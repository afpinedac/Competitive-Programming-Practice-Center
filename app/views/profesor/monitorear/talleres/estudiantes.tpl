{capture assign='content'}

  <div ng-controller="MonitorearTaller" ng-init="(MonitorearTaller({$taller->id}))">
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
    <br>
    <br>


    {* <a href='#' class="pull-right"> [Ver estadisticas]</a> *}
    <a class='pull-right' href="{url('curso/monitorear/')}/{$curso->id}/talleres/{$taller->id}/ejercicios" title="Ver ejercicios">[Ver ejercicios]</a><br><br> 

    <table class="table table-striped table-condensed table-bordered" ng-init="sortby='nombre_completo'; reverse=false">
      <thead>
      <th>Estudiante <span class="pull-right"><a href='' ng-click="sortby='nombre_completo'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='nombre_completo'; reverse=false;">&DownArrow;</a></span></th>
     <th>Ejercicios resueltos <span class="pull-right"><a href="" ng-click="sortby='porcentaje'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='porcentaje'; reverse=false;">&DownArrow;</a></span></th>
      <th>Último envio <span class="pull-right"><a href="" ng-click="sortby='ultimo_envio'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='ultimo_envio'; reverse=false;">&DownArrow;</a></span></th>
      <th>Envios <span class="pull-right"><a href="" ng-click="sortby='envios_en_taller'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='envios_en_taller'; reverse=false;">&DownArrow;</a></span></th>
      </thead>
      <tbody>
        <tr ng-repeat="estudiante in estudiantes | orderBy:sortby:reverse">
          <td><a  href='{url('curso/monitorear')}/{$curso->id}/talleres/{$taller->id}/estudiantes/[[estudiante.id]]'>[[estudiante.nombre_completo]]</a></td>
          <td>[[estudiante.ejercicios_resueltos]] ([[estudiante.porcentaje | number:2]]%)</td>
          <td>[[estudiante.ultimo_envio]]</td>
          <td> [[estudiante.envios_en_taller]]</td>
        </tr>

      
      </tbody>    
    </table>    
    
  </div>

{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='talleres'}
