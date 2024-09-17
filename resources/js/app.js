import './bootstrap';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import NotaIngreso from './components/NotaIngreso.vue'
import { createApp } from 'vue'

const app = createApp()
app.component('nota-ingreso', NotaIngreso)

app.use(VueSweetalert2);
app.mount('#app')
