import './bootstrap';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import NotaIngreso from './components/NotaIngreso.vue'
import Venta from './components/Venta.vue'
import ReporteIngresos from './components/ReporteIngresos.vue'
import ReporteDespachos from './components/ReporteDespachos.vue'
import ReporteCuentasPorCobrar from './components/ReporteCuentasPorCobrar.vue'
import { createApp } from 'vue'
const app = createApp()
app.component('nota-ingreso', NotaIngreso)
app.component('venta-desde-despacho', Venta)
app.component('reporte-ingresos', ReporteIngresos)
app.component('reporte-despachos', ReporteDespachos)
app.component('reporte-cuentas-por-cobrar', ReporteCuentasPorCobrar)
app.use(VueSweetalert2);
app.mount('#app')
