{capture assign='content'}
    


    <div class="row-fluid">
        <div class="span12">
                
            
            <h3>{$ejercicio->nombre}</h3>
            <p class='lead'>Total de envios: {ejercicio::find($ejercicio->id)->get_numero_envios_en_modulo(0,$taller->id)}<p>            
            
            
    {* ------- prepare data -------*}

    {assign var=title value='Estadísticas de envio'}
    {assign var=width value=700}
    {assign var=height value=400}
    {assign var=chart_div value='chart_div'}
    {assign var=veredict value=['Error de compilación' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'compilation error'), 'Aceptado' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'accepted') , 'Respuesta incorrecta' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'wrong answer'),'Tiempo limite' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'time limit'),'Error de ejecución' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'runtime error')]}
         
    {* ------- end prepare data -------*}
    
    {include file='../../../graficas/grafica.tpl'}
    
     <center><div id="chart_div"></div></center>
            
          
     {*char de envios*}    
    {assign var=envios value=envio::get_numero_envios('ejercicioentaller',$taller->id,$ejercicio->id)}
{assign var=title value='Envios realizados por el estudiante'}

{include file='../../../graficas/timeline.tpl'}
 <center><div  id="timeline" style="width: 800px; height: 300px;"></div></center>
{*end char de envios*}
     
            
            
        </div>
    </div>
    
 
        
{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='talleres'}
