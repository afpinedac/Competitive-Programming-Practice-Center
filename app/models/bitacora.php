<?php

class Bitacora extends Eloquent {

    protected $table = 'bitacora';
    protected $guarded = array();
    public $timestamps = false;

    public function update_ultima_vista() {
        $this->update(array('fecha_salida' => date('Y-m-d H:i:s')));
    }

}

