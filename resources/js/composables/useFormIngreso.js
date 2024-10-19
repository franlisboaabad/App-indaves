import { getCurrentInstance, reactive } from "vue";
import moment from "moment";
import Swal from "sweetalert2";

export function useFormIngreso(presentations, types) {
    const { proxy } = getCurrentInstance();

    const form = reactive({
        numero_guia: null,
        fecha_despacho: moment().format("YYYY-MM-DD"),
        peso_bruto: 0,
        peso_tara: 0,
        peso_neto: 0,
        tipo_ingreso:1,
        items: [],
    });

    const formItem = reactive({
        tipo_pollo_id: null,
        tipo_pollo_descripcion: null,
        presentacion_pollo_id: null,
        presentacion_pollo_descripcion: null,
        cantidad_jabas: null,
        cantidad_pollos: null,
        peso_neto: null,
        tara: 0,
        peso_promedio: 0,
    });

    formItem.tipo_pollo_id = types.length ? types[0].id : null;
    formItem.presentacion_pollo_id = presentations.length
        ? presentations[0].id
        : null;

    function addItem() {
        if (
            !formItem.tipo_pollo_id ||
            !formItem.presentacion_pollo_id ||
            !formItem.peso_neto ||
            !formItem.cantidad_pollos
        ) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Por favor, complete todos los campos requeridos.",
            });
            return;
        }
        const type = types.find((type) => type.id == formItem.tipo_pollo_id);
        formItem.tipo_pollo_descripcion = type?.descripcion;
        const presentation = presentations.find(
            (type) => type.id == formItem.presentacion_pollo_id
        );
        formItem.presentacion_pollo_descripcion = presentation?.descripcion;

        formItem.peso_promedio = _.round(
            formItem.peso_neto / formItem.cantidad_pollos,
            2
        );
        form.items.push({ ...formItem });
        calculateTotals();
        resetForm();
    }

    function deleteItem(index) {
        form.items.splice(index, 1);
        calculateTotals();
    }

    function calculateTotals() {
        form.cantidad_jabas = _.sumBy(form.items, "cantidad_jabas");
        form.cantidad_pollos = _.sumBy(form.items, "cantidad_pollos");
        form.peso_total = _.sumBy(form.items, "peso_bruto");
    }

    function resetForm() {
        formItem.cantidad_jabas = null;
        formItem.cantidad_pollos = null;
        formItem.peso_neto = null;
    }

    function sendForm(url) {

        //validar que la tabla no este vacia.
        if (!form.items.length) {
            proxy.$swal({
                icon: "warning",
                title: "Información",
                text: 'La tabla no tiene datos.',
                animation: false,
                position: "center",
                confirmButtonText: "Aceptar",
            });
            return;
        }

        axios
            .post(url, form)
            .then(({ data }) => {
                proxy
                    .$swal({
                        icon: "success",
                        title: "Éxito",
                        text: data.message,
                        animation: false,
                        position: "center",
                        confirmButtonText: "Aceptar",
                    })
                    .then(() => {
                        location.href = "/ordenes-ingreso";
                    });
            })
            .catch(({ response }) => {
                const errorMessage =
                    response?.data?.message ||
                    "Ocurrió un error al enviar el formulario.";
                proxy.$swal({
                    icon: "error",
                    title: "Error",
                    text: errorMessage,
                    animation: false,
                    position: "center",
                    confirmButtonText: "Aceptar",
                });
            });
    }

    return {
        form,
        formItem,
        addItem,
        deleteItem,
        sendForm,
    };
}
