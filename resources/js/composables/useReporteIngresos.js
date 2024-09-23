import {getCurrentInstance, reactive} from "vue";
import moment from "moment";
import Swal from "sweetalert2";

export function useReporteIngresos() {
    const {proxy} = getCurrentInstance();

    const form = reactive({
        date_init: moment().format("YYYY-MM-DD"),
        date_end: moment().format("YYYY-MM-DD"),
        tipo_pollo_id: null,
        presentacion_pollo_id: null,
        items : []
    });

    function resetForm() {
        form.date_init = null;
        form.date_end = null;
        form.tipo_pollo_id = null;
        form.presentacion_pollo_id = null
    }

    function sendForm(url) {
        axios.post(url, form)
            .then(({data}) => {

                form.items = data;
            })
            .catch(({response}) => {

            });
    }

    function sendExport(url, format) {
        axios.post(url)
            .then(({data}) => {
                forceFileDownload(data,format);
            })
            .catch(({response}) => {

            });
    }

    function forceFileDownload(response,format) {
        const url = window.URL.createObjectURL(new Blob([response.message]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download",  `${response.file}.${format}`);
        document.body.appendChild(link);
        link.click();
    }

    return {
        form,
        sendForm,
        sendExport
    };
}
