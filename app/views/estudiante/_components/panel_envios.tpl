<br>
<div ng-app='colaEnvios' ng-controller='envioController' >

  
  <p class="text-center"><strong>Env. ultimos 10 minutos</strong></p>
  <div class="table-responsive">
    <table class="table table-condensed table-hover">
      <thead>
        <tr>
          <td>ID</td>
          <td>Usuario</td>
          <td>Ejercicio</td>
          <td>Respuesta</td>
        </tr>
      </thead>
      <tbody ng-init="get_envios()">
        <tr ng-repeat="envio in envios" class="{literal} {{envio.color}}{/literal}">
          
          <td>{literal}{{envio.id}}{/literal}</td>
          <td>{literal}{{envio.estudiante}}{/literal}</td>
          <td>{literal}{{envio.ejercicio}}{/literal}</td>
          <td >{literal}{{envio.respuesta}}{/literal}</td>
          
        </tr>
      </tbody>
    </table>
  </div>

  
  
</div>