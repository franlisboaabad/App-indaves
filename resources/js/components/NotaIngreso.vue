<script setup>

import {useFormIngreso} from '../composables/useFormIngreso'

const props = defineProps({
    presentations: Array,
    types: Array,
    routeSave : String,
})


const { form, formItem,addItem,deleteItem, sendForm}  = useFormIngreso(props.presentations,props.types)


</script>

<template>
    <div class="card">
        <div class="card-body">
            <input type="hidden" name="type" value="ingreso">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="serie_orden">Serie de Orden</label>
                        <input type="text" v-model="form.numero_guia" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Fecha de Ingreso</label>
                        <input type="date" v-model="form.fecha_despacho" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Peso Bruto</label>
                        <input v-model="form.peso_bruto" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Total Tara</label>
                        <input v-model="form.peso_tara" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Peso Neto</label>
                        <input v-model="form.peso_neto" class="form-control">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="fecha_despacho">Tipo de Ingreso</label>
                       <select name="" id="" class="form-control" v-model="form.tipo_ingreso">
                        <option value="1">CAMION</option>
                        <option value="2">POR STOCK</option>
                        <option value="3">MERMA</option>
                       </select>
                    </div>
                </div>



            </div>
            <hr>
            <div class="row mt-5">
                <div class="col-md-2 mb-5">
                    <div class="form-grup">
                        <label>Presentacion de Pollo:</label>
                        <select class="form-control" v-model="formItem.presentacion_pollo_id">
                            <option v-for="presentation in props.presentations " v-text="presentation.descripcion" :value="presentation.id"></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 mb-5">
                    <div class="form-grup">
                        <label >Tipo de Pollo:</label>
                        <select class="form-control" v-model="formItem.tipo_pollo_id">
                            <option v-for="type in props.types " v-text="type.descripcion" :value="type.id"></option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <div class="form-group" >
                        <label for="cantidad_jabas">Número de Jabas</label>
                        <input type="number" v-model="formItem.cantidad_jabas" class="form-control">
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        <label for="cantidad_pollos">Número de Aves</label>
                        <input type="number"  v-model="formItem.cantidad_pollos" class="form-control">
                    </div>
                </div>




                <div class="col-md-2">
                    <div class="form-group">
                        <label for="peso_bruto">Peso Neto</label>
                        <input type="number" step="0.01" v-model="formItem.peso_neto"class="form-control">
                    </div>
                </div>
                <div class="col-md-2 pt-4">
                    <button type="button" id="addDetailBtn" class="btn btn-primary mt-2" @click.prevent="addItem">Agregar al Detalle</button>
                </div>


            </div>

            <!-- Tabla de Detalles -->
            <div class="mt-4">
                <table class="table table-bordered" id="detailsTable">
                    <thead class="table-dark">
                    <tr>
                        <th>Presentación</th>
                        <th>Tipo</th>
                        <th>Número de Jabas</th>
                        <th>Número de Aves</th>
                        <th>Peso Neto</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item,key) in form.items" >
                        <td v-text="item.presentacion_pollo_descripcion"></td>
                        <td v-text="item.tipo_pollo_descripcion"></td>
                        <td v-text="item.cantidad_jabas"></td>
                        <td v-text="item.cantidad_pollos"></td>
                        <td v-text="item.peso_neto"></td>
                        <td><button class="btn btn-danger btn-sm" @click="deleteItem(key)"> X</button></td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <button type="button"  class="btn btn-success"

                    @click="sendForm(props.routeSave)">Registrar Orden</button>
        </div>
    </div>

</template>
