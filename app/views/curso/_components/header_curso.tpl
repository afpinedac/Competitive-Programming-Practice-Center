<div class="navbar">
  <div class="navbar-inner">
      <a class="brand" href="#"><span id="nombre-curso">{$curso->nombre|upper}</span></a>

    
    {include file='../../_layouts/_components/opciones_usuario.tpl'}
    
        <ul class="nav pull-left">
            <li class=""><a href="{URL::to('curso')}"><i class='icon icon-home'></i> Inicio</a></li>    
    </ul>
        <ul class="nav pull-right">
            <li><a href='{url('curso/ver')}/{$curso->id}/foro'><i class='icon icon-comments'></i> Foro</a><li>
            <li class=""><a href="{URL::to('curso/ver')}/{$curso->id}/mensajes"><i class='icon icon-envelope'></i> Mensajes ({usuario::find(Auth::user()->id)->numero_de_mensajes_sin_leer($curso->id)})</a></li>    
    </ul>
  </div>
</div>