
<h3 class="pull-left"><i class='icon icon-list-ol icon-2x'></i> Ranking</h3>

{assign var=top_programmer value=$curso->get_programador_de_la_semana()}

{if $top_programmer!=null}
  <div class="pull-right well" style="margin-top: -20px; padding: 0px;">
    <h5 class="text-info">Programador de la semana <i class="icon icon-trophy"></i></h5>
    <center><h5 style="margin-top: -10px;" class="text-success">{$top_programmer->nombres} {$top_programmer->apellidos}</h5></center>
    <center><img id='foto-top-programmer' onclick='usuario.ver_perfil({$top_programmer->id})' src='{General::avatar($top_programmer->id)}'></center>
  </div>    
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>

{/if}
<br>
<p class='pull-right'>Ver: <span class='text-info' style='cursor: pointer' onclick='ranking.ver_top(5)'>5</span> | <span class='text-info' style='cursor: pointer' onclick='ranking.ver_top(10)'>10</span> | <span class='text-info' style='cursor: pointer' onclick='ranking.ver_top(20)'>20</span> | <span class='text-info' style='cursor: pointer' onclick='ranking.ver_top(50)'>50</span> | <span class='text-info' style='cursor: pointer' onclick='ranking.ver_top(-1)'>Todos</span></p>


<table class="table table-striped table-condensed">

  <thead>

  <th>Posición</th>
  <th>Avatar</th>
  <th>Nombre</th>
  <th>Puntos</th>

</thead>



<tbody id='ranking'>





  {for $i=0 to count($ranking)-1}
    <tr class="{if {$ranking[$i]['id']} == Auth::user()->id}success{/if}">
      <td width='10%'><span style="font-size: 25px;">{$i+1}</span></td>
      <td width='20%'><img class='foto-ranking' onclick='usuario.ver_perfil({$ranking[$i]['id']})' src='{General::avatar($ranking[$i]["id"],'small')}'></td>
      <td><span style="font-size: 20px;">{$ranking[$i]['nombres']|capitalize} {$ranking[$i]['apellidos']|capitalize}</span></td>
      <td><span style="font-size: 25px;">{usuario::find($ranking[$i]['id'])->get_puntos_en_curso($curso->id)}</span></td>

    </tr>

  {/for}

  {assign var=posicion_en_ranking value=usuario::find(Auth::user()->id)->get_posicion_en_ranking($curso->id)}

  {if  $posicion_en_ranking> $top}
    <tr class='success'>
      <td width='10%'><span style="font-size: 25px;">{$posicion_en_ranking}</span></td>
      <td width='20%'><img class='foto-ranking' onclick='usuario.ver_perfil({Auth::user()->id})' src='{General::avatar(Auth::user()->id)}'></td>
      <td><span style="font-size: 20px;">{Auth::user()->nombres|capitalize} {Auth::user()->apellidos|capitalize}</span></td>
      <td><span style="font-size: 20px;">{usuario::find(Auth::user()->id)->get_puntos_en_curso($curso->id)}</span>
      </td>
    </tr>
  {/if}

</tbody>    
</table>    




<script>

  ranking = {
    top: 5,
    usuario: {Auth::user()->id},
    posicion: {usuario::find(Auth::user()->id)->get_posicion_en_ranking($curso->id)},
    puntos: {usuario::find(Auth::user()->id)->get_informacion_curso($curso->id)->puntos},
    in_top: false,
    ver_top: function (top) {
      // window.alert('pasa');
      ranking.top = top;
      ranking.in_top = ranking.posicion <= top;


      $.ajax({
        dataType: "json",
        type: 'post',
        url: "{url('curso/ranking')}",
        data: {
          top: top,
          curso: {$curso->id}

        },
        success: function (data) {
          $("#ranking").empty();

          // window.console.log(data);
          $.each(data, function (idx, value) {
            ranking.add_row(idx + 1, value.usuario_id, value.nombres, value.apellidos, value.puntos, value.foto);
          });

          if (!ranking.in_top && top != -1) {
            ranking.add_row(ranking.posicion,{Auth::user()->id}, '{Auth::user()->nombres|capitalize}', '{Auth::user()->apellidos|capitalize}', ranking.puntos, '{Auth::user()->foto}');
          }


        }
      });

    },
    add_row: function (pos, usuario, nombres, apellidos, puntos, foto) {

      var row = "<tr class='"
      row += ({Auth::user()->id} == usuario) ? 'success\'>' : '\'>';
      row += "<td width='10%'><span style='font-size: 20px;'>" + pos + "</span></td><td width='20%'><img class='foto-ranking' onclick='usuario.ver_perfil(" + usuario + ")' src='{url('avatares/userimages/')}/" + usuario + ".png'></td><td><span style='font-size: 20px;'>" + nombres + " " + apellidos + "</span></td><td><span style='font-size: 20px;'>" + puntos + "</span></td></tr>";

      $("#ranking").append(row);
    }

  }



</script>




