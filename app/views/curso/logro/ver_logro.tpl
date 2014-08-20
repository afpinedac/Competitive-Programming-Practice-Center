




{capture assign='content'}
    <div class="row-fluid">
        <div class="span12">

     
 {*/////////////// HEADER DEPENDIENDO SI ESTA LOGUEADO O NO ///////*}   
 {if Auth::check()}
          {include file='estudiante/_components/header_estudiante.tpl'} 
      {else}
          
          
          
          {include file='inicio/_components/header_login.tpl'} 
          
 {/if}
 {*/////////////// HEADER DEPENDIENDO SI ESTA LOGUEADO O NO ///////*}   
 
    </div>
    </div>
 
 
 
 
 <div class="row-fluid">
     <div class="span12">
            
         
  
         <div class="span10 offset1">
{if Auth::check()}
                                 <a href='{url('/curso/')}' class='btn btn-info pull-right'><i class='icon icon-chevron-sign-left'></i> Volver a CPP</a>         
{else}
    <a href='{url('/')}' class='btn btn-info pull-right'><i class='icon icon-chevron-sign-left'></i> Entrar en CPP</a>         
{/if}
             
             <div class="row-fluid">
                 <div class="span12">
                     <div class="span3">
                         <img src='{General::avatar($usuario->id)}' class='foto-logro'>
                     </div>
                     
                     <div class="span5">
         <h2><em>{$usuario->nombres|capitalize} {$usuario->apellidos|capitalize}</em></h2>
         <br>
         <ul>
             <li><h3>Ha obtenido el logro : <br>"<span class="text-success"  style="text-decoration: underline;">{$logro->nombre}</span>" ,
             en el curso "<span class="text-success" style="text-decoration: underline;">{$curso->nombre}</span>"</h3></li>
             <li><h3>Su puntuación es: <span class="text-success"  style="text-decoration: underline;">{usuario::find($usuario->id)->get_puntos_en_curso($curso->id)}</span></h3></li>
             <li><h3>Su posición en el ranking es: <span class="text-success"  style="text-decoration: underline;">{usuario::find($usuario->id)->get_posicion_en_ranking($curso->id)}</span></h3></li>
         </ul>
           
             
             
                     </div>
             
             
             <div class="span4">
                 <center><h2>{$logro->nombre}</h2></center>
                          <center><img src='{url('img/logros')}/{$logro->codigo}.png'></center>
                          <center><h4>{$logro->descripcion}</h4></center>
                          
                     </div>
                     
                 </div>
             </div>
             
             
             
            
             
           
             
             
          
             
             
         </div>
         
         
     </div>
 </div>
 
 
 
 


{/capture}   


{include file='_templates/template.tpl' layout='default'}
