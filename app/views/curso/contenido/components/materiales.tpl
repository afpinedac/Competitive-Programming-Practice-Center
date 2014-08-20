               
                        <div class="row-fluid">
                            <div class="span12">
<h3 id="materiales" data-tipped='{url('curso/test')}'><i class='icon icon-book icon-2x'></i> Materiales</h3>
                            </div>
                        </div>                          

  <div class="row-fluid">
                                        <div class="span12">
                                            {assign var=materiales value=$modulo->get_materiales()}
         {if count($materiales)>0} 

                  <table class="table  table-condensed table-bordered table-striped">
                                                <thead>
                                                <th width='30%'>Nombre</th>
                                                <th>Descripción</th>
                                                <th width='25%'>Valoración</th>
                                                <th width='10%'>Opciones</th>
                                                </thead>
                                                <tbody>
                                                    {foreach $materiales as $material}
                                                    <tr>
                                                        <td><p class='text-left'>{$material->nombre}</p></td>
                                                        <td><p class='text-left'>{$material->descripcion}<p></td>
                                                        <td>
                                                            <span>
                                                                <img data-contenido='{$material->id}' data-avg='{$material->get_promedio_valoracion()}' class='star' src='{url('img/stars/')}/{$material->get_promedio_valoracion()}.gif'>
                                                                <span id='n-votos-{$material->id}'>{$material->numero_de_valoraciones()}</span>
                                                                <span>votos</span></span>
                                                                <p><a onClick='comentarios_contenido.ver({$material->id})' id='a-{$material->id}' href='#modal-comentarios-contenido' data-toggle='modal'>({$material->numero_de_comentarios()}) comentarios</a></p>           </td>
                                                        <td>
                                                            {if $material->enlace} <a href="{$material->enlace}" target="_blank"><i class='icon icon-option icon-link'></i></a>{/if} {if $material->archivo}<a href='{url('contenido/descargar')}/{LMSController::encoder($material->id)}' title="Descargar"><i class='icon icon-option icon-download'></i></a>{/if}
                                                        </td>
                                                    </tr>
                                                     {/foreach} 
                                                </tbody>    
          </table>   
                                    
          {else}
                                          <div class="alert alert-block">
                                              <p><center>Este módulo no tiene materiales del profesor</center></p>
                                          </div>    

                                                
          {/if}
                                                   </div>
                                    </div>
                                                   

                                                   {* Recursos extras *}
                                                   {assign var=recursos value=$modulo->get_recursos()}
                                                   <div class="row-fluid">
                                                       <div class="span12">
                                                           <div class="span6">
                                                               {if count($recursos)>0}
                                                                   <h5 style='cursor: pointer'><i class='icon icon-book'></i><a  onclick="curso.modulo.recursos_extras()"> Recursos extras ({count($recursos)})</a> </h5>
                                                               {/if}
                                                           </div>
                                                           <div class="span6">
                                                               <p class='pull-right'> <a href='#nuevo-recurso' data-toggle='modal'><i class='icon icon-cloud-upload'></i> Subir un recurso</a></p>

                                                           </div>
                                                       </div>
                                                   </div>
                                                      
                                                           
                                                           <!-- Recursos extras -->
                                                           <div class="row-fluid hide"  data-visible='0' id='div-recursos-extra'>
                                                               <div class="span12">
                                                                
                                                                            <table class="table table-striped table-condensed table-bordered">
                                                <thead>
                                                <th width='30%'>Nombre</th>
                                                <th>Descripción</th>
                                                <th>Usuario que lo subió</th>
                                                <th width='15%'>Opciones</th>
                                                </thead>
                                                <tbody>
                                                    {foreach $recursos as $recurso}
                                                    <tr>
                                                        
                                                        <td><p class='text-left'>{$recurso->nombre}</p></td>
                                                        <td><p class='text-left'>{$recurso->descripcion}<p></td>
                                                        <td><p class='text-left'>{$recurso->nombres|capitalize} {$recurso->apellidos|capitalize}<p></td>
                                                        <td> {if $recurso->enlace}<a href='{$recurso->enlace}' target="_blank"> <i class='icon icon-option icon-link'></i></a>{/if} {if $recurso->archivo} <a href='{url('recurso/descargar')}/{LMSController::encoder($recurso->id)}'><i class='icon icon-option icon-download'></i></a>{/if} {if Auth::user()->id == $recurso->usuario} <a href='{url('recurso/eliminar/')}/{$recurso->id}'><i class='icon icon-option icon-trash'></i></a>{/if}</td>
                                                    </tr>
                                                     {/foreach} 
                                                </tbody>    
                                                                        </table>   
                                                                   
                                                                   
                                                                   
                                                                   
                                                               </div>
                                                           </div>
                                                   
                                                   
                                                   
                                                   
                                                   
                                                   {*  MODAL  para subir un recurso *}
                                                   
                                               

 <div id="nuevo-recurso" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="alingCenter" id="tituloTipo"><i class='icon icon-upload'></i> Subir Material</h4>

    </div>
    <div class="modal-body">
        {Form::open(['action'=>'RecursoController@postSubir', 'files'=>true])}
           
            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Nombre: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span12' type="text" name="nombre" >
                </div>
            </div>

            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Descripción: *</label>
                </div>                
                <div class="span7">
                    <textarea name='descripcion' class='span12' required placeholder="Escriba la descripción"></textarea>
                </div>
            </div>
            
           <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">URL:</label>
                </div>                
                <div class="span7">
                    <input  name='enlace' type="url" name="nombre" class='span12' >
                </div>
            </div>
        
            <div class="row-fluid" > 
                <div class="span3">
                    <label class="pull-right">Archivo:</label>
                </div>                
                <div class="span7">
                    <input  type="file"  name="archivo">
                </div>
            </div>


            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-inverse"><i class='icon icon-upload-alt'></i> Subir</button>
            </div>
        {Form::close()}
    </div>

</div>

   {* MODAL PARA LOS COMENTARIOS DEL CONTENIDO DE LOS MATERIALES *}
   
   {include file='../../modales/comentar_contenido.tpl'} 
   
   
   {* MODAL PARA LOS COMENTARIOS DEL CONTENIDO DE LOS MATERIALES *}
    
    <script>



            $(document).ready(function() {
                //////ESTRELLAS///////////////////////              
                

                var avg = 0;
                $(".star").mousemove(function(e) {                
               // window.console.log('mouse move');
                    var x = e.pageX - $(this).offset().left;
                    if (x >= 0 && x <= 90) {
                        img = Math.floor(x / 8);
                        $(this).attr('src', '{url('./img/stars/_')}' + img + '.gif');
                    }
                });
                $(".star").mouseenter(function(e) {
                 //   window.console.log('entrando');
                    avg = $(this).data('avg');
                });

                $(".star").mouseleave(function() {
                  //  window.console.log('saliendo..');
                    // alert(avg);
                    $(this).attr('src', "{url('img/stars/')}/" + avg + ".gif");  //<---- Poner el promedio
                });

                $(".star").click(function(e) {
                  //  console.log('haciendo click');

                    var imagen = $(this);
                    var valoracion = Math.floor((e.pageX - $(this).offset().left) / 8);
                    if (valoracion == 0)
                        return;
                    var contenido = $(this).data('contenido');
                    $.ajax({
                        // dataType: "json",
                        type: 'POST',
                        url: "{url('contenido/valorar')}",
                        data: {
                            puntuacion: valoracion,
                            contenido: contenido
                        },
                        success: function(data) {
                
                           // window.console.log(data);
                            avg = (data.promedio);
                            imagen.data('avg', avg);
                            $("#n-votos-" + contenido).html(data.valoraciones);
                           // return;
                        }
                    });

                });














                //////COMENTARIOS///////////////

                comentarios_contenido = {
                    indice: 0,
                    id_usuarios: new Array(),
                    id_comentario: new Array(),
                    usuarios: new Array(),
                    comentarios: new Array(),
                    fecha: new Array(),
                    contenido: -1,
                    usuario: {Auth::user()->id},
                    show_next: function() {
                        if (this.indice == this.usuarios.length) { // no hay mas comentarios
                            this.set_no_mas_comentarios();
                        } else {
                            var cursor = this.indice;
                            for (i = cursor; i < Math.min(this.usuarios.length, cursor + 5); i++) {
                                this.indice++;
                               // window.alert(this.usuario);
                                if (this.id_usuarios[i] == this.usuario) {
                                    $("#lista-comentarios").append('<tr><td>' + this.usuarios[i] + '</td><td width=\'60%\' style =\'word-break: break-all\'>' + this.comentarios[i] + '</td><td width=\'20%\'>' + this.fecha[i] + '</td><td><button onclick=\'comentarios_contenido.eliminar(' + this.id_comentario[i] + ')\'  class=\'btn btn-mini btn-danger\'>x</button></td></tr>');
                                } else {
                                    $("#lista-comentarios").append('<tr><td>' + this.usuarios[i] + '</td><td width=\'60%\' style =\'word-break: break-all\'>' + this.comentarios[i] + '</td><td width=\'20%\'>' + this.fecha[i] + '</td><td></td></tr>');
                                }


                            }
                        }
                    },
                    reset: function() {
                        this.usuarios = new Array();
                        this.comentarios = new Array();
                        this.fecha = new Array();
                        this.id_usuarios = new Array();
                        this.id_comentario = new Array();
                        this.indice = 0;
                    },
                    set_mas_comentarios: function() {
                        $("#mostrar-mas").html("Mostrar mas<i class='icon-arrow-down'></i>");

                    },
                    set_no_mas_comentarios: function() {
                        $("#mostrar-mas").html("No hay mas comentarios");
                    },
                    set_n_comentarios: function(n) {
                        $("#n-comentarios").text('(' + n + ')');
                        $("#a-" + this.contenido).html("(" + n + ') comentarios');
                    },
                    eliminar: function(comentario) {
                        if (confirm('¿Está seguro de eliminar este comentario?')) {
                            $.ajax({
                                // dataType: "json",
                                type: 'POST',
                                url: "{url('contenido/eliminarcomentario')}",
                                data: {
                                    comentario: comentario
                                },
                                success: function(data) {
                                    if (data == '1') {
                                        comentarios_contenido.set_mas_comentarios();
                                        set_lista_comentarios($("#id-contenido").val());
                                    } else {
                                        alert('Se ha producido un error, por favor intentelo mas tarde');
                                    }
                                }
                            });
                        }

                    },
                    ver: function(contenido) {
                        this.contenido = contenido;
                        $("#id-contenido").val(contenido);  // ponemos el id del curso que queremos que se cargue
                        comentarios_contenido.set_mas_comentarios();
                        set_lista_comentarios(contenido);
                    }

                };


                //FUNCIONES PARA COMENTARIOS
                $("#btn-comentar").click(function() {
                    $("#div-comentario").show(300);
                });


//cuando se le da cancelar al boton de cancelar comentario
                $("#btn-cancelar-comentario").click(function(e) {
                    e.preventDefault();
                    $("#area-comentario").val('');
                    $("#div-comentario").hide(300);
                });


                //cuando se le da click en comentario
                $("#btn-crear-comentario").click(function(e) {
                    var id_contenido = $("#id-contenido").val();
                    var comentario = $("#area-comentario").val();
                    if(comentario == ""){
                        alert('El comentario no puede estar vacío');
                        return;
                    }
                    $.ajax({
                        //dataType: "json",
                        type: 'POST',
                        url: "{url('contenido/comentar')}",
                        data: {
                            comentario: comentario,
                            contenido: id_contenido
                        },
                        success: function(data) {
                            //  window.console.log(data);
                            if (data == 1) { //se creo correctamente el comentario
                                $("#area-comentario").val('');
                                set_lista_comentarios(id_contenido);
                            } else { //se generó algun error
                                alert('se ha generado algun error, por favor intente mas tarde.');
                                $("#area-comentario").val('');
                            }
                            comentarios_contenido.set_mas_comentarios();
                        }
                    });
                });


                //------------------------------

                //FUNCIONES PARA LA LISTA DE COMENTARIOS






                function set_lista_comentarios(contenido) {
                    var result;
                    $.ajax({
                        dataType: "json",
                        type: 'POST',
                        url: "{url('contenido/comentarios')}",
                        data: {
                            contenido : contenido
                        },
                        success: function(data) {
                       // window.console.log(data);
                       // return;
                        
                            comentarios_contenido.set_n_comentarios(data.total);
                            if (data.total === 0) {
                                $("#no-comentarios").show();
                                $("#si-comentarios").hide();
                            } else { // tiene comentarios
                                $("#lista-comentarios").html(''); //borramos los comentarios que tiene y volvemos los ponemos
                                $("#si-comentarios").show();
                                $("#no-comentarios").hide();
                                comentarios_contenido.reset();
                                $.each(data.comentario, function(idx, value) {
                                    //$("#lista-comentarios").append('<tr><td>' + value.usuario + '</td><td>' + value.comentario + '</td><td>' + value.fecha + '</td></tr>')
                                    //cargamos todos los valores en el vector de comentarios
                                    comentarios_contenido.usuarios.push(value.nombres + " " + value.apellidos);
                                    comentarios_contenido.fecha.push(value.fecha);
                                    comentarios_contenido.comentarios.push(value.comentario);
                                    comentarios_contenido.id_usuarios.push(value.usuario_id);
                                    comentarios_contenido.id_comentario.push(value.comentario_id);
                                });

                                comentarios_contenido.show_next();
                            }
                        }
                    });
                }
            });
</script>

    
    
    
    
    
    
    {HTML::script('js/curso.js')}