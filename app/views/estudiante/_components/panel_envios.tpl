<br>
<div >
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
            <td>[[envio.id]]</td>
            <td ><small>[[envio.respuesta]]</small></td>
          </tr>
        </tbody>
      </table>
    </div>


  </div>


</div>