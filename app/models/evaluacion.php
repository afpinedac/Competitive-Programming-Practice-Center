<?php

class Evaluacion extends Eloquent {

    protected $table = 'evaluacion';
    protected $guarded = array();

    public function get_ejercicios() {

        return DB::table('ejercicio')
                        ->join('ejercicio_x_evaluacion', 'ejercicio_x_evaluacion.ejercicio', '=', 'ejercicio.id')
                        ->where('ejercicio_x_evaluacion.evaluacion', $this->id)
                        ->get();
    }

    public function get_numero_ejercicios() {
        return ejercicioxevaluacion::where('evaluacion', $this->id)->count();
    }

    public function get_numero_de_ejercicios() {
        return DB::table('ejercicio_x_evaluacion')
                        ->where('evaluacion', $this->id)
                        ->count();
    }

    public function get_posibles_ejercicios() {

        $ejercicios = DB::table('ejercicio')
                ->whereNotIn('id', array_add(ejercicioxevaluacion::where('evaluacion', $this->id)
                                ->lists('ejercicio'), -1, -1))
                ->where('profesor', Auth::user()->id)
                ->get();
        return $ejercicios;
    }

    #retorna el ranking de una evaluacion

    public function get_ranking() {
        #la clasificacion es 
        #1. numero de ejercicios resueltos
        #2. numero de envios en la evaluacion        
        #3. el que hay enviado el primer ejercicio accepted
        #4. el menor tiempo de ejecucíon 

        $evaluacion = $this->id;
        $ranking = DB::select(
                        "SELECT u.usuario_id  as usuario, (SELECT count(DISTINCT ejercicio) FROM lms_envio e2 WHERE tipo = 1 AND codigo = {$evaluacion} AND e2.usuario = u.usuario_id AND e2.resultado = 'accepted') accepteds , (SELECT count(ejercicio) FROM lms_envio e2 WHERE tipo = 1 AND codigo = {$evaluacion} AND e2.usuario = u.usuario_id ) envios "
                        . " FROM lms_curso_x_usuario u LEFT JOIN lms_envio e ON (u.usuario_id = e.usuario)"
                        . " GROUP BY u.usuario_id"
                        . "  ORDER BY accepteds DESC, envios , MIN(e.created_at) DESC , u.usuario_id "
        );
        return $ranking;
    }

    #retorna el tiempo de finalizacion de una evaluacion en segundos

    public function get_time_fin() {
        return $this->get_time_ini() + $this->duracion * 60;
    }

    #retorna el tiempo de inicio de una evaluacion

    public function get_time_ini() {
        return strtotime($this->fecha_activacion);
    }

    #verifica si una evaluacion tiene un ejercicio

    public function tiene_ejercicio($ejercicio) {
        return DB::table('ejercicio_x_evaluacion')
                        ->where('ejercicio', $ejercicio)
                        ->where('evaluacion', $this->id)
                        ->count() == 1;
    }

    #si una evaluación ya termino

    public function termino() {
        return time() > $this->get_time_fin();
    }

    #verifica si se ha calculado los resultaso para un usuario

    public function ha_sido_calculado_para_usuario($usuario) {
        return DB::table('calculo_usuario_evaluacion')
                        ->where('usuario', $usuario)
                        ->where('evaluacion', $this->id)->count() == 1;
    }

}

?>
