{capture assign='content'}

    
    
    <div class="row-fluid">
        <div class="span12">
            <div class="span6">
                {include file='./divisiones/logros.tpl'}
                <hr>
                 {include file='./divisiones/estadisticas.tpl'}
            </div>
            <div class="span6">
{include file='./divisiones/ranking.tpl'}
            </div>
        </div>
    </div>
    
    
    
    

    
    
   
    
    
  
    
{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='perfil'}
