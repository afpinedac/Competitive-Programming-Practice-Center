


<div class="table-responsive">
    <table class="table table-condensed table-hover">
        <thead>
            <tr>
                <td>Nombre</td>
                <td>Edad</td>
                <td>Sueldo</td>
                <td>Tipo</td>
            </tr>
        </thead>
        <tbody>
            
            {Form::open(['to' => '/'])}
            {foreach $empleados as $e}
                <tr>  
                    <td>{$e->nombre}</td>
                    <td>{$e->edad}</td>
                    <td>{$e->sueldo}</td>
                    <td>{if $e->edad<18}Menor{else}Mayor{/if} de edad</td>
                 </tr>            
            {/foreach}
            {Form::close()}

          
        </tbody>
    </table>
</div>

