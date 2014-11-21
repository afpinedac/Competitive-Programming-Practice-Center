<?php


class Logro  extends Eloquent{
      protected $table = 'logro';
    protected $guarded = array();
    
    
    
    #función que retorna la informacion de un logro a partir de un código 
    #dado por el curso_x_logro_x_usuario
    public static function get_info_logro($codigo){
        
        $logro = DB::table('curso_x_logro_x_usuario')
                ->where('id',$codigo)->first();
        
        return logro::find($logro->logro);
        
    }
    
    
    
    
    
}

?>
