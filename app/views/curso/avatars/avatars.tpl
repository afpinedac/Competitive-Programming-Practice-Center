{capture assign='content'}


    
    
    
    
    <div class="row-fluid">
    <div class="span12">
        <div class="span10 offset1">
            <div class="row-fluid">
                <h3 class="pull-left">Selecciona tu avatar</h3> <span class='pull-right'><a href='{url('curso/all')}'><i class='icon icon-share-alt'></i> Regresar</a></span>
            </div>            
            <div class="row-fluid">
                
                {Form::open(['url' => 'usuario/actualizar-avatar'])}
                
                    <div class="span12">


                        
                        {for $i=1 to 28}
                            
                            <span style='margin: 10px;'>
                                <img src='{url('img/avatars/')}/{$i}.png'>
                                <input  type="radio" name="avatar" value="{$i}.png"></span>

                        {/for}

                        <br>
                    </div>


                    <center ><button class='btn btn-success' style="margin-top: 20px;"><i class='icon icon-save'></i> Seleccionar</button></center>

               {Form::close()}
            </div> <br>
            <br>

        </div>
    </div>
    </div>

    

{/capture}   


{include file='_templates/template.tpl' layout='default_login'}
