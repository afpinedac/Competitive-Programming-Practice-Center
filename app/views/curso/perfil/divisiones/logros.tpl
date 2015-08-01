<h3><i class='icon icon-trophy icon-2x'></i> Logros obtenidos</h3>
{if count($logros)>0}

  <div class="row-fluid">

    {foreach $logros as $logro}
      <div class='span3'>
        <center><img class='img-logro' src='{url('img/logros/small')}/{$logro->codigo}.png' title="{$logro->descripcion}"></center>
        <center><span class='text-success'><strong>{$logro->nombre}</strong></span></center>
      </div>
    {/foreach}

  </div>
{else}
  <div class="alert alert-block">
    <p class='text-center'>No has conseguido ningún logro</p>
  </div>    
{/if}
