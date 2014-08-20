<?php

class Ejercicio extends Eloquent {

    protected $table = 'ejercicio';
    protected $guarded = array();

    
    
    
    #verifica si un ejercicio esta resuelto por un usuario en un curso
    public  function esta_resuelto($usuario,$tipo ,$codigo){
        return 
        DB::table('envio')
                ->where('usuario',$usuario)                
                ->where('ejercicio',$this->id)
                ->where('tipo',$tipo)
                ->where('codigo',$codigo)
                ->where('resultado','accepted')
                ->where('test',0)
                ->count() > 0 ;
    }
    
   
    #retorna el tipo de entrada que tiene en un taller
    public function get_tipo_entrada_en_taller($taller){
        return DB::table('ejercicio_x_taller')
                ->where('ejercicio',$this->id)
                ->where('taller',$taller)
                ->first()
                ->tipo_entrada;
    }
    
    
    #numero de estudiantes que han resuelto el ejercicio en un taller
    public function get_numero_estudiantes_resolvieron_en_modulo($tipo,$codigo){
        
        $x =  DB::table('envio')
                ->select(DB::raw('count(distinct usuario, ejercicio) as resuelto'))
                ->where('codigo', $codigo)
                ->where('ejercicio', $this->id)
                ->where('resultado','accepted')
                ->where('test',0)
                ->where('tipo',$tipo)
                ->groupBy('ejercicio' , 'usuario')
                ->get();      
        
        
        return count($x);   
        
        
    }
    
    
    #numero de veredictos en un taller (tipo hace refencia a 0 taller, 1 evaluacion)
    public function get_numero_respuestas_en_modulo($curso, $tipo, $codigo,$resultado){
        
        return DB::table('envio')
                ->where('tipo', $tipo)
                ->where('codigo', $codigo)
                ->where('resultado', $resultado)
                ->where('curso', $curso)
                ->where('ejercicio', $this->id)
                ->count();
        
        
    }
    
    
    #numero de envios en un taller de un curso
    public function get_numero_envios_en_modulo($tipo, $codigo){
        
        return DB::table('envio')
                ->where('tipo', $tipo)
                ->where('codigo', $codigo)
                ->where('ejercicio', $this->id)
                ->count();
        
        
                
    }
 
    
    #número de veces resuelto
    public function get_numero_veces_resuelto($tipo,$codigo,$ejercicio){
        return DB::table('envio')
                ->where('tipo',$tipo)
                ->where('ejercicio',$ejercicio)
                ->where('codigo',$codigo)
                ->where('resultado','accepted')
                ->count();
    }
    
    
    
}

?>
