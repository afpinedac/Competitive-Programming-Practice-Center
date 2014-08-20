<div class="row-fluid">
    <div class="span12">
      
        <div class="row-fluid">
            <div class="span12">
                
  {include file='../curso/_components/header_curso.tpl'}
            </div>
        </div>
        
            
            
            
            
            
            
            <div class="row-fluid">
                <div class="span12">
                    <div class="span10 offset1">
                        <a href='{url('curso/')}/ver/{$curso->id}/' class="pull-left btn btn-success"><i class='icon icon-reply'></i> Volver al curso</a>            
                        <br>
                        <br>
                        <h3 class='text-right'>{$curso->nombre|truncate:50}</h3>                      
                        
                        
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="span2 well">
                                 <ul class='nav nav-list'>                           
                             <li class='{if $tab=="talleres"}active{/if}'><a href='{url('curso/monitorear')}/{$curso->id}/talleres'>Talleres</a></li>                                
                            <li class='{if $tab=="evaluaciones"}active{/if}'><a href='{url('curso/monitorear')}/{$curso->id}/evaluaciones'>Evaluaciones</a></li>                                
                            <li class='{if $tab=="estudiantes"}active{/if}'><a href='{url('curso/monitorear')}/{$curso->id}/estudiantes'>Estudiantes</a></li>                                
                        </ul>
                                </div>
                                <div class="span10">
                                  <h2 style='margin-top: -40px;'>{$tab|upper}</h2> 
                                  
<ul  class="breadcrumb">
  
    {for $i=1 to count($path)}
            {if $i == count($path)}               
                 <li class="active">{$path[$i]['nombre']}</li>
            {else}            
                 <a href="{$path[$i]['enlace']}">{$path[$i]['nombre']}</a> 
                 <span class="divider">/</span>
            {/if}
    {/for}
  
</ul>
                                 
                                  <div class="row-fluid">
                                      <div class="span12">
                                              {if isset($content)}
                                      {$content}
                                    {/if}
                                      </div>
                                  </div>
                                  
                                
                                    
                                            
                                </div>                                
                                
                                
                            </div>
                        </div>
                        
                        
                        
                        
                    </div>    

                </div>
            </div>
            
            
            
        
        
    
        
        
        
      
    </div>
</div>