{capture assign='content'}


  <div class="row-fluid">
    <div class="span12">
      <div class="row-fluid">
        <div class="span12">
          <center><h3>COMPETENCIA - {evaluacion::find($evaluacion)->nombre}</h3></center>
          <p  class='pull-right' style="color:#000000;text-align:center;"><i class='icon icon-time'></i> <em><span id='counter'></span></em></p>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span12">
          <h3><i class='icon icon-cogs'></i> Ejercicios</h3>
          {foreach $ejercicios as $ejercicio}
            <div class="row-fluid">
              <div class="span12">
                <h4><i class='icon icon-file-text-alt'></i> <a href='{url('curso/ver/')}/{$curso->id}/evaluacion/{$evaluacion}/{$ejercicio->id}'>{$ejercicio->nombre}</a>  {if ejercicio::find($ejercicio->id)->esta_resuelto(Auth::user()->id,1,$evaluacion)} <i class='icon icon-ok'></i>{/if} </h4>
              </div>
            </div>

          {foreachelse}
            <div class="alert alert-block">
              <center><p>Esta evaluación no tiene ejercicios</p>
              </center>
            </div>    
          {/foreach}
        </div>
      </div>
      <div class="row-fluid">
        <div class="span12">
          {include file='./inc/cuenta_regresiva.tpl'}
        </div>
      </div>
        {if Input::has('tp')}
      <div class="row-fluid">
        <div class="span12">
          {include file='./inc/tabla_de_posiciones.tpl'}
        </div>
      </div>
        {/if}
        
    </div>
  </div>
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='evaluacion'}
