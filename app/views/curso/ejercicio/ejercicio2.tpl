{capture assign='content'}

    
          <script>
                   tester = {
                        toogle : function(){
                            $("#testing").toggle(1000);
                        }
                    };
            
              </script> 
    
    {assign var=title value=$ejercicio->nombre} 
    
    <div class="row-fluid">
        <div class="span12">
                
            <div class="span10 offset1">

                <div class="row-fluid">
                    <div class="span12">

                        {if $evaluacion!=-1} {*Miramos si el ejercicio es de una evaluacion*}
                        <a href='{url('curso/ver/')}/{$curso->id}/evaluacion/{$evaluacion}' class='btn btn-info pull-right'><i class='icon icon-chevron-sign-left'></i> Volver a la Evaluación</a>         
                                                 
                            {else} {*si es el ejercicio de un taller|       *}
                                 <a href='{url('curso/ver/')}/{$curso->id}/contenido#ejercicios' class='btn btn-info pull-right'><i class='icon icon-chevron-sign-left'></i> Volver al Taller</a>         
                            {/if}
                        
                                <center><h2>{$ejercicio->nombre}</h2></center>
                                {if $ejercicio->tipo_entrada==1}
                                    <h4><i class='icon icon-star'></i> Tiempo límite: <em>{$ejercicio->time_limit|string_format:"%.1f"} seg</em></h4>
                                {/if}
              {if $evaluacion!=-1}     {*Cuenta regresiva*}             
             <p  class='pull-right' style="color:#000000;text-align:center; margin-top: -20px;"><i class='icon icon-time'></i> <em><span id='counter'></span></em></p>
                        {include file='../evaluacion/inc/cuenta_regresiva.tpl'}
             {/if}
             
            {if $ejercicio->tipo_formulacion == 0}
                 <br>
                 <br>
                <p style='text-align: justify; font-size: 16px;'><em>{$ejercicio->formulacion}</em></p>
                
                
                {if $ejercicio->tipo_entrada ==0}
                <p><strong>Descargar</strong> .in: <a target='_blank' href='{url('ejercicio/descargar-in/')}/{LMSController::encoder($ejercicio->id)}'><img src='{url('img/general/txt.jpg')}'></a></p>
                    {/if}
                
                
                
              {else}                  
                
                
               <center> <embed src="{url('/_data_/formulaciones/')}/{LMSController::encoder($ejercicio->id)}.pdf#toolbar=0" width="800" height="500"></center>
                {if $ejercicio->tipo_entrada ==0}
                <p><strong>Descargar</strong> .in: <a target='_blank' href='{url('ejercicio/descargar-in/')}/{LMSController::encoder($ejercicio->id)}'><img src='{url('img/general/txt.jpg')}'></a></p>
                    {/if}
                
                  {/if}
                        
                    </div>
                </div>
           <hr>
                  
                  <div class="row-fluid">
                      <div class="span12">
                          <h3>{if $ejercicio->tipo_entrada == 0} <i class='icon icon-file-text-alt'></i>{else} <i class='icon icon-code'></i> {/if} Respuesta</h3>
                          
                          {if $ejercicio->tipo_entrada == 0}
                          
                              
                          
                            {Form::open(['action' => 'TallerController@postEvaluarEjercicio', 'files'=>true])}
                               
                               <input type="hidden" name="ejercicio" value='{Crypt::encrypt($ejercicio->ejercicio)}'>
                               <input type="hidden" name="taller" value='{Crypt::encrypt($ejercicio->taller)}'>
                               <input type="hidden" name="curso" value='{Crypt::encrypt($curso->id)}'>
                               
                              {if $evaluacion !=-1}
                                    <input type="hidden" name="evaluacion" value='{Crypt::encrypt($evaluacion)}'>
                              {/if}
                               
                               
                               
                               <div class="row-fluid">
                                   <div class="span12">

                                       <span class='span2'>Copia tu salida aquí:</span>
                               <textarea rows="8" name='respuesta' class='span10' placeholder='Copia tu {if $ejercicio->tipo_entrada == 0}salida{else}código{/if} aquí'></textarea><br>
                               
                                       
                                   </div>
                               </div>
                                <input class='pull-right' type="file" name="out"><span class='pull-right'>o súbela:</span><br>     
                               
                                <button onclick="return lms.confirmar();" class='btn btn-success'> <i class='icon icon-upload'></i> Enviar</button>
                               {Form::close()}
                           {else} {*EL USUARIO TIENE QUE METER TODO EL CÓDIGO*}
                               
                               
                               
                              
                           
                              {Form::open(['action' => 'TallerController@postEvaluarEjercicioCodigo', 'files'=>true])}
                               
                               <input type="hidden" name="ejercicio" value='{Crypt::encrypt($ejercicio->ejercicio)}'>
                               <input type="hidden" name="taller" value='{Crypt::encrypt($ejercicio->taller)}'>
                               <input type="hidden" name="curso" value='{Crypt::encrypt($curso->id)}'>
                                {if $evaluacion !=-1}
                                    <input type="hidden" name="evaluacion" value='{Crypt::encrypt($evaluacion)}'>
                              {/if}
                               
                               
                               <div class="row-fluid">
                                   <div class="span12">
                                       <div class="span2">
                                           <span class=''>Lenguaje</span>
                                       </div>
                                       <div class="span4">
                                        <input type="radio" name="lenguaje" value='java' checked=""><span class=''>Java</span><br>
                                        <input type="radio" name="lenguaje" value='c++'><span class=''>C++</span>
                                       </div>
                                       <div class="span5" style="margin-left: 30px; margin-top: -55px;">
                                           <div class="alert-block alert-info" >
                                               
                                               <p><strong> &nbsp; Consideraciones:</strong></p>
                                               <ul>
                                                   <li><small>La clase o archivo principal se debe llamar Main</small></li>    
                                                   <li><small>No incluya paquetes ni archivos externos</small></li>    
                                                   <li><small>La lectura y salida es estándar</small></li>    
                                               </ul>

                                           </div>    

                                       </div>
                                   </div>
                               </div>
                               <br>
                               <div class="row-fluid">
                                   <div class="span12">
                                       <div class="span2">
 <span class='span12' style='font-size: 13px;'>Copia tu código aquí:</span>
                                       </div>
                                       <div class="span10">
<textarea rows="10" name='respuesta' class='span11' placeholder='Copia tu {if $ejercicio->tipo_entrada == 0}salida{else}código{/if} aquí'>{if $test}{$test->algoritmo}{/if}</textarea><br>
                                       </div>
                                   </div>
                               </div>
                               
                                       <input class="offset2"  onclick="tester.toogle();" type="checkbox" name="test" {if $test}checked=""{/if} id="checktest"><span style="margin-top: 4px;">Testear con mis casos de prueba</span>
                               
                               <input class='pull-right' type="file" name="out"><span class='pull-right'>o súbelo:</span><br>     
                               
                               
                               <div class="row-fluid {if !$test}hide{/if}" id="testing">
                                   <div class="span9 offset2">
                                       <h4>Casos de prueba</h4>
                                       {if $test}
                                       <textarea class="span6" rows="10" name="in" placeholder="Ingresa tus casos de prueba aquí">{$test->in}</textarea>
                                       <textarea class="span6" rows="10" name="" placeholder="Tu salida">{if $test->resultado != ""}<<{$test->resultado}>>{/if}{$test->mensaje}</textarea>
                                       {else}
                                           <textarea class="span12" rows="10" name="in" placeholder="Ingresa tus casos de prueba aquí">{if $test->resultado != ""}[{$test->resultado}]{/if}{$test->mensaje}</textarea>
                                       {/if}
                                       </div>
                               </div>

                               
                               <br>
                               <button onclick="return lms.confirmar();" class='btn btn-success'>Enviar</button>
                               {Form::close()}
                               
                               
                           {/if}
                             
                      </div>
                  </div>
                
            </div>
            
              </div>
                           
              
    </div>

                           
                                  {*Mostrar si hay un logro*}
        
            {if $logro!=null}                
             
                {include file='../modales/logro_obtenido.tpl'}

                
                
            {/if}
        
        {*Fin de mostrar si hay un logro*}
        
                           
                     
                           
                           
                             {if Session::has('veredicto')}
                                 <script> 
                                     veredicto = '{Session::get('veredicto')|upper}';
                                     
                                     delay = 3000;
                                    if(veredicto == 'WRONG ANSWER'){
                                             alertify.alert('RESPUESTA INCORRECTA');
                                             alertify.log('INCORRECTO!! Revisa tu respuesta',"error",delay);
                                     }else if(veredicto == 'COMPILATION ERROR'){
                                     alertify.alert('ERROR DE COMPILACIÓN');
                                     
                                            alertify.log('Oppss.. ha ocurrido un problema',"",delay);
                                     }
                                     else if(veredicto == 'TIME LIMIT'){
                                     alertify.alert('TIEMPO LIMITE EXCEDIDO');                                     
                                            alertify.log('Tu algoritmo se ha demorado mucho...',"",delay);
                                     }
                                     
                                     else if(veredicto == 'ACCEPTED'){
                                        alertify.alert('ACEPTADO');                                     
                                       // alertify.log('Oppss.. ha ocurrido un problema',"",delay);
                                     }else{
                                      alertify.alert('RESPUESTA DESCONOCIDA');     
                                     }
                                     
                                     </script>
                         {/if}
    
        
                         <hr>
              
                         <div class="row-fluid">
                             <div class="span12">
 {if $evaluacion==-1 and ejercicio::find($ejercicio->id)->esta_resuelto(Auth::user()->id, 0 , $ejercicio->taller) and $ejercicio->tipo_entrada==1} {*si es un ejercicio de taller y esta resuelto entonces puede ver las soluciones de los demas usuario*}
               
         {if $curso->tiene_soluciones_visibles()}
         {include file='./soluciones.tpl'}
         {/if}
            {/if}
                             </div>
                         </div>
       
 
        
        
        
                           
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='ejercicio'}
