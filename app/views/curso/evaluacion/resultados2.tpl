{capture assign='content'}

  {*Mostrar si hay un logro*}

  {if $logro!=null} 


    {include file='../modales/logro_obtenido.tpl'} 

  {/if}

  {*Fin de mostrar si hay un logro*}


  <div class="row-fluid">
    <div class="span12">

      <div class="row-fluid">
        <div class="span12">
          <center><h3>Resultados <em>'{$evaluacion->nombre|upper}'</em></h3></center>  
          <p><strong><i class='icon icon-star-empty'></i> Porcentaje de aprobación : </strong>{$evaluacion->porcentaje_aprobacion} %</p>
          {assign var=resueltos value=usuario::find(Auth::user()->id)->get_numero_ejercicios_resultos_en_evaluacion($evaluacion->id)}
          {assign var=totales value=$evaluacion->get_numero_ejercicios()}
          <p><strong><i class='icon icon-star-empty'></i> Ejercicios resueltos : </strong>{$resueltos}/{$totales} ({if $totales == 0}-{else}{($resueltos/$totales)*100}{/if}%)</p>
        </div>
      </div>
      <br>

      <div class="row-fluid">
        <div class="span12">


          <div class="span7">

            <h4><i class='icon icon-star-empty'></i> Envíos</h4>
            {* ------- prepare data -------*}
            {assign var=title value='Envios en toda la evaluación'}
            {assign var=width value=600}
            {assign var=height value=300}
            {assign var=chart_div value='chart_div'}
            {assign var=veredict value=['Error de compilación' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'compilation error'), 'Aceptado' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'accepted') , 'Respuesta incorrecta' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'wrong answer'),'Tiempo límite' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'time limit'),'Error de ejecución' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'runtime error')]}
            {include file='../../graficas/grafica.tpl'}


            <center><div id="chart_div"></div></center>

            {* ------- end prepare data -------*}

            {assign var=envios_evaluacion value=usuario::find(Auth::user()->id)->get_envios_en_evaluacion($evaluacion->id)}     
            {if count($envios_evaluacion)>0}

              <table class="table table-striped table-bordered table-condensed">
                <thead>
                <th>Ejercicio</th>
                <th>Fecha de envío</th>
                <th>Resultado</th>
                <th>Tiempo de ejecución</th>
                </thead>
                <tbody>

                  {foreach $envios_evaluacion as $envio}



                    <tr class='{if $envio->resultado == 'accepted'}success{/if}'>
                      <td>{$envio->nombre}</td>
                      <td>{$envio->created_at}</td>
                      <td>{$envio->resultado}</td>
                      <td>{$envio->tiempo_de_ejecucion|default:'-'}</td>
                    </tr>

                  {/foreach}

                </tbody>    
              </table>    
            {else}
              <div class="alert alert-block">
                <center><p>No se realizó ningún envío</p>
                </center>

              </div>    

            {/if}                


          </div>
          <div class="span5">
            <h4><i class='icon icon-star-empty'></i> Logros Obtenidos</h4>
            {foreach $logros as $logro}
              <div class='span4'>
                <center><img class='img-logro' src='{url('img/logros/')}/{$logro->codigo}.png' title="{$logro->descripcion}"></center>
                <center><span class='text-success'><strong>{$logro->nombre}</strong></span></center>
              </div>

            {foreachelse}
              <div class="alert alert-block">
                <center>
                  <p>No obtuvo ningún logro en esta evaluación</p>
                </center>
              </div>    

            {/foreach}
          </div>                    

        </div>
      </div>




    </div>
  </div>















{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='resultado'}
