


                    <div id="xdxd" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h2 class="alingCenter"> <i class='icon icon-trophy'></i> Logro Obtenido</h2>

                        </div>
                        <div class="modal-body">
                            
                            <center><h3>{$logro->nombre}</h3></center>

                           <center> <img class='img-logro-obtenido' src='{url('img/logros/')}/{$logro->codigo}.png'></center>
                           <h4 class='text-center'>{$logro->descripcion}</h4>



                            <div class="modal-footer">
                                <center><a href='' class='btn-success btn'>Aceptar</a> </center>                               
                                
                                
                            </div>
                           
                        </div>

                    </div>                        
                        <script>
                            $(document).ready(function(){
                              $("#xdxd").modal('toggle');
                            });
        
      
                  alertify.log("HAS DESBLOQUEADO UN NUEVO LOGRO", "success",4000);        

                          
                        </script>