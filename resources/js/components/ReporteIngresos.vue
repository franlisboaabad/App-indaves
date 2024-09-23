<script setup>

import {useReporteIngresos} from '../composables/useReporteIngresos'

const props = defineProps({
    presentations: Array,
    types: Array,
    routeSend: String,
    routeExportPdf : String,
    routeExportExcel : String,
    csrf : String
})
const {form, sendForm,sendExport} = useReporteIngresos()

sendForm(props.routeSend)
</script>

<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Fecha de Inicio</label>
                        <input type="date" v-model="form.date_init" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Fecha de Fin</label>
                        <input type="date" v-model="form.date_end" class="form-control" required>
                    </div>
                </div>
<!--                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Presentación de Pollo</label>
                        <select class="form-control" v-model="form.presentacion_pollo_id">
                            <option v-for="type in props.types " v-text="type.descripcion" :value="type.id"></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Tipo de pollo</label>
                        <select class="form-control" v-model="form.tipo_pollo_id">
                            <option v-for="type in props.types " v-text="type.descripcion" :value="type.id"></option>
                        </select>
                    </div>
                </div>-->
                <div class="col-md-6 pt-2 form-inline">
                    <button type="button"
                            class="btn btn-success mt-2 ml-2"
                            @click="sendForm(props.routeSend)"><i class="fa fa-search mr-2"></i>Búscar</button>
                    <form :action="props.routeExportPdf" method="POST" target="_blank">
                        <input v-model="props.csrf" name="_token" type="hidden">
                        <input v-model="form.date_init" name="date_init" type="hidden">
                        <input v-model="form.date_end" name="date_end" type="hidden">
                        <button class="btn btn-danger mt-2 ml-2"
                                ><i class="fa fa-file-pdf mr-2"></i>PDF</button>
                    </form>

                    <form :action="props.routeExportExcel" method="POST" target="_blank">
                        <input v-model="props.csrf" name="_token" type="hidden">
                        <input v-model="form.date_init" name="date_init" type="hidden">
                        <input v-model="form.date_end" name="date_end" type="hidden">
                        <button
                            class="btn btn-info mt-2 ml-2"
                        ><i class="fa fa-file-excel mr-2"></i>Excel</button>
                    </form>
                </div>
            </div>
            <hr>
            <!-- Tabla de Detalles -->
            <div class="mt-4">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Usuario Registro</th>
                        <th>Fecha Ingreso</th>
                        <th>Número de Guía</th>
                        <th>Total Aves</th>
                        <th>Peso Bruto</th>
                        <th>Peso Tara</th>
                        <th>Peso Neto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,key) in form.items">
                        <td v-text="item.user_name"></td>
                        <td v-text="item.fecha_ingreso"></td>
                        <td v-text="item.numero_guia"></td>
                        <td v-text="item.detalle_sum_cantidad_pollos"></td>
                        <td v-text="item.peso_bruto"></td>
                        <td v-text="item.peso_tara"></td>
                        <td v-text="item.peso_neto"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>
