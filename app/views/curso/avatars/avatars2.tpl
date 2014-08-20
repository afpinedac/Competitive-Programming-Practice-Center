{capture assign='content'}


    
    



	
	{HTML::style('avatares/style.css')} 
	
        
        <div class="row-fluid">
            <div class="span12">
                <a href='{url('curso')}' style="margin-top: -50px;"><i class='icon icon-reply'></i>Volver</a>
            </div>
        </div>
        
        <center><h3>Modifica tu Avatar</h3></center>
        <div class="row-fluid">
            <div class="span10 offset1">

                
                <div id="container">
		<div id="loading">Cargando ...</div>
		<div id="save_btn" class="panel_btn"></div>
		<canvas id="avatar_canvas" width="800" height="600"></canvas>
		<div id="panel">
			<div id="panel_buttons">
				<div id="panel_colors"></div>
				<div id="back_btn" class="panel_btn"></div>
			</div>
			<div id="categories"></div>
			<div id="items"></div>
		</div>
		
	</div>
                
            </div>
        </div>



        <script>
            
            link = {
                avatar : '{URL::to('usuario/avatar')}',
                avatarxml : '{URL::to('avatares/avatar.xml')}',
                avatarimages : '{URL::to('avatares/images/')}',
                avataruserimages : '{URL::to('avatares/userimages/')}',
                publicpath : '{public_path()}'
              
            } 
   // window.console.log(link);
        </script>
        
        
{HTML::script('avatares/scripts.js')}   


</html>
    
    
    
    
  

    

    
{/capture}   


{include file='_templates/template.tpl' layout='default_login'}
