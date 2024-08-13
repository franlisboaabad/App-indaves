<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class DemoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    // public $rutaArchivo;


    // /**
    //  * Create a new message instance.
    //  *
    //  * @return void
    //  */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }


    // /**
    //  * Get the message envelope.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Envelope
    //  */
    // public function envelope()
    // {
    //     return new Envelope(
    //         subject: 'Demo Email',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Content
    //  */
    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array
    //  */
    // public function attachments()
    // {
    //     return [];
    // }

    public function build()
    {
        $rutaArchivoPDF = storage_path('app/public/' . $this->ticket->ticketpdf);

        return $this->from('info@ideassoftperu.com', 'EL TRIKI')
            ->view('emails.demo')
            ->subject('Ticket generado | EL TRIKI')
            ->attach($rutaArchivoPDF, [
                'as' => 'ticket_' . $this->ticket->id . '_detalles.pdf',
                'mime' => 'application/pdf', // Tipo MIME del archivo adjunto
            ]);
    }
}
