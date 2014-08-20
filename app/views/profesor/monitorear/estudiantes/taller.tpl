{capture assign='content'}
    

   <div class="row-fluid">
        <div class="span12">
                
            
            <h3>{$taller->nombre}</h3>
            <p class='lead'>Total de envios: {usuario::find($estudiante->id)->get_numero_envios_en_modulo(0,$taller->id)}<p>            
            
            
    {* ------- prepare data -------*}

    {assign var=title value='Estadísticas de envío'}
    {assign var=width value=700}
    {assign var=height value=400}
    {assign var=chart_div value='chart_div'}
    {assign var=veredict value=['Error de compilación' => usuario::find($estudiante->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'compilation error'), 'Aceptado' => usuario::find($estudiante->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'accepted') , 'Respuesta incorrecta' => usuario::find($estudiante->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'wrong answer'),'Tiempo límite' => usuario::find($estudiante->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'time limit'),'Error de ejecución' => usuario::find($estudiante->id)->get_numero_respuestas_en_modulo($curso->id,0,$taller->id,'runtime error')]}
       
    
   
    {* ------- end prepare data -------*}
    
    {include file='../../../graficas/grafica.tpl'}
    
     <center><div id="chart_div"></div></center>
            
            
            
            
        </div>
    </div>
    
{/capture}   


{include file='_templates/template.tpl' layout='monitorear' tab='estudiantes'}
