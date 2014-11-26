{capture assign='content'}
    
   <div ng-controller='MonitorearEstudiante' ng-init='MonitorearEstudiante({$curso->id})'> 
 
    
    <table class="table table-hover table-bordered table-condensed">
      <thead>
        
         <th><input type="text" placeholder="Estudiante" class="input-mini span12" ng-model="estudiante.nombre_completo">
           Estudiante <span class="pull-right"><a href='' ng-click="sortby='nombre_completo'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='nombre_completo'; reverse=false;">&DownArrow;</a></span></th>
        
           <th>Ejercicios resueltos <span class="pull-right"><a href='' ng-click="sortby='ejercicios_resueltos'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='ejercicios_resueltos'; reverse=false;">&DownArrow;</a></span></th>
       
         <th>Tiempo en la plataforma (min) <span class="pull-right"><a href='' ng-click="sortby='tiempo_logueado'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='tiempo_logueado'; reverse=false;">&DownArrow;</a></span></th>
          <th>Fecha de última sesión <span class="pull-right"><a href='' ng-click="sortby='ultimo_acceso'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='ultimo_acceso'; reverse=false;">&DownArrow;</a></span></th> 
        <th>Puntos <span class="pull-right"><a href='' ng-click="sortby='puntos'; reverse=true;">&UpArrow;</a><a href="" ng-click="sortby='puntos'; reverse=false;">&DownArrow;</a></span></th>
        <th><a href="#">Monitor</a></th>
    </thead>
    <tbody ng-init="sortby='nombre_completo'; reverse=false">
      
      
           <tr ng-repeat="estudiante in estudiantes | orderBy:sortby:reverse | filter:estudiante">
          <td>[[estudiante.nombre_completo]]</td>
          <td>[[estudiante.ejercicios_resueltos]] </td>
          <td>[[estudiante.tiempo_logueado]]</td>
          <td> [[estudiante.ultimo_acceso]]</td>
          <td> [[estudiante.puntos]]</td>
          <td>
            <input type="checkbox" ng-if='estudiante.es_monitor' checked ng-value="[[estudiante.id]]" ng-click="set_monitor([[estudiante.id]], {$curso->id})">
            <input type="checkbox" ng-if='!estudiante.es_monitor'   ng-value="estudiante.id" ng-click="set_monitor([[estudiante.id]], {$curso->id})" >
          </td>
        </tr>
    
      
     
        
    </tbody>    
</table>   
        
        
{HTML::script('js/curso.js')}


</div>

{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='estudiantes'}
