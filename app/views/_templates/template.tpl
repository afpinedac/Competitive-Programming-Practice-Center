<!DOCTYPE html>
<html lang="es">
  <head>
    <title>{$title|default:"CPP | Centro de Práctica de Programación"}</title>  
    {HTML::script('js/analytics.js')}  
    
    <meta name="description" content="CPP es un sitio excelente para los profesores e Instituciones Educativas que desean contar con una plataforma Virtual de Aprendizaje para la enseñanza y práctica de la Programación Competitiva"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    {*font google fonts*}
    <link href='http://fonts.googleapis.com/css?family=Londrina+Sketch' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Londrina+Solid' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Press+Start+2P' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Cabin+Condensed' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Average+Sans' rel='stylesheet' type='text/css'>
    {*end google fonts*}
    <link rel="shortcut icon" href="{url('img/general/ico.png')}">
    {HTML::style('css/lms.css')}  
    {HTML::style('css/fonts.css')}  
    {HTML::style('libs/normalize/normalize.css')}
    {HTML::style('libs/bootstrap/css/bootstrap.min.css')}
    {HTML::style('libs/tipped/css/tipped/tipped.css')}
    {HTML::style('libs/font-awesome/css/font-awesome.min.css')}   
    {HTML::script('libs/jquery/jquery-1.9.1.js')}  
    {HTML::script('libs/jquery-ui/jquery-ui-1.9.2.custom.min.js')} 
    {HTML::script('libs/tipped/js/tipped/tipped.js')} 
    {HTML::script('libs/tipped/js/spinners/spinners.min.js')} 
    {HTML::script('libs/alertify/src/alertify.js')} 
    {HTML::style('libs/alertify/themes/alertify.core.css')} 
    {HTML::style('libs/alertify/themes/alertify.default.css')} 
    {HTML::script('libs/bootstrap/js/bootstrap.min.js')}   
    {HTML::script('js/lms.js')}    
    {HTML::script('js/envio.js')}    
    {HTML::script('libs/angularjs/angular.min.js')}
    {*{HTML::script('libs/angularjs/angular.min.js.map')}*}
    {HTML::script('js/angular/app_cpp.js')}
    <script>
      URL = {
        set_pre_requisito: '{url('modulo/establecer-prerequisito')}',
        set_monitor: '{url('curso/asignar-monitor')}',
      }
      base_url = '{url('/')}'
    </script>
  </head>
  <body ng-app='CPP' ng-cloak > 
  <content>
    {capture assign='layouts'}../_layouts/{$layout|default:'default'}.tpl{/capture}     
    {include file='../mensajes/alertify.tpl'}      
    {include file='../curso/modales/perfil_usuario.tpl'}      
    {include file=$layouts}       
  </content>
      <br>
  <br>
  <footer class="well well-large"   style='margin-bottom: -5px; height: 80px;'>
    <div class="row-fluid">
      <div class="span12">
        <span class=''><strong>Contáctanos:</strong> <a href="https://www.facebook.com/afpinedac" target="_blank">Andrés Pineda</a></span>

        <center>
          <a href="http://codeforces.com/" target="_blank"><img  style="height: 35px; width:180px;" src='{url('img/sponsors/codeforces.png')}'> </a>
          <a href="http://guiame.medellin.unal.edu.co/semillero/ppc/" target="_blank"><img style="height: 45px; width:180px;" src='{url('img/sponsors/ppc.png')}'></a>
          <a href="http://aprenderaprogramar.com.co/" target="_blank"><img style="height: 40px; width:80px;" src='{url('img/sponsors/aap.png')}'></a>
          &nbsp;<a href="http://www.minas.medellin.unal.edu.co/" target="_blank"><img style="height: 80px; width:140px;" src='{url('img/sponsors/unalmed.png')}'></a>

          &nbsp;&nbsp;<a href="http://guiame.medellin.unal.edu.co/" target="_blank"><img style="height: 45px; width:100px;" src='{url('img/sponsors/guiame.png')}'></a>
          &nbsp;&nbsp;<a href="http://www.topcoder.com/" target="_blank"><img style="height: 30px; width:160px;" src='{url('img/sponsors/topcoder.jpg')}'> </a> &nbsp; &nbsp;
          &nbsp;&nbsp;<a href="http://uhunt.felix-halim.net/" target="_blank"><img  style="height: 45px; width:160px;" src='{url('img/sponsors/uhunt.png')}'></a>
        </center>
      </div>
    </div>
  </footer>
  </body>




</html>
