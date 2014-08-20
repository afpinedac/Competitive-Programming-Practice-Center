{capture assign='content'}


    <div class="row-fluid">
        <div class="span12">
            
            
            
            <div class="span3">
                {assign var=tab2 value='ejercicios'}
  {include file='./components/lista_modulos.tpl'}
            </div>

            <div class="span9">

         
    <div class="row-fluid">
        <div class="span12">

            <h3 ><i class='icon icon-cogs '></i> Mi lista de ejercicios creados</h3>        
    
            <div class="">
                        <a class='btn btn-success pull-right' href='#nuevo-ejercicio' data-toggle='modal' onclick="nuevo_ejercicio.reestablecer_formulario()"><i class='icon icon-plus'></i> Crear nuevo ejercicio</a>
            </div>    
            <br>

                        
                                            
         
            
            
            {if count($ejercicios)>0}
                
                <hr>
                <table class="table table-striped table-condensed table-bordered">
                    <thead>
                    <th>Nombre</th>
                    <th>Fecha de última modificación</th>                    
                                 
                                      
                    <th>Opciones</th>
                    </thead>
                    <tbody>
                        {foreach $ejercicios as $ejercicio}
                            
                        <tr>
                            <td>{$ejercicio->nombre}  {if $ejercicio->tipo_formulacion==1}  <a target='_blank' href='{url('ejercicio/descargar-formulacion')}/{LMSController::encoder($ejercicio->id)}'><img src='{url('img/general/pdf.jpg')}'></a> {/if}</td>
                            <td>{$ejercicio->updated_at}</td>
                           
                         
                            
                            <td>
                                <a href="{url('curso/ver')}/{$curso->id}/editar/lista-ejercicios/{$ejercicio->id}"><i class='icon icon-edit'></i></a> 
                                <a href="{url('ejercicio/eliminar')}/{$ejercicio->id}" onclick="return confirm('Si elimina este ejercicio, se eliminara de todos los talleres y evaluaciones donde este se encuentre. ¿Está seguro?')"><i class='icon icon-trash'></i></a>
                            </td>
                        </tr>
                        {/foreach}                      
                    </tbody>    
                </table>    
                
            {else}
                
                <div class="alert alert-block">
                    <center><p>
                        Usted no ha creado ningún ejercicio
                        </p>
                    </center>
                </div>    

                
            {/if}
            
            
            
        </div>
    </div>
    
    
                  </div>
    </div>
    </div>
    
   {*modal de creación de nuevo ejercicio*}
   {include file='./modales/nuevo_ejercicio.tpl'}

{/capture}   


{include file='_templates/template.tpl' layout='curso' tab='edicion' }
