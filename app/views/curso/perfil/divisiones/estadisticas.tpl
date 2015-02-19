<h3><i class='icon icon-bar-chart icon-2x'></i> Estadísticas</h3>

<ul>
    <li><strong>Logros desbloqueados :</strong> <em>{count($logros)}</em></li>    
    <li><strong>Ejercicios de talleres solucionados :</strong> <em>{count(usuario::find(Auth::user()->id)->get_ejercicios_resueltos($curso->id))}</em></li>    
    <li><strong>Ejercicios de evaluaciones solucionados :</strong> <em>{count(usuario::find(Auth::user()->id)->get_ejercicios_resueltos($curso->id,1))}</em></li>    
    <li><strong>Módulos desbloqueados :</strong> <em>{$curso->get_numero_modulos_desbloqueados()}</em></li>    
       
</ul>

    
    
{* ------- prepare data -------*}

    {assign var=title value='Envios en todo el curso'}
    {assign var=width value=400}
    {assign var=height value=400}
    {assign var=chart_div value='chart_div'}
    {assign var=veredict value=['Error de compilación' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_curso($curso->id,'compilation error'), 'Aceptado' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_curso($curso->id,'accepted') , 'Respuesta incorrecta' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_curso($curso->id,'wrong answer'),'Tiempo límite' => usuario::find(Auth::user()->id)->get_numero_respuestas_en_curso($curso->id,'time limit')]}
       
    {include file='../../../graficas/grafica.tpl'} 
    
   
    {* ------- end prepare data -------*}
    
{*char de envios*}    
    {assign var=envios value=envio::get_numero_envios('estudiante',Auth::user()->id)}
{assign var=title value='Envios realizados por el estudiante'}

{include file='../../../graficas/timeline.tpl'}

{*end char de envios*}
    
    
    <div id='chart_div'></div>   
    <h5><center>Número de envios diariamente</center></h5>
    <div  id="timeline" style="width: 500px; height: 300px;"></div>
    <br>
    <br>

    
    