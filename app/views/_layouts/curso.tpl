

<div class="row-fluid" ng-app='EnvioController'>


  <div class="span12">



    <div class="row-fluid">
      <div class="span12">
        {include file='../curso/_components/header_curso.tpl'}
      </div>
    </div>






    <div class="row-fluid" id='test'>
      <div class="span12">


        <div class="span10 offset1 contenedor" style='padding-left: 10px; padding-right: 10px;'   >




          <div class="row-fluid">
            <div class="span12">



              <div class="span10">


                <ul class="nav nav-tabs">
                  <li  class='{if $tab=="inicio"}{'active'}{/if}'>
                    <a href="{URL::to('curso/ver')}/{$curso->id}/{'inicio'}"><i class='icon icon-home'></i> Publicaciones</a>
                  </li>
                  <li  class='{if $tab=="contenido"}{'active'}{/if}'><a href="{URL::to('curso/ver')}/{$curso->id}/{'contenido'}"><i class='icon icon-book'></i> Contenido</a></li>
                  <li  class='{if $tab=="perfil"}{'active'}{/if}'><a href="{URL::to('curso/ver')}/{$curso->id}/{'perfil'}"><i class='icon icon-user'></i> Mi perfil</a></li>
                    {*<li  class='{if $tab=="trofeos"}{'active'}{/if}'><a href="{URL::to('curso/entrar')}/{$curso}/{'trofeos'}"><i class='icon icon-trophy'></i> Ranking</a></li> *}
                    {if $tab == "ejercicio"} 
                      {if $evaluacion == -1}
                      <li  class='active'><a ><i class='icon icon-pencil'></i> Ejercicio de Taller</a></li>
                      {else}
                      <li  class='active'><a ><i class='icon icon-pencil'></i> Ejercicio Evaluativo</a></li>
                      {/if}


                  {/if}
                  {if $tab == "mensajes"} 
                    <li  class='active'><a ><i class='icon icon-envelope'></i> Mensajes</a></li>
                    {/if}
                    {if $tab == "evaluacion"} 
                    <li  class='active'><a ><i class='icon icon-file'></i> Evaluación</a></li>
                    {/if}

                  {if $tab == "resultado"} 
                    <li  class='active'><a ><i class='icon icon-file'></i> Resultado de la evaluación</a></li>
                    {/if}
                    {if $tab == "foro"} 
                    <li  class='active'><a ><i class='icon icon-comments'></i> Foro</a></li>
                    {/if}
                    {if $tab == "envios"} 
                    <li  class='active'><a ><i class='icon icon-refresh'></i> Mis Envíos</a></li>
                    {/if}



                  {*NOTIFICACIONES*}


                  {assign var=alertas value=usuario::find(Auth::user()->id)->get_alertas($curso->id)}
                  {assign var=nalertas value=usuario::find(Auth::user()->id)->get_alertas($curso->id,'c')}

                  {if $curso->profesor_id == Auth::user()->id }

                    <li  class='{if $tab=="monitorear"}active{/if} pull-right'>
                      <a href="{url('curso/monitorear/')}/{$curso->id}"> <i class='icon icon-info-sign'></i> Monitoreo</a>
                    </li>                     
                    <li  class='{if $tab=="edicion"}active{/if} pull-right'>
                      <a href="{url('curso/ver/')}/{$curso->id}/editar"> <i class='icon icon-edit-sign'></i> Edición</a>
                    </li>
                  {/if}



                  {* <li> <a href="curso.php"><i class="icon icon-share-alt"></i> Back</a><li> *}
                  <li class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle"  data-toggle="dropdown"><i class='icon-th'></i> Notificaciones {if $nalertas>0}<span id='nalertas' class="badge badge-important">{$nalertas}</span>{/if}
                      <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">

                      {foreach $alertas as $alerta}
                        <li class="success"><a href="{$alerta->enlace}" onclick="alerta.ver({$alerta->id})"><small>{if $alerta->visto == 0}<i id='icon-alert-{$alerta->id}' class='icon icon-star'></i> {/if}{$alerta->mensaje|truncate:80}</small></a></li>
                              {/foreach}

                    </ul>    
                  </li>   
                </ul>
                {if isset($content)}                                        
                  {$content}
                {/if}
              </div>

              <div class="span2">
                {include file='../estudiante/_components/sidebar_estudiante.tpl'}
                <judgeonline></judgeonline>
              </div>


            </div>
          </div>

        </div>
      </div>
    </div>



  </div>


</div>


<script>
  curso_actual = {$curso->id};
  alerta = {
    ver: function(alerta) {
      $.ajax({
        dataType: "json",
        type: 'post',
        url: "{url('notificacion/ver-alerta')}",
        data: {
          alerta: alerta
        },
        success: function(data) {
          $("#icon-alert-" + data.alerta).remove();
          if (data.nalertas > 0) {
            $("#nalertas").text(data.nalertas);
          } else {
            $("#nalertas").text("");
          }
          return true;
        }
      });
    }

  }



</script>