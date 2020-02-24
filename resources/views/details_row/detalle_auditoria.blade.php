<div class="m-t-10 m-b-10 p-l-10 p-r-10 p-t-10 p-b-10">
	<div class="row">
		<div class="col-md-12">
            ID auditoria: {{$auditoria->id}}
        </div>
        <div class="col-md-12">
            Usuario: {{$auditoria->user->name}}
        </div>
        <div class="col-md-12">
            Evento: {{$auditoria->event}}
        </div>
        <div class="col-md-12">
            Modelo Auditado: {{$auditoria->auditable_type}}
        </div>
        <div class="col-md-12">
            ID Auditado: {{$auditoria->auditable_id}}
        </div>
        <div class="col-md-12">
            Valores Anteriores: {{$auditoria->old_values}}
        </div>
        <div class="col-md-12">
            Valores Actuales: {{$auditoria->new_values}}
        </div>
        <div class="col-md-12">
            Creado: {{$auditoria->created_at}}
        </div>
        <div class="col-md-12">
            Actualizado: {{$auditoria->updated_at}}
        </div>
	</div>
</div>
<div class="clearfix"></div>
