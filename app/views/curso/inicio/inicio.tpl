{capture assign='content'}

  <div class="row-fluid" ng-controller="InicioController" ng-init='InicioController({$curso->id}); usuario_logueado={Auth::user()->id}'>
    <div class="span12">

      <div class="span12">
        {Form::open(['action' => 'CursoController@postPublicar'])}
        <div class="row-fluid">
          <div class="span12">
            <textarea placeholder="¿Que piensas?" rows='3' name='publicacion'  class='span12' required></textarea>
          </div>
          <input type="hidden" name="curso" value='{Crypt::encrypt($curso->id)}'>
          <input type='submit' class='btn btn-success pull-right' value='Publicar'>
        </div>

        {Form::close()}

        
        <hr>
        <div class="row-fluid" style=''>
          <div class="span12">
            
            
            <center><i class="icon icon-repeat icon-3x" ng-show="loading_init"></i></center>

            <div class="" ng-repeat="notificacion in notificaciones  | limitTo : limit_notificaciones | filter: search_notificacion">
              <a id="p[[notificacion.id]]"></a>
              <a id="c[[notificacion.id]]"></a>
              
            


              <!-----ANGULAR----------->
              <div id='publicacion-[[notificacion.id]]' style='margin-top: -10px;'>

                <div class="row-fluid" >
                  <div class="span12">
                    <div class="span1">
                      
                      <img ng-src="{url('/avatares/userimages')}/[[notificacion.propietario]].png" class='mini_foto ver-perfil' {*onclick="usuario.ver_perfil({notificacion::find(notificacion.id)->usuario})"*}>
                    </div>
                    <div class="span11 div-wrap" >
                      <p><strong>[[notificacion.nombres]] [[notificacion.apellidos]]</strong></p>

                      <!--publicacion normal-->
                      <div ng-if='notificacion.tipo==0' class=''>
                       <div ng-if='usuario_logueado==notificacion.propietario' class="pull-right" style="margin-top: -25px;"><a href="" ng-click="eliminar_notificacion(notificacion.id)">x</a></div>
                       <pre class='guiones'> [[notificacion.publicacion]]</pre>
                      </div>
                      <!---LOGRO---->
                      <div ng-if='notificacion.tipo!=0' class='well' >
                     {*   {assign var=logrox value=logro::get_info_logro($notificacion->codigo)}*}
                        <h4>He conseguido el logro: <span style='font-size: 30px;'>[[notificacion.logro.nombre]]</span></h4>
                        <img style='margin-left: 15px' ng-src='{url('img/logros/')}/[[notificacion.logro.imagen]].png' class='img-logro-notificacion'>
                        <br>
                      </div>
                      <div class="row-fluid" style="margin-top: -10px;">
                        <!--la publicacion es mia-->
                        <div class="span12" ng-if='usuario_logueado==notificacion.propietario'>
                          <a href=''  onclick="return false;" ng-click='notificacion.tiene_comentarios=true'> <i class="icon icon-comment-alt"></i> Comentar</a>
                          &nbsp;&nbsp;
                          <!--COMPARTIR EN REDES SOCIALES-->   
                          <!--twitter-->
                          <!--si esta twitteada-->
                               <span ng-if='notificacion.compartida_twitter==1'>
                                  <a href="#" onclick="return false;">
                                    <span class=''><i class='icon icon-twitter-sign'></i> Ha sido twitteado <i class='icon icon-ok'></i></span>
                                  </a>
                                </span>
                          <!--si no está twitteada-->
                                <span ng-if='notificacion.compartida_twitter==0'>
                                  <a ng-href="{url('notificacion/login-twitter')}/[[notificacion.id]]">
                                    <span class=''><i class='icon icon-twitter-sign'></i> Twittear</span>
                                  </a>
                                </span>
                                <!--FIN REDES SOCIALES-->
                        </div>
                        <!--la publicacion es de otro-->
                        <div class="span12" ng-if='usuario_logueado!=notificacion.propietario'>
                          
                          <a id="text-me-gusta-[[notificacion.id]]" href=''  ng-click="me_gusta([[notificacion.id]])">[[notificacion.me_gusta]]</a>  &nbsp;&nbsp;
                         <!-- <a href='' ng-show='!me_gusta[notificacion.id]' ng-click="me_gusta([[notificacion.id]])">Ya no me gusta</a>  &nbsp;&nbsp;-->
                          <a href=''  ng-click='notificacion.tiene_comentarios=true'>  <i class="icon icon-comment-alt"></i> Comentar</a>
                        </div>
                      </div>    

                      <div  style="margin-top: -40px; padding: 0px;" class="row-fluid">
                        <i class='icon icon-thumbs-up-alt' style="margin-top: -10px;" ></i>
                        <small> A <span><a href='#likes'  id="me-gusta-[[notificacion.id]]" ng-bind='notificacion.n_likes'></a></span> personas les gusta esto</p></small>
                      </div>

                      <!--comentarios-->
                      <div>
                        <div  ng-show='notificacion.tiene_comentarios' class='container-fluid'> 
                          <div class='span6'>
                            <div  ng-repeat='comment in notificacion.comentarios' >
                              <div id="comment-[[notificacion.id]]-[[comment.id]]" class="bubble span12" style="margin-left: 20px; padding: 0px;">
                                <img ng-src="{url('/avatares/userimages')}/[[comment.comentadorid]].png"  class="img-avatar-comentario pull-left" style="margin-top: 5px; margin-left: 3px;margin-right: 3px;">
                                <p><strong><small>[[comment.nombres]] [[comment.apellidos]]:</small> </strong><small>[[comment.publicacion]]</small></p>
                                <span ng-if='comment.comentadorid==usuario_logueado' class='pull-right' style='font-size: 8px; margin-top: -20px; margin-right: 3px;'><small><i class='icon icon-remove' ng-click='eliminar_comentario([[comment.id]],[[notificacion.id]])' ></i></small></span>
                              </div>  
                            </div>
                            <div  style="margin-left: 20px; padding: 3px;" class='row-fluid' >
                              <textarea ng-model='comentario[notificacion.id]' class="span11" rows="1"  placeholder="Tu comentario..." name='comentario' required=""></textarea>
                              <button class="btn-mini btn-success" ng-click="comentar([[notificacion.id]])" style="margin-top: -10px; margin-left: 5px;"><i class='icon icon-edit' title='Comentar'></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!--fin comentarios-->
                    </div>
                  </div>
                </div>
              </div>
              <hr>
            </div>
                                <div class="container-fluid" ng-hide='notificaciones.length==limit_notificaciones'>
    <div class="span4 offset4">
     <center><button class='btn-mini btn-info span12' ng-click='mas_notificaciones()'> Mas notificaciones<i ng-init='loading2=false' ng-show='loading2' class='icon icon-repeat icon-spin'></i></button></center>
    </div>
                                  
                                  
                                  
</div>    
                               
            <!--    <button ng-click='menos_notificaciones()'> Menos notificaciones</button>-->
            <!-----END ANGULAR----->


            
                  </div>
                </div>





              </div>


            </div>
          </div>


          {* ver perfil del usuario *}


          {include file='../modales/perfil_usuario.tpl'}
          {include file='../modales/likes.tpl'}



          {*Mostrar si hay un logro*}

          {if $logro!=null}                
            {*{$logro|var_dump}*}

            {include file='../modales/logro_obtenido.tpl'} 

          {/if}

          {*Fin de mostrar si hay un logro*}




          <style>
            .div-wrap{
              word-wrap: break-word;
            }

          </style>




          <script>

           var publicacion = {
              me_gusta: function(id) {

                $.ajax({
                  type: 'post',
                  url: "{url('notificacion/me-gusta')}",
                  data: {
                    notificacion: id,
                    usuario: {Auth::user()->id}

                  },
                  success: function(data) {

                    data = data.split('-');
                    //window.console.log(data);
                    if (data[0] == 1) {

                      $("#me-gusta-" + id).text('Ya no me gusta');
                    } else {
                      $("#me-gusta-" + id).text('Me gusta');
                    }

                    $("#contador-me-gusta-" + id).text(data[1]);

                  }
                });
                return false;
              }


              ,
              //eliminar una publicacion
              eliminar: function(notificacion) {


                alertify.confirm('¿Esta seguro de eliminar esta publicación?', function(e) {
                  if (e) {
                    $.ajax({
                      dataType: "json",
                      type: 'post',
                      url: "{url('notificacion/eliminar')}",
                      data: {
                        notificacion: notificacion,
                        curso: {$curso->id}
                      },
                      success: function(data) {
                        if (data == 1) {
                          $("#publicacion-" + notificacion).empty();
                          alertify.log("LA PUBLICACIÓN HA SIDO ELIMINADA", "success", 2000);
                        } else {
                          alertify.log("Ha ocurrido un problema", "", 2000);
                        }
                      }
                    });
                  }
                });


              },
            
              comentar: function(publicacion) {

                comentario = $("#comentario-" + publicacion + " textarea").val();

                if (comentario != "") {

                  //se va a agregar el comentario en la bd;
                  $.ajax({
                    dataType: "json",
                    type: 'post',
                    url: "{url('notificacion/comentar')}",
                    data: {
                      notificacion: publicacion,
                      comentario: comentario
                    },
                    success: function(data) {
                      if (data == '0') {
                        alertify.log('Se generó un problema, intentelo más tarde', "error", 4000);

                      } else {
                        //  window.console.log(data);
                        $("#comentario-" + publicacion).before("<div class='bubble span12' style=\"margin-left: 20px; padding: 0px;\" id='div-comentario-" + data.id + "'><img src='" + data.foto + "' class=\"img-avatar-comentario pull-left\" style=\"margin-top: 5px; margin-left: 3px;margin-right: 3px;\"> <p><strong><small>" + data.nombres + ":</small> </strong><small>" + data.comentario + "</small></p><span class='pull-right' style='font-size: 8px; margin-top: -20px; margin-right: 3px;'><small><i class='icon icon-remove' onclick=\"publicacion.eliminar_comentario(" + data.id + ")\"></i></small></span></div>")
                        alertify.log('HAS COMENTADO ESTO', "success", 4000);
                        $("#comentario-" + publicacion + " textarea").val("");
                      }
                    }
                  });

                } else {
                  $("#comentario-" + publicacion).focus();
                }

              },
              eliminar_comentario: function(publicacion) {
                $.ajax({
                  dataType: "json",
                  type: 'post',
                  url: "{url('notificacion/eliminar-comentario')}",
                  data: {
                    comentario: publicacion
                  },
                  success: function(data) {
                    if (data == "1") {
                      $("#div-comentario-" + publicacion).remove();
                      alertify.log("COMENTARIO ELIMINADO", "success", 4000);
                    } else {
                      alertify.log('Se generó un problema, intentelo más tarde', "error", 4000);
                      // alert();
                    }
                  }
                });




              },
              likes: function(notificacion) {
                //   window.alert('pasa');
                $.ajax({
                  dataType: "json",
                  type: 'post',
                  url: "{url('notificacion/likes')}",
                  data: {
                    notificacion: notificacion
                  },
                  success: function(data) {
                    if (data == '0') {
                      alertify.log('Se generó un problema, intentelo más tarde', "error", 4000);
                    } else {
                      $("#lista-les-gusta").empty();
                      $.each(data, function(idx, value) {
                        //   window.console.log(value);

                        row = "<tr><td><img style='cursor:pointer; width:70px; heigth:70px;' onclick='usuario.ver_perfil(" + value.id + ")' src='" + '{url('avatares/userimages/')}' + "/" + value.id + ".png'></td><td><h4 onclick='usuario.ver_perfil(" + value.id + ")'>" + value.nombres + " " + value.apellidos + "</h4></td></tr>";
                        $("#lista-les-gusta").append(row);
                      });
                    }
                  }
                });
              }
            }

          </script>




          {/capture}   


            {include file='_templates/template.tpl' layout='curso' tab='inicio'}
