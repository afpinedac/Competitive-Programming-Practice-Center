{capture assign='content'}

  
    
    
    
    <div class="row-fluid">
        <div class="span12">
                
            
            <div class="span3 well">
                <center><h4>Crear tema</h4></center>
                 
    {Form::open(['url'=> ['foro/crear-tema']])}
    <label>Nombre:</label>
    <input type="text" name="nombre" required class='span12'><br>
    <label>Descripción:</label>
    <textarea class='span12' rows="8" name='descripcion' placeholder="Escriba la descripción" required></textarea><br>
    <center><input type="submit" class='btn btn-success' value='Crear Tema' ></center>
    {Form::close()}    
                
                
                
                
            </div>
            <div class="span9">
                
                
                  <div class="row-fluid">
        <div class="span12">

{if count($temas)>0}
  
   {foreach $temas as $tema}
       
      <div class="row-fluid">
        <div class="span12 well">
            <p class='pull-right' style='margin-top: -20px;'><small>Creado por <a href='#'>{$tema->nombres|capitalize}</a> el dia {$tema->created_at}</small> {if Auth::user()->id == $tema->usuario}<a  href='{url('foro/eliminar-tema/')}/{$tema->id}'><i class='icon icon-remove'></i></a>{/if}</p>
            <h3 style='margin-top: -15px;' class='pull-left'><a href='{url('curso/ver')}/{$curso->id}/foro/{$tema->id}'><i class='icon icon-comment'></i> {e($tema->nombre)}</a></h3>
            
            
<br>
<br>
<br>
            
            <p>{e($tema->descripcion)}</p>
            
            <p class='pull-right'>
                <a href='{url('curso/ver')}/{$curso->id}/foro/{$tema->id}'>Responder</a> &nbsp;&nbsp;
                <i class='icon icon-comments-alt'></i> ({temaforo::find($tema->id)->get_numero_de_respuestas()})
            </p>

            
        </div>
    </div>

       
   {/foreach}
{else}
    
    <div class="row-fluid">
        <div class="span12">
            <div class="alert alert-info">
                <br>
                <center><h4>Este foro no tiene ningún tema, sé el primero en crear uno</h4></center>
                <br>
            </div>    

        </div>
    </div>
{/if}
   
   
   
   
   
   
           </div>
    </div>
        
                
                

            </div>
        </div>
    </div>
    
   
    
    
    
    
  
        
                           
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='foro'}
