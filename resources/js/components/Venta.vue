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
                <table class="table table-bordered table-hover" id="detailsTable">
                    <thead class="thead-dark">
                    <tr>
                        <th>Presentación</th>
                        <th>Tipo</th>
                        <th># de Jabas</th>
                        <th># de Aves</th>
                        <th>Precio</th>
                        <th>Peso Neto</th>
                        <th>Descuento Peso</th>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="saldo">Comentario</label>
                        <textarea v-model="form.comentario" class="form-control" placeholder="Escribir comentario sobre la venta" rows="5"></textarea>
                    </div>
                </div>

                <div class="col-md-6" style="margin-top: -20px;">
                    <div class="mt-5">
                        <h4 class="mb-2">Resumen de Transacción</h4>
                        <div class="d-flex justify-content-between">
                            <span>PESO NETO TOTAL (KG):</span>
                            <span v-text="form.peso_neto" class="font-weight-bold"></span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>TOTAL DE VENTA S/:</span>
                            <span v-text="form.monto_total" class="font-weight-bold"></span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>TOTAL A PAGAR S/:</span>
                            <span v-text="form.payment.monto_total" class="font-weight-bold"></span>
                        </div>
                        <div v-if="form.payment.deuda > 0" class="d-flex justify-content-between mt-2">
                            <span>DEUDA ANTERIOR S/:</span>
                            <span v-text="form.payment.deuda" class="font-weight-bold text-danger"></span>
                        </div>
                    </div>
                </div>
            </div>



            <hr>

            <div class="row mt-5">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="comprobante" class="form-label">Seleccionar Comprobante</label>
                        <input type="file" id="comprobante" name="image" @change="getImage" accept="image/*" class="form-control-file">
                        <small class="form-text text-muted">Formato aceptado: JPG, PNG, PDF.</small>
                    </div>
                </div>

                <!-- <div class="col-md-4">
                    <div class="form-group">
                        <label for="monto_total">Monto Total a Pagar</label>
                        <input
                            v-model="form.payment.monto_total"
                            class="form-control" readonly>
                    </div>
                </div> -->
                <!-- Información de Pago -->
                <div class="col-md-2">
                    <div class="form-group"><br>
                        <label for="checkPagoCompleto" class="checkbox-label">
                            Pago Completo?
                            <input type="checkbox" v-model="form.payment.pago_completo"
                                   @change="togglePagoCompleto">
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="monto_recibido">Monto Recibido</label>
                        <input
                            v-model="form.payment.monto_recibido"
                            type="number"
                            class="form-control"
                            @change="calculateTotalsPay">
                    </div>
                </div>
                <div class="col-md-4">
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
