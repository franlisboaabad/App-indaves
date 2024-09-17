import { getCurrentInstance, reactive} from "vue";
import moment from "moment";
export function useVenta(order, paymentMethods) {

    const { proxy } = getCurrentInstance();

    const form = reactive({
        orden_despacho_id : order.orden_despacho_id,
        cliente : order.cliente,
        detalles : [... order.detalles]
    })

    function calculateTotals(){

    }

    function sendForm(url) {
        axios.post(url, form)
            .then(({data})=>{

            })
    }

    return {
        form,
        sendForm
    };
}
