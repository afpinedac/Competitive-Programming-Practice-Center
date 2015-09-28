<?php

//all the controllers must to override this methods
interface CRUD {
    public function postRegistrar();
    public function postEliminar();
    public function postEditar();
}

?>
