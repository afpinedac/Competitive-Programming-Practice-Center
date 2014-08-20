{capture assign='content'}
    
    
    <div class="row-fluid">
        <div class="span12">

            {*char de envios*}    
    {assign var=envios value=envio::get_numero_envios('taller',$taller->id)}
{assign var=title value='Envios realizados por el estudiante'}

{include file='../../../graficas/timeline.tpl'}

{*end char de envios*}
<center><div  id="timeline" style="width: 700px; height: 300px;"></div></center>
            
            
        </div>
    </div>
<br>
    

   {* <a href='#' class="pull-right"> [Ver estadisticas]</a> *}
    <a class='pull-right' href="{url('curso/monitorear/')}/{$curso->id}/talleres/{$taller->id}/ejercicios" title="Ver ejercicios">[Ver ejercicios]</a><br><br> 
    
    <table class="table table-striped table-condensed table-bordered">
        <thead>
        <th><a href='{url('curso/monitorear')}/{$curso->id}/talleres/{$taller->id}?sortby=nombres'>Estudiante</a></th>
        <th><a href='{url('curso/monitorear')}/{$curso->id}/talleres/{$taller->id}?sortby=porcentaje'>Porcentaje</a></th>
        <th><a href='{url('curso/monitorear')}/{$curso->id}/talleres/{$taller->id}?sortby=ultimo_envio'>Último envío</a></th>
        <th><a href='{url('curso/monitorear')}/{$curso->id}/talleres/{$taller->id}?sortby=ejercicios_resueltos'>Aceptados / Envios</a> </th>
    </thead>
    <tbody>
        
        {foreach $estudiantes_inscritos as $estudiante}
            {if not usuario::find($estudiante['id'])->es_monitor($curso->id)}
        <tr>
            <td><a href='{url('curso/monitorear')}/{$curso->id}/talleres/{$taller->id}/estudiantes/{$estudiante['id']}'>{$estudiante['nombres']|capitalize} {$estudiante['apellidos']|capitalize}</a></td>
            <td>{$estudiante['porcentaje']|string_format:"%.2f"}%</td>
            <td>{$estudiante['ultimo_envio']}</td>
            <td>{$estudiante['ejercicios_resueltos']} / {usuario::find($estudiante['id'])->get_numero_envios_en_taller($taller->id)}</td>
        </tr>        
        {/if}
        {/foreach}
    </tbody>    
</table>    
    
    
    
{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='talleres'}
