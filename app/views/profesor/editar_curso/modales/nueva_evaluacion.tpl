


    <div id="nueva-evaluacion" style='width: 55%; left: 40%' class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="alingCenter" id="tituloTipo"><i class='icon icon-plus-sign'></i> Nueva evaluación</h4>

        </div>
        <div class="modal-body">
            {Form::open(['action'=>'EvaluacionController@postCrear'])}

            <span class='badge badge-important'>1</span> <span class='lead'>Información General</span>  
            <br><br>
            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Nombre: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span12' type="text" name="nombre" >
                </div>
            </div>

            <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Descripción: *</label>
                </div>                
                <div class="span7">
                    <textarea name='descripcion' class='span12' required placeholder="Escriba la descripción"></textarea>
                </div>
            </div>
            
             <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Porcentaje aprobacion: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span2' type="number" max="100" min="0" name="porcentaje_aprobacion" > <span>%</span>
                </div>
            </div>
             <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Fecha Activación: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span12' type="datetime-local" min="01-01-2013 00:00" name="fecha_activacion" >
                </div>
            </div>
             <div class="row-fluid"> 
                <div class="span3">
                    <label class="pull-right">Duración: *</label>
                </div>                
                <div class="span7">
                    <input required  class='span2' type="number" name="duracion" > <span>mins.</span>
                </div>
            </div>
            
              

            <div class="modal-footer">
                <button class="btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-inverse"><i class='icon icon-plus-sign'></i> Crear</button>
            </div>
            {Form::close()}
        </div>

    </div>
        
        
  