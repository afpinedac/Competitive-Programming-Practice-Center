 <div id="nuevo-material" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="alingCenter" id="tituloTipo"><i class='icon icon-upload'></i> Subir Material</h4>

    </div>
    <div class="modal-body">
        {Form::open(['action'=>'ContenidoController@postCrear', 'files'=>true ])}
           
            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Nombre: *</label>
                </div>                
                <div class="span7">
                    <input required  id='nombre_material' class='span12' type="text" name="nombre" >
                </div>
            </div>

            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Descripción: *</label>
                </div>                
                <div class="span7">
                    <textarea name='descripcion' id='descripcion_material' class='span12' required placeholder="Escriba la descripción"></textarea>
                </div>
            </div>
            
           <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">URL:</label>
                </div>                
                <div class="span7">
                    <input  name='enlace' id="url_material" type="url"  name="nombre" class='span12' >
                </div>
            </div>
        
            <div class="row-fluid" > 
                <div class="span3">
                    <label class="pull-right">Archivo:</label>
                </div>                
                <div class="span7">
                    <input  type="file" id='archivo_material'  name="archivo">
                </div>
            </div>


            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" onclick="return validar_material();" class="btn btn-inverse"><i class='icon icon-upload-alt'></i> Subir</button>
            </div>
        {Form::close()}
    </div>

</div>
    
    <script>        
       function validar_material(){            
                if($("#archivo_material").val() == "" && $("#url_material").val() == ""){
                        alertify.alert('Debes ingresar una URL o subir un archivo');
                        $("#url_material").focus();
                        return false;
                } 
            return true;
        }       
        
    </script>