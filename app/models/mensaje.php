<?php


class Mensaje extends Eloquent{
          protected $table = 'mensaje';
    protected $guarded = array();
    
   
    
    
    public static function numero_mensajes_anidados($mensaje){
        $c = 1;
        
        $n = static::get_mensaje_hijo($mensaje);
        
        
        while($n != null){
            $c++;
            $n=mensaje::get_mensaje_hijo($n);
        }
        return $c;
        
    }
    
    
    
    public static function get_mensaje_hijo($mensaje){
        return mensaje::where('respuesta_a',$mensaje)->first()->id;
    }
    
    
}

?>
