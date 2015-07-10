{capture assign='content'}

  {*Mostrar si hay un logro*}

  {if $logro!=null}
    {include file='../modales/logro_obtenido.tpl'} 
  {/if}
  <div class="row-fluid">
    <div class="span12">
      <div class="span4">

        {include file='./components/modulos.tpl'}

      </div>

      <div class="span8">   

        {if $modulos }
          {assign var=desbloqueado value=$modulo->esta_desbloqueado()} 

          <div class="row-fluid">
            <div class="span12">
              <h3>{if $desbloqueado} <i class='icon icon-unlock'></i> {/if} {$modulo->nombre|upper}</h3>
            </div>
          </div>               
          <hr>
          {if $desbloqueado or $curso->profesor_id == Auth::user()->id}

            <div class="row-fluid" style="margin-top: -20px;">
              <div class="span12" style='background-color: #FFF'>
                {include file='./components/materiales.tpl'}
              </div>
            </div>
            <hr>

            <div class="row-fluid">
              <div class="span12" style='background-color: #FFF'>

                {include file='./components/ejercicios.tpl'}                        

              </div>
            </div>
            <hr>

            <div class="row-fluid">
              <div class="span12" style='background-color: #FFF'>                    
                {include file='./components/evaluacion.tpl'}                  

              </div>
            </div>

          {else}  
            {if modulo::find($modulo->id)->tiene_ejercicios()}
              <h3 class='text-muted'><i class='icon icon-lock icon-2x'></i> Este módulo esta bloqueado</h3>
              <p class=''><em>Desbloquea primero los modulos:</em></p>
              <ul>
                {foreach $modulo->get_pre_requisitos() as $pre}
                  <li><h4><a href='{url('curso/modulo')}/{$pre->id}'>{$pre->nombre}</a></h4></li>
                      {/foreach}
              </ul>
            {else}
              <h3 class='text-muted'><i class='icon icon-lock icon-2x'></i> Este módulo no tiene ejercicios</h3>
            {/if}
          {/if}
          <hr>
        {else}
          <div class="row-fluid">
            <div class="span12">
              <div class="alert alert-block">
                <center><p>Este curso aún no tiene módulos</p></center>
              </div>    

            </div>
          </div>
        {/if}
      </div>
    </div>
  </div>


{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='contenido'}
