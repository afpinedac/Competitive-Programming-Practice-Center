


    <div id="editar-evaluacion" style='width: 55%; left: 40%' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="alingCenter" id="tituloTipo"><i class='icon icon-plus-sign'></i> Nueva evaluación</h4>

        </div>
        <div class="modal-body">
            {Form::open(['action'=>'EvaluacionController@postEditar'])}

            <span class='badge badge-important'>1</span> <span class='lead'>Información General</span>  
            <br><br>
            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Nombre: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span12' type="text" id='ee_nombre' name="nombre" >
                </div>
            </div>

            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Descripción: *</label>
                </div>                
                <div class="span7">
                    <textarea name='descripcion' id='ee_descripcion' class='span12' required placeholder="Escriba la descripción"></textarea>
                </div>
            </div>
            
             <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Porcentaje aprobacion: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span2' id='ee_porcentaje' type="number" max="100" min="0" name="porcentaje_aprobacion" > <span>%</span>
                </div>
            </div>
             <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Fecha Activación: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span12' type="datetime-local" id='ee_fecha_activacion'  min="2013-01-01T00:00" name="fecha_activacion" >
                </div>
            </div>
             <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Duración: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span2' id='ee_duracion' type="number" name="duracion" > <span>mins.</span>
                </div>
            </div>
            <input type="hidden" name="evaluacion" id='ee_evaluacion'>
              

            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" onclick="return editar_evaluacion.validar()" class="btn btn-inverse"><i class='icon icon-upload-alt'></i> Actualizar</button>
            </div>
            {Form::close()}
        </div>

    </div>
        
        
         <script>
  editar_evaluacion = {
    evaluacion: null,
    editar: function(evaluacion) {
        editar_evaluacion.evaluacion = evaluacion;
        $.ajax({
            dataType: "json",
            type: 'post',
            url: "{url('evaluacion/infojson')}",
            data: {
                evaluacion: evaluacion
            },
            success: function(evaluacion) {              
              
                $("#ee_nombre").val(evaluacion.nombre);
                $("#ee_descripcion").text(evaluacion.descripcion);
                $("#ee_fecha_activacion").val(editar_evaluacion.formatear_fecha(evaluacion.fecha_activacion));
                $("#ee_duracion").val(evaluacion.duracion);
                $("#ee_porcentaje").val(evaluacion.porcentaje_aprobacion);
                
            }
        });
    },  
    formatear_fecha : function(fecha){    
           fecha =  fecha.split(" ");
            return fecha[0] + "T" + fecha[1];
   },
    
    validar : function(){
       $("#ee_evaluacion").val(editar_evaluacion.evaluacion);
    },            
    limpiar_formulario: function() {
        $("#editar_nombre_material").val("");
        $("#editar_descripcion_material").text("");
        $("#editar_url_material").val("");
        $("#editar_archivo_material").val("");
        $("#editar_archivo_material2").val("");
    }         
  

}

</script>
        

        
        
  