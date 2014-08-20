<?php

class Taller extends Eloquent {

    protected $table = 'taller';
    protected $guarded = array();

    public  function get_ejercicios() {
        return DB::table('ejercicio')
                        ->join('ejercicio_x_taller', 'ejercicio_x_taller.ejercicio', '=', 'ejercicio.id')
                        ->where('ejercicio_x_taller.taller', $this->id)
                        ->get();
    }

    public  function get_numero_ejercicios() {
        return DB::table('ejercicio_x_taller')
                        ->where('taller', $this->id)
                        ->count();
    }

    
    
    #validaciones
    
    public function tiene_ejercicio($ejercicio) {
        return DB::table('ejercicio_x_taller')
                ->where('taller',$this->id)
                ->where('ejercicio',$ejercicio)
                ->count()==1;
            }
    
}

?>
