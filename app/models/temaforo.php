<?php

class Temaforo extends Eloquent {

    protected $table = 'tema_foro';
    protected $guarded = array();

    public function get_respuestas() {
        return DB::table('usuario')
                 ->join('respuesta_foro' , 'usuario.id', '=', 'respuesta_foro.usuario')
                        ->where('tema_foro', $this->id)
                        ->orderBy('created_at','desc')
                        ->get();
    }
    
    
    public static function get_temas($curso){
        
        return DB::table('usuario')
                ->join('tema_foro' , 'usuario.id', '=' , 'tema_foro.usuario')
                ->where('curso',$curso)
                ->orderby('tema_foro.id','desc')
                ->get();
        
    }
    
    
    public function get_numero_de_respuestas(){
        return respuestaforo::where('tema_foro', $this->id)->count();
               
    }
    
    
    public static function get_tema($tema){
       return DB::table('usuario')
                ->join('tema_foro' , 'usuario.id', '=' , 'tema_foro.usuario')
                ->where('tema_foro.id', $tema)
                ->first();
        
    }
    
    public function get_participantes(){
        return DB::table('respuesta_foro')
                ->where('tema_foro',$this->id)
                ->get();
    }

}

?>
