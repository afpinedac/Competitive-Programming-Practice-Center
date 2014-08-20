{capture assign='content'}


    {*MENSAJES Y ERRORES*}
    
    
    
    
    
    <div class="row-fluid">
    <div class="span12">
        <div class="span12">
            
            <div class="row-fluid">
                <div class="span12">
                    
                    <a href='{url('curso/all')}'><i class='icon icon-reply'></i> Volver</a>

           
                    <center><h2>TIENDA</h2></center>
                     <h3 class='pull-right' style='margin-top: -40px'><i class='icon icon-money'></i> Dinero disponible : <span style="color:green">${usuario::find(Auth::user()->id)->get_dinero_total()}</span></h3>
                    <hr>
                    <h3><i class='icon icon-star-empty'></i> Items disponibles</h3>
            
           
            <br>
                       
            <div class="row-fluid">
                
 
                
                    <div class="span12">

                        

                        

                        
                        {foreach $disponibles as $item}
                            
                            
                            <div  class='well well-small' style='float: left; margin-right: 30px; border: black solid 0px;'>
                                <div class="" style='float:left; margin-right: 5px;'>
<img src='{url('img/items/')}/{$item->id}.png'>
                                </div>   
                                <div class="" style='float: right'>
                                    <p style='font-size: 22px;'><strong> {$item->nombre}</strong></p>

                            <span style='font-size: 28px;'><em>${$item->precio}</em></span>
                            <br>
                            <a style='' href='{url('item/comprar')}/{$item->id}' class='btn btn-success btn-mini'><i class='icon icon-money'></i> Comprar</a>
                                </div>    


                            </div>    
                        {foreachelse}
                            
                            <div class="alert alert-block">
                                <center> <p>No hay items disponibles para comprar</p>
                                </center>
                            </div>    

                              

                        {/foreach}
                                               
                            
                        
                        

                        <br>
                    </div>


                

 
            </div> <br>
            <hr>

                 </div>
            </div>
            
            
            
            <div class="row-fluid">
                <div class="span12">
                    <h3><i class='icon icon-star-empty'></i> Items comprados</h3>
                    <br>
                                           
                        {foreach $comprados as $item}
                            
                            
                            <div class='well well-small' style='float: left; margin-right: 30px; border: black solid 0px;'>
                                <div class="" style='float:left; margin-right: 5px;'>
<img src='{url('img/items/')}/{$item->id}.png'>
                                </div>   
                                <div class="" style='float: right'>
                                    <p style='font-size: 22px;'><strong> {$item->nombre}</strong></p>

                           
                            {if $item->usando == 0}
                            <a style='' href='{url('item/usar')}/{$item->id}/si' class='btn btn-info btn-mini'><i class='icon icon-star'></i> Usar</a>
                            {else}
                                <a style='' href='{url('item/usar')}/{$item->id}/no' class='btn btn-info btn-mini'><i class='icon icon-star-empty'></i> Dejar de usar</a>
                                {/if}
                                <p>
                                    
                                    
                                    <a style='' href='{url('item/vender')}/{$item->id}' class='btn btn-success btn-mini'><i class='icon icon-money'></i> Vender ($ {$item->precio/2})</a> 
                                </p>
                                </div>    

                                     
                            </div>    

                            {foreachelse}
                            
                            <div class="alert alert-block">
                                <center> <h5>No has comprado ningún ítem</h5>
                                </center>
                            </div>    
                            
                              

                        {/foreach}

                    
                </div>
            </div>
            
            
        </div>
    </div>
    </div>

    

{/capture}   


{include file='_templates/template.tpl' layout='default_login'}
