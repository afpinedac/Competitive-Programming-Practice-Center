 
<div class="row-fluid">
  <div class="span12">


    <div class=" offset1 span10">
      <div class="row-fluid">
        <div class="span12">
          <h3 id="soluciones">Soluciones</h3>

          <a style="margin-top: -35px;" href='{url('curso/ver/')}/{$curso->id}/contenido#ejercicios' class='btn btn-info pull-right'><i class='icon icon-chevron-sign-left'></i> Volver al Taller</a>         

          <div class="row-fluid">

            <div class="span12">
              {assign var=soluciones value=ejercicioxtaller::get_soluciones($ejercicio->id,$ejercicio->taller)}
              {foreach $soluciones as $solucion}
                {assign var=user value=usuario::find($solucion->usuario)}
                <div class="row-fluid">
                  <div class="span12">

                    <div class="span2">


                      <center> <img class="img-foto-foro" onclick='usuario.ver_perfil({$user->id})' style='cursor: pointer' src='{General::avatar($user->id,'small')}'></center>
                      <p class="text-center">{$user->nombres|capitalize}</p>
                      <p class="text-center">{$solucion->created_at|date_format}</p>


                    </div>

                    <div class="span10">
                      <ul>
                        <li><strong>Lenguaje: </strong> {$solucion->lenguaje}</li>    
                        <li><strong>Tiempo de ejecución: </strong> {$solucion->tiempo_de_ejecucion}</li>    
                      </ul>                                                           
                      <pre>{e(General::trim($solucion->algoritmo))}</pre>
                      <span class='pull-right'><a href='#soluciones'>[<i class='icon icon-arrow-up'></i> Arriba]</a></span>
                    </div>

                  </div>
                </div>
              {foreachelse}
                <div class="alert alert-block">
                  <center>
                    <p>No hay soluciones para este ejercicio</p>
                  </center>
                </div>    

              {/foreach}
            </div>
          </div>
        </div>
      </div>
    </div>




  </div>
</div>