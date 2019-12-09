<?php

namespace App\Http\Controllers\Admin\Extra;

use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as OriginalUserCrudController;


class CustomUserCrudController extends OriginalUserCrudController
{
    public function setup()
    {
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.user'), trans('backpack::permissionmanager.users'));
        $this->crud->setRoute(backpack_url('user'));

        $this->crud->denyAccess(['create', 'update','delete','list','show']);

        if (backpack_user()->hasPermissionTo('createUser')) {
            $this->crud->allowAccess('create');
        }
        if (backpack_user()->hasPermissionTo('updateUser')) {
            $this->crud->allowAccess('update');
        }
        if (backpack_user()->hasPermissionTo('deleteUser')) {
            $this->crud->allowAccess('delete');
        }
        if (backpack_user()->hasPermissionTo('listUser')) {
            $this->crud->allowAccess('list');
        }
        if (backpack_user()->hasPermissionTo('showUser')) {
            $this->crud->allowAccess('show');
        }
        
        //SI el usuario es un admin muestra solo los ingresos de insumos del comedor del cual es responsable
        if (backpack_user()->hasRole('operativo')) {
            $this->crud->addClause('whereHas', 'persona', function ($query) {
                $query->where('comedor_id', backpack_user()->persona->comedor_id);
            });
        }
    }

}
