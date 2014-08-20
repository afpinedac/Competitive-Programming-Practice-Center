     
                        <div class="row-fluid">
                            <div class="span12">
<h4 id="evaluacion"><i class='icon icon-bookmark'></i> Envíos ({count($envios)})</h4>
                            </div>
                        </div>
                        <div class="row-fluid" style='max-height: 200px; overflow-y: scroll'>
                            <div class="span12">
                                {if count($envios)>0}
                                
                                <table class="table table-striped table-condensed table-bordered">
                                    <thead>
                                    <th># Envío</th>
                                    <th>Ejercicio</th>
                                    <th>Fecha de envío</th>
                                    <th>Resultado</th>                                    
                                    <th>Tiempo de ejecución</th>                                    
                                    </thead>
                                    <tbody>   
                                       {foreach $envios  as $envio} 
                                        <tr class='{if $envio->resultado == 'accepted'}success{/if}'>
                                            <td >{$envio->envio_id}</td>
                                            <td >{$envio->nombre}</td>
                                            <td >{$envio->created_at}</td>
                                            <td>{$veredicto[$envio->resultado]}</td>
                                            <td>{$envio->tiempo_de_ejecucion|default:'-'}</td>
                                        </tr>                                      
                                        {/foreach}
                                    </tbody>    
                                </table>  
                                   
                                {else}
                                    <div class="alert alert-block">
                                        <center>
                                            <p>No se ha realizado ningún envío para este ejercicio</p>
                                        </center>
                                    </div>    

                                    
                              {/if}      
                            </div>
                        </div>    