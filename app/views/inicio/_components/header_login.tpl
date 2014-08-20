

    
    
    <div class="navbar">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
                    
                   
                    <a style="padding: -10px; margin-top: -10px; margin-bottom: -15px;" class="brand" href="{URL::to('/')}" name="top"><i class='icon icon-star'></i> <span class='' style="font-family: 'Londrina Solid', cursive; font-size: 50px"><img style="margin-right: -10px;" src='{url('img/general/logo2.png')}'></span> </a>
			<div class="nav-collapse collapse">
				<ul class="nav">
                                        <li class="divider-vertical"></li>
                                        <li><a href="{url('/about')}"> Qué es?</a></li>
					<li class="divider-vertical"></li>
				{*	<li class=""><a href="#">Características</a></li>
					<li class="divider-vertical"></li>
					<li><a href="#"><i class="icon-envelope"></i> Messages</a></li>
					<li class="divider-vertical"></li>
                  	<li><a href="#"><i class="icon-signal"></i> Stats</a></li>
					<li class="divider-vertical"></li>
					<li><a href="#"><i class="icon-lock"></i> Permissions</a></li>
					<li class="divider-vertical"></li>*}
				</ul>
                            
                            
                            
				<ul class="nav pull-right">
                                    <li><a href="{url('/registrar')}"> <i class='icon icon-pencil'></i> Registarse</a></li>
                  	<li class="divider-vertical"></li>
					<li class="dropdown">
                                            <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class='icon icon-star'></i> Entrar<strong class="caret"></strong></a>
						<div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
							{Form::open(['url'=>'/loguear'])}
                                                        <input style="margin-bottom: 15px;" type="text" placeholder="E-mail" id="username" name="email" required value='{if Cache::has('remember-user')}{*{Cache::get('remember-user')}*}{/if}'>
                                                        
								<input style="margin-bottom: 15px;" type="password" placeholder="Password" id="password" name="password" value='{if Cache::has('remember-pass')}{*{Cache::get('remember-pass')}*}{/if}'>
								<!--<input style="float: left; margin-right: 10px;" type="checkbox" name="rememberme" id="remember-me" {if Cache::has('rememberme')}checked=""{/if} >
								<label class="string optional" for="user_remember_me"> Recordar mis datos</label>-->
								<input class="btn btn-primary btn-block" type="submit" id="sign-in" value="Entrar">
                                                          {Form::close()}
						<!--		<label style="text-align:center;margin-top:5px">or</label>
                                                                <button class='btn btn-primary btn-block'><i class='icon icon-google-plus-sign'></i> Loguear con Google</button>
                                                                <button class='btn btn-primary btn-block'><i class='icon icon-facebook-sign'></i> Loguear con Facebook</button> -->
                                                               
                                                <a href='{URL::route('registrarse')}' class='btn btn-block btn-success'>Registrarse</a>
                                               <!-- <button class='btn btn-success btn-block'>Registrarse</button>-->
                                                               <br>
							
						</div>
					</li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
		<!--/.container-fluid -->
	</div>
	<!--/.navbar-inner -->
</div>

<!--/.navbar -->
{*
<a href='{URL::route('change_language', ['lang'=>'es'])}'>es</a>
<a href='{URL::route('change_language', ['lang'=>'en'])}'>en</a>

*}


<script>
$(document).ready(function()
{
  //Handles menu drop down
  $('.dropdown-menu').find('form').click(function (e) {
        e.stopPropagation();
        });
  });
</script>
    
 
