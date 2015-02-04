



<div id="perfil-usuario" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 class="alingCenter"> <i class='icon icon-user'></i> Perfil de usuario</h3>

                        </div>
                        <div class="modal-body">
                                <div class="row-fluid">
                                    <div class="span12">
                                            
                                        <div class="row-fluid">
                                            <div class="span12">
                                                <div class="span4">
                                                    <img src='' id='img-perfil' class='img-perfil'>
                                                </div>
                                                <div class="span8">
                                                    <p><strong>Nombre:</strong> <span id='perfil-nombre'></span></p>
                                                    <p><strong>E-mail:</strong> <span id='perfil-correo'></span></p>
                                                    <p><strong>Puntos:</strong> <span id='perfil-puntos'></span></p>                                                   
                                                    <p><strong>Posición:</strong> <span id='perfil-posicion'></span></p>                                                   
                                                    <p><strong>Tiempo logueado:</strong> <span id='perfil-tiempo-logueado'></span></p>                                                   

                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="row-fluid">
                                            <div class="span12" >
                                                <h3>Logros Obtenidos</h3>  
                                            </div>
                                        </div>
                                        
                                        <div class="row-fluid">
                                            <div class="span12" id='logros-perfil'>
                                                
                                            </div>
                                        </div>
                                        <br>
                                          <div class="modal-footer">
                                
                                 <center><button class="btn btn-success" data-dismiss="modal">Aceptar</button></center>
                                
                                
                            </div>
                                        
                                        
                                        
                                    </div>
                                </div>
                            
                           
                        </div>

                    </div>  






                        <script>
                           
                           
     usuario = {
         ver_perfil : function(usuario){
         
         
       $.ajax({
            dataType: "json",
            type: 'get',
            url: "{url('usuario/informacion')}",
            data: {
            
               usuario_id : usuario,
       curso : {$curso->id|default:0}
            },
            success: function(data) {
                  //  window.console.log(data);
                    //cargando informacion                    
                    foto = '{url('img/avatars/')}/' + data.info.foto;
                  //  window.console.log(foto);                    
                    $("#img-perfil").attr('src','{url('avatares/userimages/')}/' + data.info.id + '.png');
                    $("#perfil-nombre").text(data.info.nombres + " " + data.info.apellidos);
                    $("#perfil-correo").text(data.info.email);
                    $("#perfil-puntos").text(data.info.puntos);
                    $("#perfil-posicion").text(data.info.posicion);
                    $("#perfil-tiempo-logueado").text(data.info.tiempo_logueado);
                    
                    
                    $("#logros-perfil").empty();
                    //ponemos los logros
                    
                    
                    $.each(data.logros,function(idx,value){                          
                       //   window.console.log(value.codigo);
                          //window.console.log('pasa1');
                          img = "<img class='logro-perfil' title='"+ value.nombre +"' src='" + '{url('img/logros/')}' + "/" + value.codigo + ".png'>";
                          //window.console.log('pasa2');
                        //  window.console.log(img);
                          $("#logros-perfil").append(img);
                     });
            }
        }); 
                                
                                             
                                         $("#perfil-usuario").modal('toggle');  
                                     
                                }
                   
                           };

                          
                        </script>