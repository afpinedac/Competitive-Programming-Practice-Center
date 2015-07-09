  

<ul class="nav pull-right">
                   {* <li> <a href="curso.php"><i class="icon icon-share-alt"></i> Back</a><li> *}
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img id='mini-foto' src='{General::avatar(Auth::user()->id,'small')}'> {Auth::user()->nombres|capitalize} {" "} {Auth::user()->apellidos|capitalize} 
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                             
                            <li> <a href="{URL::action('UsuarioController@getInformacion')}"><i class="icon icon-edit"></i> Perfil</a><li>
                            <li class="divider"></li>   
                            <li> <a href="{URL::action('UsuarioController@getLogout')}"><i class="icon icon-off"></i> Salir</a><li>

                        </ul>    
                    </li>   

  </ul>  
                            
                            
                            
                            
                            
                     