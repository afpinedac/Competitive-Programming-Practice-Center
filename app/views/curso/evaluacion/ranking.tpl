{capture assign="content"}
  <br>

  <div class="container-fluid">
    <div class="row">
      <div class="span10 offset1">
        <div ng-controller="RankingEvaluacion" ng-init="RankingEvaluacion({$evaluacion->id})">
          <center><h3>{$evaluacion->nombre}</h3></center>


          <div class="table-responsive">
            <table class="table table-condensed table-hover table-bordered table-striped">
              <thead>
                <tr>
                  <td>Posición</td>
                  <td>Nombre</td>
                  <td ng-repeat="ejercicio in ranking.ejercicios">[[ejercicio]]</td>
                  <td><a href='' >Puntos totales</td>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="estudiante in ranking.points | orderBy:'puntos_totales':true">
                  <td><strong>[[$index+1]]</strong></td>
                  <td width="20%"><span class="pull-left"><strong >[[estudiante.nombre_completo]]</strong></span></td>  
                  <td ng-repeat='puntos in estudiante.puntos track by $index'><strong style="font-size: 20px;">[[puntos]]</strong></td>
                  <td style="background-color: #ddf"><strong style="font-size: 25px;">[[estudiante.puntos_totales]]</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>


    </div>
  </div>    


{/capture}

{include file='_templates/template.tpl' layout='' title=''}