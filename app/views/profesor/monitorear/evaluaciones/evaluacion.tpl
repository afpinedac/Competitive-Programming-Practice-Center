{capture assign='content'}

    
     {*<a href='#' class="pull-right"> [Ver estadisticas]</a> *}
    <a class='pull-right' href="{url('curso/monitorear/')}/{$curso->id}/evaluaciones/{$evaluacion->id}/ejercicios" title="Ver ejercicios">[Ver ejercicios]</a><br><br> 
    
   <table class="table table-hover table-bordered table-condensed">
      <thead>
        <th>Nombre</th>
        <th>Ejercicios resueltos</th>       
        <th>Porcentaje aprobacion</th>   
        <th>Aceptados / Envios</th>  
    </thead>
    <tbody>
        {foreach $estudiantes as $estudiante}
            <tr>
                <td><a href='{url('curso/monitorear')}/{$curso->id}/evaluaciones/{$evaluacion->id}/estudiantes/{$estudiante->id}'>{$estudiante->nombres|capitalize} {$estudiante->apellidos|capitalize}</a></td>
                <td> {usuario::find($estudiante->id)->get_numero_ejercicios_resultos_en_evaluacion($evaluacion->id)} / {$evaluacion->get_numero_de_ejercicios()}</td>
                <td>{usuario::find($estudiante->id)->get_porcentaje_en_evaluacion($evaluacion->id)}</td>
                <td> {usuario::find($estudiante->id)->get_numero_ejercicios_resultos_en_evaluacion($evaluacion->id)} / {usuario::find($estudiante->id)->get_numero_envios_en_evaluacion($evaluacion->id)}</td>
            </tr>
        {/foreach}

        
    </tbody>    
</table>    

{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='evaluaciones'}
