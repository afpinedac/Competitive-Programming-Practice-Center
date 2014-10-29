<br>
<div ng-app='colaEnvios' ng-controller='envioController'>
  <div ng-show="envios.length>0">

    <p class="text-center"><strong>Últimos envios</strong></p>
    <div class=" well">
      <table class="table table-condensed table-hover">
        <thead>
          <tr>
            <td><small>Envio</small></td>
            <td><small>Respuesta</small></td>
          </tr>
        </thead>
        <tbody ng-init="get_envios()">
          <tr ng-repeat="envio in envios" ng-class="getClass(envio)">
            <td>{literal}{{envio.id}}{/literal}</td>
            <!--<td>{literal}{{envio.estudiante}}{/literal}</td>
            <td><small>{literal}{{envio.ejercicio}}</small>{/literal}</td>-->
            <td ><small>{literal}{{envio.respuesta}}{/literal}</small></td>
          </tr>
        </tbody>
      </table>
    </div>


  </div>


</div>