
{capture assign='content'}

  {if Auth::user()->rol == 1}
    {include file='../../profesor/crear_curso.tpl'}
    <br>
    <hr>
  {/if}  
  {include file='./inscritos.tpl'}
  <hr>    
  {include file='./disponibles.tpl'}
{/capture}   


{include file='_templates/template.tpl' layout='estudiante' tab='disponibles'}
