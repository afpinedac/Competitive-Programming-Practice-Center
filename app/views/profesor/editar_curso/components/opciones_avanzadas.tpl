


{Form::open(['action'=>'TallerController@postOpcionesavanzadas'])}

<p>
    <strong><i class='icon icon-chevron-right'></i> Asignar fecha de Inicio: </strong>  <em>Ninguna:</em> <input type="checkbox" name="tiene_inicio" id='tiene_inicio' {if $taller->tiene_inicio==0} checked="checked" {/if} onclick="formulario_opciones_avanzadas.set_fecha_inicio()"> <span id="fecha_inicio"  {if $taller->tiene_inicio==0}  class='hide' {/if}><em> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha :</em> <input type="datetime-local" class='' id='in-fecha-inicio'  {if $taller->tiene_inicio == 1} value='{General::formatear_fecha($taller->fecha_inicio)}' {/if}   name="fecha_inicio"></span>
</p>

<p>

<strong><i class='icon icon-chevron-right'></i> Asignar fecha de Finalización: </strong>  <em>Ninguna:</em> <input type="checkbox" name="tiene_fin" id='tiene_fin' {if $taller->tiene_fin==0} checked="checked" {/if} onclick="formulario_opciones_avanzadas.set_fecha_fin()"> <span id="fecha_fin" {if $taller->tiene_fin==0} class='hide' {/if}><em> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha :</em> <input type="datetime-local" id='in-fecha-fin' name="fecha_fin"  {if $taller->tiene_fin == 1} value='{General::formatear_fecha($taller->fecha_fin)}' {/if}  ></span>
</p>

<p>
    <strong><i class='icon icon-chevron-right'></i> <em>Permitir envios tardíos:</em> </strong><input onclick="formulario_opciones_avanzadas.permitir_envios_tardios();"   {if $taller->envios_tardios == 1}checked="checked"{/if}  type="checkbox" name="envios_tardios">
</p>

<div id='div-envios-tardios'  {if $taller->envios_tardios == 0} class='hide' {/if}>
    <strong><i class='icon icon-star'></i> Porcentaje de disminución:</strong>
    <select id='porcentaje_disminucion' class='span2' name='porcentaje_disminucion'>
        <option value='0'>0%</option>    
        <option value='10'>10%</option>    
        <option value='20'>20%</option>    
        <option value='30'>30%</option>    
        <option value='40'>40%</option>   
        <option value='50'>50%</option>    
        <option value='60'>60%</option>    
        <option value='70'>70%</option>    
        <option value='80'>80%</option>    
        <option value='90'>90%</option>    
    </select>   
    
    <em>Cada:</em> <input type="number"  min="1" name="tiempo_disminucion" value='{$taller->tiempo_disminucion}' class='span2'>
    <select  class='span2' id='unidad_disminucion' name='unidad_disminucion'>
        <option value="m">minutos</option>    
        <option value="h">horas</option>    
        <option value="d">dias</option>    
    </select>  
    <br>
    <em>Utilizar este mismo porcentaje de disminución para el puntaje</em> <input type="checkbox"  {if $taller->disminucion_puntaje == 1} checked="checked" {/if}  name="disminucion_puntaje" id='disminucion_puntaje'>
</div>    




<br>
<center><button type='submit' class='btn btn-info' onclick="return formulario_opciones_avanzadas.validar();" ><i class='icon icon-save'></i> Guardar</button></center>

{Form::close()}


<script>


formulario_opciones_avanzadas = {
    
   validar : function(){    
         
      check1 = $("#tiene_inicio").prop('checked');
      check2 = $("#tiene_fin").prop('checked');
      
      f1 = $("#in-fecha-inicio").val();
      f2 = $("#in-fecha-fin").val();
      
      if(!check1 && !check2){ //se puso fecha para ambas
          
           
           if(f1==""){
                alert('La fecha de inicio es obligatoria');
                 $("#in-fecha-inicio").focus();
                 return false;
           }
           if(f2==""){
                alert('La fecha de finalización es obligatoria');
                 $("#in-fecha-fin").focus();
                 return false;
           }
           
           if(f2<=f1){
                alert('La fecha de finalización debe ser posterior a la de inicio');
                $("#in-fecha-inicio").focus();
                return false;
           }
      }else if(!check1){
            if(f1 == ""){
                   alert('La fecha de inicio es obligatoria');
                 $("#in-fecha-inicio").focus();
                 return false;
            }
      }else if(!check2){
                if(f2 == ""){
                       alert('La fecha de finalización es obligatoria');
                 $("#in-fecha-fin").focus();
                 return false;
                } 
      }
      
    return true;
    },
            
    set_fecha_inicio : function(){
        checked = $("#tiene_inicio").prop('checked');
       // window.alert(checked);
        if(checked){
           $("#fecha_inicio").hide();
           $("#in-fecha-inicio").val("");
        }else{
            $("#fecha_inicio").show();
        }
    },
    set_fecha_fin: function(){
        
          checked = $("#tiene_fin").prop('checked');
       // window.alert(checked);
        if(checked){
           $("#fecha_fin").hide();
            $("#in-fecha-fin").val("");
        }else{
            $("#fecha_fin").show();
        }
        
    },
    permitir_envios_tardios : function(){
        $("#div-envios-tardios").toggle();
    } 
}


$("#porcentaje_disminucion").val({$taller->porcentaje_disminucion});
$("#unidad_disminucion").val('{$taller->unidad_disminucion}');

</script>