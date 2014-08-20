{capture assign='content'}
    


    <div class="row-fluid">
        <div class="span12">
                
            
            <h3>{$ejercicio->nombre}</h3>
            <p class='lead'>Total de envios: {ejercicio::find($ejercicio->id)->get_numero_envios_en_modulo(1,$evaluacion->id)}<p>            
            
            
    {* ------- prepare data -------*}

    {assign var=title value='Estadísticas de envío'}
    {assign var=width value=700}
    {assign var=height value=400}
    {assign var=chart_div value='chart_div'}
    {assign var=veredict value=['Error de compilación' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'compilation error'), 'Aceptado' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'accepted') , 'Respuesta incorrecta' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'wrong answer'),'Tiempo límite' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'time limit'),'Error de ejecución' => ejercicio::find($ejercicio->id)->get_numero_respuestas_en_modulo($curso->id,1,$evaluacion->id,'runtime error')]}
       
    
   
    {* ------- end prepare data -------*}
    
    {include file='../../../graficas/grafica.tpl'}
    
     <center><div id="chart_div"></div></center>
            
            
            
            
        </div>
    </div>
    
 
        
{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='evaluaciones'}
