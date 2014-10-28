

<div class="row-fluid">
    <div class="span12">
        
       
        
        <div class="row-fluid">
            <div class="span12">
               
                <center><a href='{url('curso/avatars')}'> <img id='avatar-principal' style='margin-top: 0px' src='{General::avatar(Auth::user()->id)}'></a></center>
                <p></p>
                <center>
                <p style='margin-top: -3px'>
                    
                    <span class="label label-success">
                        
                        {if Auth::user()->id==$curso->profesor_id}
                            {"PROFESOR"}
                    {else if $layout != "curso"}
                        {if Auth::user()->rol == 1}
                            {"PROFESOR"}
                            {else}
                               {"ESTUDIANTE"}
                            {/if}
                        {else if usuario::find(Auth::user()->id)->es_monitor($curso->id)}
                            {"MONITOR"}
                            {else}                         
                            {"ESTUDIANTE"}
                    {/if}
                    </span>
                    {*
                    <span class=''>
                        <a href='{url('curso')}/personalizar-avatar'><i class='icon icon-edit'></i> Personalizar</a>
                    </span>
                    *}
                </p>
                </center>

            

            </div>
        </div>
                    
                
                
                
 
               
              {if $layout=="curso" and count($amigos)>0 and $curso->tiene_chat()}
               <div class="row-fluid">
                   <div class="span12">
                       <h5><i class='icon icon-user icon-2x'></i> Amigos ({count($amigos)})</h5>
                       <div id='lista-amigos'>

                           
                           <script>
                               friends=Array();
                               friends_=Array();
                               friends[{Auth::user()->id}] = '{Auth::user()->nombres|capitalize}'
                               friends_[{Auth::user()->id}] = '{Auth::user()->nombres|capitalize} {Auth::user()->apellidos|capitalize}'
                            // window.console.log(friends);
                               </script>
                           
                           
                           {foreach $amigos as $amigo}

                           <script>
                           friends[{$amigo->id}] = '{$amigo->nombres}';
                           friends_[{$amigo->id}] = '{$amigo->nombres} {$amigo->apellidos}';
                           </script>
                           
                           <a href="javascript:void(0)" onclick="javascript:chatWith('{$amigo->id}')">
                           <div class="row-fluid amigo">
                               <div class="span1">
                                   {if usuario::find($amigo->id)->get_ultima_interaccion_en_curso($curso->id) + LMSController::$MINUTES_TO_OFFLINE * 60 >= time()}                                   
                                   <img src='{url('img/chat/online.jpg')}' class='status-chat' >
                                   {else}
                                       <img src='{url('img/chat/offline.jpg')}' class='status-chat' >
                                       {/if}
                               </div>
                               <div class="span2">
                                   <img src='{General::avatar($amigo->id)}' class='foto-amigo-chat' >
                               </div>
                               <div class="span9 suspensive-points">
                                   <small>{$amigo->nombres|capitalize} {$amigo->apellidos|capitalize} </small>
                               </div>
                           </div>  
                               
                               </a>
                          
                                  {/foreach} 
                                  
                           
                               
                       </div>    

                       
                   </div>
               </div>
                                  
          {*se carga el chat y angular*}   
  
          
          
  {HTML::style('libs/chat/css/chat.css')}        
  {HTML::style('libs/chat/css/screen.css')}
  {HTML::script('libs/angularjs/angular.min.js')}
  
  
  
 <script>
     url_chat = {
       sendchat : '{url('chat/sendchat')}',
       startchatsession : '{url('chat/startchatsession')}',
       chatheartbeat : '{url('chat/chatheartbeat')}',
       closechat : '{url('chat/closechat')}'
     };
     
     envios = {
       request_url : '{url('envio/all')}'
     };
     
     
 </script> 
  {HTML::script('libs/chat/js/chat.js')}   
  {HTML::script('js/angular/appEnvios.js')}
                         
  
  
  
  <!---- angular-------------->
  <div class="row-fluid">
    <div class="span12">
        {include file='./panel_envios.tpl'}
    </div>
  </div>
  
  
  
  
  
  
  
  
             
        {else if $layout=="estudiante"}
            <br>
            <br>
            <div class="row-fluid">
                <div class="span12 well well-small">
                    
                    <legend><small><i class='icon icon-paper-clip'></i> <strong>Últimas notícias</strong></small> <span class="badge-important badge">3</span></legend> 
                    
                    <ul>
                           <li class="">
                               <span style="font-size: 10px;"><span>Recuerda <strong>modificar tu Avatar </strong>haciendo click en la imagen </em></span>
                        </li>    
                        <li>
                            <span style="font-size: 10px;"><span>Bienvenidos estudiantes al curso de <strong>Estructuras de Datos</strong> 2014-II</span>
                        </li>    
                        <li>
                            <span style="font-size: 10px;"><span>Pregunta por la <strong>Liga Colombiana de Programación</strong></span>
                        </li>    
                           
                    </ul>
                </div>
            </div>
            
  
        {/if}
        
    </div>
</div>
    