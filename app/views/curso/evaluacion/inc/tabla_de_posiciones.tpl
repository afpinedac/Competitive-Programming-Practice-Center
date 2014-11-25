
<div class="table-responsive" ng-Controller='EvaluacionController' ng-init='EvaluacionController({$evaluacion})'>
  <table class="table table-condensed table-hover table-bordered">
    <thead>
      
        <th>
            <td ng-repeat='ejercicio in [1,2,3]'> Ejercicio [[ejercicio]]</td>
            
            <td><strong>Resueltos</strong></td>
            <td><strong>Puntos</strong></td>
        </th>
     </thead>
     <tbody>
        
        <tr ng-repeat='estudiante in [10,20,30]'>
            <td>[[estudiante]]</td>
            <td ng-repeat='ejercicio in [1,2,3]'> [[ejercicio]] - [[estudiante]]</td>
            <td > 0 / n</td>
            <td> 0 </td>
        </tr>
     </tbody>
  </table>
</div>

  

 


