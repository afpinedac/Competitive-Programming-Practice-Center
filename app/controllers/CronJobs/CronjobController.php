<?php

class CronjobController extends BaseController {

    #llamar cuando se quiera hacer un bk especializado para cada usuario
    public function getBkBdEspecializado() {
        
    }

    #llamar cuando se quiera hacer un bk completo de la bd
    public function getBkBd() {
        
    }

    #llamar cuando se quiera eliminar los datos de la tabla formulario_consulta_editable
    public function getDeleteFormularioConsulta() {
        
    }
    
    
    public function getTest(){
        
        $backup = new BackUp();
     //   $backup->test();
    }

}
