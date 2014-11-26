{capture assign="content"}
  <br>

  <div class="container-fluid">
    <div class="row">
      <div class="span10 offset1">
        <div ng-controller="RankingEvaluacion" ng-init="RankingEvaluacion({$evaluacion->id})">
          <center><h3>{$evaluacion->nombre}</h3></center>


          <div class="table-responsive">
            <table class="table table-condensed table-hover table-bordered">
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
                  <td>[[$index+1]]</td>
                  <td width="20%"><span class="pull-left">[[estudiante.nombre_completo]]</span></td>  
                  <td ng-repeat='puntos in estudiante.puntos'>[[puntos]]</td>
                  <td>[[estudiante.puntos_totales]]</td>
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