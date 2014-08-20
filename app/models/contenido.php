<?php

class Contenido extends Eloquent {

    protected $table = 'contenido';
    protected $guarded = array();

    public  function numero_de_valoraciones() {


        return DB::table('valoracion_contenido')
                        ->where('contenido', $this->id)
                        ->count();
    }

    public  function get_promedio_valoracion() {
        $suma = DB::table('valoracion_contenido')
                ->where('contenido', $this->id)
                ->sum('puntuacion');
        $total = $this->numero_de_valoraciones();


        return $total == 0 ? 0 : floor($suma / $total);
    }

    public function get_comentarios() {
        return DB::table('comentario_contenido')
                     ->select(DB::raw('nombres, apellidos, lms_usuario.id as usuario_id, comentario, fecha, lms_comentario_contenido.id as comentario_id'))
                  ->join('usuario', 'usuario.id','=','comentario_contenido.usuario')
                        ->where('contenido', $this->id)
                        ->orderBy('comentario_contenido.id','desc')
                        ->get();
    }
    
    public  function numero_de_comentarios(){
        return DB::table('comentario_contenido')    
                ->where('contenido',$this->id)
                ->count();
    }

}