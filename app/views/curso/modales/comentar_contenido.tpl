<!-- Modal para los comentarios -->


<!-- Modal -->
<div id="modal-comentarios-contenido" class="modal hide fade" style="width: 70%; left: 30% " >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel" class='pull-left'>Comentarios <span id="n-comentarios">(0)</span></h3>&nbsp;&nbsp;&nbsp; <button id='btn-comentar' class='btn-primary btn btn-mini'><i class='icon-comment icon-white'></i>  comentar</button>
        <div id='div-comentario' class="hide">
            <!--<form method="post" action="">-->
            <textarea id="area-comentario" style='width: 100%; resize: none' placeholder="Escriba su comentario aqui" ></textarea>
            <button  id="btn-crear-comentario" class='btn-mini btn-success pull-right'>comentar</button>
            <button id="btn-cancelar-comentario" class='btn btn-danger btn-mini pull-right'>cancelar</button>
            <input id="id-contenido" type="hidden" name="contenido"><br><br>
            <!--</form>-->    

        </div>    
    </div>
    <div id="" class="modal-body">
        <div id="no-comentarios" class=" hide alert alert-error">
            <p class="text-center">Este material no tiene comentarios</p>
        </div>    


        <table id="si-comentarios" class="table hide table-striped table-bordered table-condensed">
            <thead>
            <th>Usuario</th>
            <th>Comentario</th>
            <th>Fecha</th>
            <th></th>
            </thead>
            <tbody id="lista-comentarios">              
            </tbody>    
        </table>   
        <hr>
        <p class="text-center"><button id='mostrar-mas' onclick="comentarios_contenido.show_next()" class="btn">Mostrar mas <i class="icon-arrow-down"></i></button></p>

    </div>
    <div class="modal-footer">
        <button class="btn btn-warning" data-dismiss="modal"  aria-hidden="true">Cerrar</button>
        <!--<button class="btn btn-primary">Save changes</button>-->
    </div>
</div>
