<script setup>

import {useFormIngreso} from '../composables/useFormIngreso'
import {useVenta} from "../composables/useVenta";

const props = defineProps({
    orden: Object,
    paymentMethods: Array,
    cliente : Object,
    routeSave : String,
    serie : String
})


const { form, sendForm}  = useVenta(props.orden,props.paymentMethods)


</script>

<template>
    <div class="card">
        <div class="card-body">
            <input type="hidden" name="type" value="ingreso">
            <div class="row">
                <!-- Serie de Venta -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="serie_venta">Serie de Venta</label>
                        <input type="text" :value="serie" class="form-control" required  readonly>
                    </div>
                </div>

                <!-- Fecha de Venta -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_venta">Fecha de Venta</label>
                        <input type="date" id="fecha_venta" name="fecha_venta" class="form-control" required>
                    </div>
                </div>

                <!-- Select Cliente -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <input type="text" readonly class="form-control" :value="cliente.razon_social">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="saldo">Saldo</label>
                        <input type="text" id="saldo_pendiente" name="saldo_pendiente" class="form-control" readonly
                               value="0">
                    </div>
                </div>


            </div>


            <!-- Tabla de Detalles -->
            <div class="mt-4">
                <table class="table table-bordered" id="detailsTable">
                    <thead>
                    <tr>
                        <th>Presentación</th>
                        <th>Tipo</th>
                        <th>Número de Jabas</th>
                        <th>Número de Aves</th>
                        <th>Peso Bruto</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,key) in form.detalles" >
                        <td v-text="item.presentacion_pollo_descripcion"></td>
                        <td v-text="item.tipo_pollo_descripcion"></td>
                        <td v-text="item.cantidad_jabas"></td>
                        <td v-text="item.cantidad_pollos"></td>
                        <td v-text="item.peso_neto"></td>
                        <td><button class="btn btn-danger btn-sm" @click="deleteItem(key)"> X</button></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <button type="button"  class="btn btn-success"

                    @click="sendForm(props.routeSave)">Registrar Venta</button>
        </div>
    </div>

</template>
