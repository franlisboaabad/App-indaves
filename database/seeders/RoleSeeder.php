<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $RoleAdmin = Role::create(['name' => 'Admin']);
        //Roles Adicionales App Tareas
        $RoleColaborator = Role::create(['name' => 'Colaborador']);
        // $RoleCustomer = Role::create(['name' => 'Cliente']);



        Permission::create(['name' => 'admin.home','description'=>'Ver Dashboard'])->syncRoles([$RoleAdmin, $RoleColaborator]);

        Permission::create(['name' => 'admin.roles.index', 'description' => 'Lista de roles'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.roles.create', 'description' => 'Registrar rol'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.roles.edit', 'description' => 'Editar rol'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.roles.destroy', 'description' => 'Eliminar rol'])->syncRoles($RoleAdmin);


        /* permisos Role 1 para usuarios */
        Permission::create(['name' => 'admin.usuarios.index', 'description' => 'Lista de usuarios'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.usuarios.edit', 'description' => 'Editar usuario'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.usuarios.update', 'description' => 'Actualizar usuario y asignar roles'])->syncRoles($RoleAdmin);


        Permission::create(['name' => 'admin.invitados.index', 'description' => 'Lista de invitados'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.invitados.create', 'description' => 'Registrar invitado'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.invitados.edit', 'description' => 'Editar invitado'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.invitados.destroy', 'description' => 'Eliminar invitado'])->syncRoles($RoleAdmin);


        Permission::create(['name' => 'admin.clientes.index', 'description' => 'Lista de clientes'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.clientes.create', 'description' => 'Registrar cliente'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.clientes.edit', 'description' => 'Editar cliente'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.clientes.show', 'description' => 'Ver cliente'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.clientes.destroy', 'description' => 'Eliminar cliente'])->syncRoles($RoleAdmin);


        Permission::create(['name' => 'admin.actividad.index', 'description' => 'Lista de actividades'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.actividad.create', 'description' => 'Registrar actvidad'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.actividad.edit', 'description' => 'Editar actividad'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.actividad.destroy', 'description' => 'Eliminar actividad'])->syncRoles($RoleAdmin);


        Permission::create(['name' => 'admin.proyectos.index', 'description' => 'Lista de proyectos'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.proyectos.create', 'description' => 'Registrar proyecto'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.proyectos.edit', 'description' => 'Editar proyecto'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.proyectos.show', 'description' => 'Ver proyecto'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.proyectos.destroy', 'description' => 'Eliminar proyecto'])->syncRoles($RoleAdmin);



        Permission::create(['name' => 'admin.tareas.index', 'description' => 'Lista de tareas'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.tareas.create', 'description' => 'Registrar proyecto'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.tareas.edit', 'description' => 'Editar proyecto'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.tareas.show', 'description' => 'Ver proyecto'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.tareas.destroy', 'description' => 'Eliminar proyecto'])->syncRoles($RoleAdmin);



        Permission::create(['name' => 'admin.equipos.index', 'description' => 'Lista de equipos'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.equipos.create', 'description' => 'Registrar equipo'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.equipos.edit', 'description' => 'Editar equipo'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.equipos.destroy', 'description' => 'Eliminar equipo'])->syncRoles($RoleAdmin);


        Permission::create(['name' => 'admin.ordenes.index', 'description' => 'Lista de ordenes'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.ordenes.create', 'description' => 'Registrar orden'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.ordenes.edit', 'description' => 'Editar orden'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.ordenes.destroy', 'description' => 'Eliminar orden'])->syncRoles($RoleAdmin);


        //sorteos
        Permission::create(['name' => 'admin.sorteos.index', 'description' => 'Lista de sorteos'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.sorteos.create', 'description' => 'Registrar sorteo'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.sorteos.edit', 'description' => 'Editar sorteo'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.sorteos.show', 'description' => 'Ver sorteo'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.sorteos.destroy', 'description' => 'Eliminar sorteo'])->syncRoles($RoleAdmin);

        Permission::create(['name' => 'admin.registros.index', 'description' => 'Lista de registros'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.registros.create', 'description' => 'Registrar registro de sorteo'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.registros.edit', 'description' => 'Editar registro de sorteo'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.registros.show', 'description' => 'Ver registro'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.registros.destroy', 'description' => 'Eliminar registro'])->syncRoles($RoleAdmin);


        //Indaves


        Permission::create(['name' => 'admin.cajas.index', 'description' => 'Lista de cajas'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.cajas.create', 'description' => 'Registrar caja'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.cajas.edit', 'description' => 'Editar caja'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.cajas.show', 'description' => 'Ver caja'])->syncRoles($RoleAdmin);
        Permission::create(['name' => 'admin.cajas.destroy', 'description' => 'Eliminar caja'])->syncRoles($RoleAdmin);





    }
}
