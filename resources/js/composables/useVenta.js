import {getCurrentInstance, onMounted, reactive} from "vue";
import moment from "moment";

export function useVenta(order, paymentMethods, cliente,serie) {

    const {proxy} = getCurrentInstance();

    const form = reactive({
        orden_despacho_id: order.id,
        serie_venta : serie,
        cliente: cliente,
        cliente_id: cliente?.id,
        fecha_venta: moment().format('YYYY-MM-DD'),
        detalles: [...order.detalles],
        monto_total: 0,
        comentario : null,
        saldo_pendiente: cliente.saldos_sum_total,
        imagen: null,
        peso_neto : 0,
        payment: {
            forma_de_pago: 0,
            metodo_pago_id: paymentMethods?.length ? paymentMethods[0].id : null,
            pago_completo: false,
            monto_recibido: null,
            monto_pendiente: null,
            monto_total: 0,
            deuda : cliente.deuda_pendiente,
            image: null
        }
    })

    function calculateTotals() {
        form.monto_total = _.sumBy(form.detalles, 'subtotal');
        form.peso_neto = _.sumBy(form.detalles, 'peso_neto') - _.sumBy(form.detalles, 'descuento_peso');
        calculateTotalsPay();
    }

    function calculateTotalsPay() {
        const saldoActual = _.isNil(form.cliente?.saldos_sum_total) ? 0 : form.cliente?.saldos_sum_total;
        form.payment.monto_total = _.round(form.monto_total - saldoActual + +form.payment.deuda, 2);
        if (form.payment.monto_recibido) {
            form.payment.monto_pendiente = +form.payment.monto_recibido - +form.payment.monto_total;
        }
    }

    function calculateSubTotal(index) {
        form.detalles[index].subtotal = +form.detalles[index].precio * ( +form.detalles[index].peso_neto -  +form.detalles[index].descuento_peso) ;
        calculateTotals();
    }

    function togglePagoCompleto() {
        form.payment.monto_recibido = form.payment.pago_completo ? form.payment.monto_total : null;
        form.payment.saldo = 0;
    }

    function getImage(event){
        form.imagen = event.target.files[0];
    }

    function buildFormData(formData, data, parentKey) {
        if (data && typeof data === 'object' && !(data instanceof Date) && !(data instanceof File) && !(data instanceof Blob)) {
            Object.keys(data).forEach(key => {
                buildFormData(formData, data[key], parentKey ? `${parentKey}[${key}]` : key);
            });
        } else {
            const value = data == null ? '' : data;

            formData.append(parentKey, value);
        }
    }

    function makeForm(){
        const formData = new FormData();
        buildFormData(formData, form);

        formData.append('imagen', form.imagen);

        return formData;
    }
    function sendForm(url) {
        const newForm = makeForm();
        axios.post(url, newForm)
            .then(({data}) => {
                proxy.$swal({
                    toast: true,
                    icon: 'success',
                    title: data.message,
                    animation: false,
                    position: 'top-right',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
                console.log(data);

            })
            .catch(({response}) =>{
                if(response.data){
                    proxy.$swal({
                        toast: true,
                        icon: 'error',
                        title: response.data.message,
                        animation: false,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
            })
    }

    return {
        form,
        sendForm,
        calculateSubTotal,
        calculateTotals,
        calculateTotalsPay,
        togglePagoCompleto,
        getImage
    };
}
