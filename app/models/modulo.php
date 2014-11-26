<?php

class Modulo extends Eloquent {

    protected $table = 'modulo';
    protected $guarded = array();

    public function get_ejercicios() {
        $ejercicios = DB::table('ejercicio')
                ->join('ejercicio_x_taller', 'ejercicio_x_taller.ejercicio', '=', 'ejercicio.id')
                ->where('ejercicio_x_taller.taller', $this->id)
                ->orderBy('prioridad')
                ->get();
        return $ejercicios;
    }

    # Retorna los ejercicios que no estan en el modulo actual creados por un profesor

    public function get_otros_ejercicios($profesor) {


        $ejercicios = DB::table('ejercicio')
                ->whereNotIn('id', array_add(ejercicioxtaller::where('taller', $this->id)
                                ->lists('ejercicio'), -1, -1))
                ->where('profesor',$profesor)
                ->get();
        return $ejercicios;
    }

    
    #materiales subidos por el profesor
    public function get_materiales() {

        return contenido::where('modulo', $this->id)
                        ->get();
    }

    public function get_evaluaciones() {
        return evaluacion::where('modulo', $this->id)
                ->orderBy('fecha_activacion', 'desc')->get();
    }

    #recursos subidos por los estudiantes
    public function get_recursos() {
        return DB::table('usuario')
                        ->join('recurso', 'recurso.usuario', '=', 'usuario.id')
                        ->where('recurso.modulo', $this->id)
                        ->get();
    }

    #devuelve el ejercicio perteneciente a un modulo
    public function get_ejercicio($ejercicio) {

        return DB::table('ejercicio')
                        ->join('ejercicio_x_taller', 'ejercicio_x_taller.ejercicio', '=', 'ejercicio.id')
                        ->where('ejercicio_x_taller.taller', $this->id)
                        ->where('ejercicio_x_taller.ejercicio', $ejercicio)
                        ->first();
    }
    
    
    
    #devuelve la evaluacion perteneciente a una evaluacion
    public function get_evaluacion($evaluacion) {

        return DB::table('modulo')
                        ->join('evaluacion', 'evaluacion.modulo', '=', 'modulo.id')
                        ->where('evaluacion.id', $evaluacion)
                        ->where('modulo.id', $this->id)                        
                        ->first();
    }

    //verifica si el modulo 'a' es pre-requisito del modulo 'b' 
    public static function es_pre_requisito($a, $b) {

        return modulorequisito::where('modulo', $b)->where('requisito', $a)->count() == 1;
    }

    public function esta_desbloqueado() {

        $unlock = true;

        $pre = DB::table('modulo_requisito')->where('modulo', $this->id)->lists('requisito');
        foreach ($pre as $p) {
            if (modulo::find($p)->minimo_para_desbloquear > modulo::find($p)->get_numero_ejercicios_realizados($this->curso, Auth::user()->id))
                return false;
        }

        return true ;
    }

    public function get_numero_ejercicios() {
        return DB::table('ejercicio_x_taller')
                        ->where('taller', $this->id)
                        ->count();
    }
    
    #tiene ejercicios
    
      public function tiene_ejercicios(){
          return $this->get_numero_ejercicios()>0;
      }

    public function get_numero_ejercicios_realizados($curso, $usuario) {

        $solved = DB::table('envio')
                ->where('usuario', $usuario)
                ->where('curso', $curso)
                ->where('resultado', 'accepted')
                ->where('test',0)
                ->where('tipo', 0)
                ->where('codigo', $this->id)
                ->lists('ejercicio');

        $solved = array_add($solved, -1, -1);



        return DB::table('ejercicio_x_taller')
                        ->where('taller', $this->id)
                        ->whereIn('ejercicio_x_taller.ejercicio', $solved)
                        ->count();
    }

    public function get_pre_requisitos() {

        $pre = DB::table('modulo')
                ->join('modulo_requisito', 'modulo.id', '=', 'modulo_requisito.requisito')
                ->where('modulo_requisito.modulo', $this->id)
                ->get();
        return $pre;
    }

    public function get_numero_minimo_para_desbloquear() {
        return modulo::find($this->id)->minimo_para_desbloquear;
    }

    public function get_numero_evaluaciones() {
        return evaluacion::where('modulo', $this->id)->count();
    }

}
