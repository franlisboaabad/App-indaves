<script setup>

import {useVenta} from "../composables/useVenta";

const props = defineProps({
    orden: Object,
    paymentMethods: Array,
    cliente: Object,
    routeSave: String,
    serie: String
})


const {
    form,
    sendForm,
    calculateSubTotal,
    calculateTotals,
    calculateTotalsPay,
    togglePagoCompleto,
    getImage
} = useVenta(props.orden, props.paymentMethods, props.cliente, props.serie)

calculateTotals()

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
                        <input type="text" v-model="form.serie_venta" class="form-control" required readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_venta">Fecha de Venta</label>
                        <input type="date" id="fecha_venta" name="fecha_venta" class="form-control" required
                               v-model="form.fecha_venta">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <input type="text" readonly class="form-control" :value="cliente.razon_social">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="saldo">Saldo</label>
                        <input :value="form.saldo_pendiente"
                               class="form-control" readonly>
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
                        <th># de Jabas</th>
                        <th># de Aves</th>
                        <th>Precio</th>
                        <th>Peso Neto</th>
                        <th>Descuento</th>
                        <th>Sub Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,key) in form.detalles">
                        <td v-text="item.presentacion_pollo_descripcion"></td>
                        <td v-text="item.tipo_pollo_descripcion"></td>
                        <td v-text="item.cantidad_jabas"></td>
                        <td v-text="item.cantidad_pollos"></td>
                        <td>
                            <input type="number" v-model.sync="item.precio" class="form-control"
                                   @change="calculateSubTotal(key)">
                        </td>
                        <td v-text="item.peso_neto"></td>
                        <td>
                            <input type="number" v-model.sync="item.descuento_peso" class="form-control"
                                   @change="calculateSubTotal(key)">
                        </td>
                        <td>
                            <input type="number" readonly v-model.sync="item.subtotal" class="form-control">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-8">

                    <div class="form-group">
                        <label for="saldo">Comentario</label>
                        <textarea v-text="form.comentario" class="form-control"></textarea>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="form-inline mt-5">
                        <h4 class="mr-3 w-50">PESO NETO TOTAL: </h4>
                        <h4 v-text="form.peso_neto" class="text-right w-25"></h4>
                        <h4 class="mr-3 w-50">TOTAL DE VENTA: </h4>
                        <h4 v-text="form.monto_total" class="text-right w-25"></h4>
                        <h4 class="mr-3 w-50" v-if="form.payment.deuda > 0">DEUDA ANTERIOR: </h4>
                        <h4 v-if="form.payment.deuda > 0" v-text="form.payment.deuda" class="text-right w-25"></h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="form-group">
                        <label for="forma_de_pago">Forma de Pago</label>
                        <select
                            v-model="form.payment.forma_de_pago"
                            class="form-control">
                            <option value="0">Contado</option>
                            <option value="1">Credito</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="form-group">
                        <label for="metodo_pago_id">Método de Pago</label>
                        <select
                            v-model="form.payment.metodo_pago_id"
                            class="form-control">
                            <option v-for="method in paymentMethods" :value="method.id"
                                    v-text="method.descripcion"></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div>
                        <input type="file" name="image" @change="getImage" accept="image/*">
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="form-group">
                        <label for="monto_total">Monto Total a Pagar</label>
                        <input
                            v-model="form.payment.monto_total"
                            class="form-control" readonly>
                    </div>
                </div>
                <!-- Información de Pago -->
                <div class="col-md-2 mb-4">
                    <div class="form-group"><br>
                        <label for="checkPagoCompleto" class="checkbox-label">
                            Desea hacer el pago completo?
                            <input type="checkbox" v-model="form.payment.pago_completo"
                                   @change="togglePagoCompleto">
                        </label>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <div class="form-group">
                        <label for="monto_recibido">Monto Recibido</label>
                        <input
                            v-model="form.payment.monto_recibido"
                            type="number"
                            class="form-control"
                            @change="calculateTotalsPay">
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="form-group">
                        <label for="saldo">Monto Pendiente</label>
                        <input v-model="form.payment.monto_pendiente" class="form-control" readonly>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-success" @click="sendForm(props.routeSave)">Registrar Venta</button>
        </div>

    </div>

</template>
