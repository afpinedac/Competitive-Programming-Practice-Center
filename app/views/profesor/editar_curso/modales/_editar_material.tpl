 <div id="editar-material" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="alingCenter" id="tituloTipo"><i class='icon icon-upload'></i> Editar Material</h4>

    </div>
    <div class="modal-body">
        {Form::open(['action'=>'ContenidoController@postEditar', 'files'=>true ])}
        
            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Nombre: *</label>
                </div>                
                <div class="span7">
                    <input required  id='editar_nombre_material' class='span12' type="text" name="nombre" >
                </div>
            </div>

            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Descripción: *</label>
                </div>                
                <div class="span7">
                    <textarea name='descripcion' id='editar_descripcion_material' class='span12' required placeholder="Escriba la descripción"></textarea>
                </div>
            </div>
        <input type="hidden" id='editar_contenido' name="contenido">    
           <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">URL:</label>
                </div>                
                <div class="span7">
                    <input  name='enlace' id="editar_url_material" type="url"  name="enlace" class='span12' >
                </div>
            </div>
        
        
          <div class="row-fluid hide" id='archivo_actual' > 
                <div class="span3">
                    <label class="pull-right">Archivo actual:</label>
                </div>                
                <div class="span7">
                    <a id='editar_archivo_material' href=''></a> &nbsp;&nbsp;&nbsp;&nbsp; <i class='icon icon-remove'></i> <a href='#' onclick="editar_material.eliminar_archivo()"><span class=''>Eliminar</span></a>
                </div>
            </div>
        
            <div class="row-fluid" > 
                <div class="span3">
                    <label class="pull-right">Nuevo Archivo:</label>
                </div>                
                <div class="span7">
                    <input  type="file" id='editar_archivo_material2'  name="archivo">
                </div>
            </div>


            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" onclick="return editar_material.validar();" class="btn btn-inverse"><i class='icon icon-upload-alt'></i> Actualizar</button>
            </div>
        {Form::close()}
    </div>

</div>
        
        <script>
  editar_material = {
    material: null,
    editar: function(mat) {
        editar_material.material = mat;
        $.ajax({
            dataType: "json",
            type: 'post',
            url: "{url('contenido/infojson')}",
            data: {
                material: mat
            },
            success: function(data) {

            //    window.console.log(data);

                $("#editar_nombre_material").val(data.nombre);
                $("#editar_descripcion_material").val(data.descripcion);
                $("#editar_url_material").val(data.enlace);
                if (data.archivo != "") {
                    $("#editar_archivo_material").text(data.archivo);
                    $("#archivo_actual").show();
                    $("#editar_archivo_material").attr('href', "{url('contenido/descargar/')}" + '/' + data.id);
                } else {
                    $("#archivo_actual").hide();
                }


            }
        });
    },
    validar: function() {


     //   window.console.log($("#editar_url_material").val());
      //  window.console.log($("#editar_archivo_material").text());
      //  window.console.log($("#editar_archivo_material2").val());


        if ($("#editar_url_material").val() == "" && $("#editar_archivo_material").text() == "" && $("#editar_archivo_material2").val() == "") {
            alertify.alert('Debe ingresar una URL o subir un archivo');
            return false;
        }
        
       $("#editar_contenido").val(editar_material.material);

        return true;
    }
    ,
    limpiar_formulario: function() {
        $("#editar_nombre_material").val("");
        $("#editar_descripcion_material").text("");
        $("#editar_url_material").val("");
        $("#editar_archivo_material").val("");
        $("#editar_archivo_material2").val("");
    },            
    eliminar_archivo: function() {  
    
       if(lms.confirmar()){
        $.ajax({
            dataType: "",
            type: 'post',
            url: "{url('contenido/eliminararchivo')}" + '/' + editar_material.material,
            data: {
                material : editar_material.material                
            },
            success: function(data) {
                if(data === "1"){
                        $("#editar_archivo_material").text("");                
                        $("#archivo_actual").hide();
                }else{
                    alertify.alert('Ha ocurrido un error');
                   // window.console.log(data);                    
                }
            }
        });
        }



    }

}

</script>
        
