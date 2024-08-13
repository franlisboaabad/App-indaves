<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Sorteo;
use App\Models\Ticket;
use App\Mail\DemoEmail;
use App\Models\Registro;
use App\Mail\RechazadoMail;
use Illuminate\Http\Request;
use App\Models\DetalleTicket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegistroController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.registros.index')->only('index');
        $this->middleware('can:admin.registros.create')->only('create', 'store');
        $this->middleware('can:admin.registros.edit')->only('edit', 'update');
        $this->middleware('can:admin.registros.show')->only('show');
        $this->middleware('can:admin.registros.destroy')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sorteos = Sorteo::all();
        $registros = Registro::with('tickets.detalles')->get();
        return view('admin.registros.index', compact('registros', 'sorteos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sorteos = Sorteo::get()->where('estado', 1);
        return view('admin.registros.create', compact('sorteos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'sorteo_id' => 'required',
            'numero_identidad' => 'required|string|max:10',
            'nombre_apellidos' => 'required|string',
            'celular' => 'required|string',
            'monto' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // max:2048 significa 2MB
        ]);

        // Obtener la fecha y hora actual de Perú
        $fecha_actual = Carbon::now('America/Lima');

        // Agregar la fecha y hora actual al request antes de crear el registro
        $request->merge(['created_at' => $fecha_actual]);

        $registro = Registro::create($request->all());

        if ($request->file('image')) {
            $registro->image = $request->file('image')->store('registros', 'public');
            $registro->save();
        }

        return back()->with('success', 'Se realizó el registro correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function show(Registro $registro)
    {
        return view('admin.registros.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function edit(Registro $registro)
    {
        $sorteos = Sorteo::get()->where('estado', 1);
        return view('admin.registros.edit', compact('registro', 'sorteos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Registro $registro)
    {
        $request->validate([
            'sorteo_id' => 'required',
            'numero_identidad' => 'required|string|max:10',
            'nombre_apellidos' => 'required|string',
            'celular' => 'required|string',
            'monto' => 'required',
            'estado_registro' => 'required'
        ]);

        $Estado = $request->estado_registro;

        // Generacion de ticket,0 = en proceso, 1 = aprobado, 2 = rechazado
        if ($Estado === "1") {
            // Registro de cliente Aprobado
            $sorteo = Sorteo::where('estado', 1)->first(); // Obtener el primer sorteo activo

            // Actualizar el registro según los datos enviados en la solicitud
            $registro->update($request->all());

            if ($request->file('image')) {
                $registro->image = $request->file('image')->store('registros', 'public');
                $registro->save();
            }


            // Calcular la cantidad de tickets a partir del monto y opciones del sorteo
            $cantidad_tickets = $request->monto * $sorteo->opciones;

            // Crear un nuevo registro en la tabla Tickets asociado al registro actual
            $ticket = Ticket::create(['registro_id' => $registro->id, 'cantidad_tickets' => $cantidad_tickets]);
            $ultimoCorrelativo = DetalleTicket::orderByDesc('correlativo_ticket')
                ->value('correlativo_ticket');
            // Convertir el valor obtenido a entero
            $ultimo_correlativo_int = (int) $ultimoCorrelativo;

            if ($ultimo_correlativo_int === 0 && $ultimoCorrelativo !== null) {
                $ultimo_correlativo_int = (int) $ultimoCorrelativo;
            }

            // Registro detalle de Tickets
            for ($i = 1; $i <= $cantidad_tickets; $i++) {

                // Incrementar el correlativo
                $nuevo_correlativo = str_pad($ultimo_correlativo_int + $i, 6, '0', STR_PAD_LEFT); // Genera el correlativo con ceros a la izquierda

                DetalleTicket::create([
                    'ticket_id' => $ticket->id,
                    'correlativo_ticket' => $nuevo_correlativo
                ]);

                // Incrementar la cantidad vendida en el sorteo
                $sorteo->cantidad_vendida++;
                $sorteo->save();
            }

            // Generar y guardar el PDF
            $pdfPath = $this->generarPDF($sorteo->id, $registro->id,  $ticket->id);
            $ticket->ticketpdf = $pdfPath;
            $ticket->save();

            $ObtenerTicket = Ticket::find($ticket->id);
            Mail::to($registro->email)->send(new DemoEmail($ObtenerTicket));


            return back()->with('success', 'El PDF se ha generado y el correo electrónico se ha enviado correctamente.');
        } else if ($Estado === "2") {
            // Rechazado
            try {
                //code...
                $registro->update($request->all());
                Mail::to($registro->email)->send(new RechazadoMail());
                return back()->with('success', 'El registro del ticket fue rechazado.');
            } catch (\Exception $e) {
                dd($e);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Registro  $registro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Registro $registro)
    {
        //
    }


    public function generarPDF($sorteoId, $registroId, $ticketId)
    {
        $sorteo = Sorteo::find($sorteoId);
        $registro = Registro::find($registroId);
        $ticket = Ticket::find($ticketId);
        // Obtener los detalles de los tickets para el ticket dado
        $detalles = DetalleTicket::where('ticket_id', $ticketId)->get();

        // Renderizar la vista Blade para obtener el HTML
        $html = View::make('ticket/template', compact('sorteo', 'registro', 'ticket', 'detalles'))->render();

        // Configurar dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Renderizar el PDF
        $dompdf->render();

        // Nombre del archivo PDF
        $filename = 'ticket_' . $ticketId . '_detalles.pdf';

        // Guardar el PDF en storage/app/public/pdfs
        $pdfPath = storage_path('app/public/pdfs/' . $filename);
        file_put_contents($pdfPath, $dompdf->output());

        // Devolver la ruta pública relativa del archivo PDF generado
        return 'pdfs/' . $filename;
    }
}
