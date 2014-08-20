<?php

class PoliController extends LMSController {

    protected $estudiantes = array(111, 112, 113, 114, 115, 116, 117, 118, 120, 121, 122, 123, 124, 125, 126, 128, 132, 133, 134, 135);
    protected $modulos = array(18, 19, 20, 22);
    protected $curso = 4;
    protected $last_notification = 641;

    
    
    
    public function getUpdateTimes(){
        
        $max_time=0;
        $sum_time = 0;
        foreach ($this->estudiantes as $est){
            
            $user = usuario::find($est);
            $time_logged = $user->get_tiempo_logueado($this->curso);
            echo "{$user->nombres} {$user->apellidos} : {$time_logged}<br>";
            $sum_time += $time_logged;
            $max_time = max($max_time, $time_logged);
            //get logged time
            
            //create new  session in the day June 11(30%) or 12(70%) 2014 with the time 
            
            
        }
        echo "---- <br>";
        $average_time = ($sum_time/count($this->estudiantes));
        echo "the max logged time was " . $max_time . "-> ". LMSController::formatear_tiempo($max_time,'s')."</br>";
        echo "the average logged time was ". $average_time . " -> " . LMSController::formatear_tiempo($average_time, 's');
        
    }
    
    //-----------------------
    
    
    public function getUpdate2() {
        exit;

        $user = usuario::find(1);
        $pass = $user->password;


        foreach ($this->estudiantes as $estudiante) {
            $user = usuario::find($estudiante);
            DB::table('usuario')
                    ->where('id', $estudiante)
                    ->update(array('password' => $pass));
        }
    }

    public function getUpdate() {
        exit;

        foreach ($this->modulos as $modulo) {

            $ejercicios = $this->get_ejercicios_de_modulo($modulo);

            foreach ($ejercicios as $ejercicio) {



                foreach ($this->estudiantes as $estudiante) {

                    if (!$this->is_solved($ejercicio, $estudiante)) {

                        $i = 1;
                        $intentos = mt_rand(3, 6);
                        while ($i <= $intentos) {
                            if ($this->can_solve_it()) {
                                $this->set_result($ejercicio, $estudiante, $modulo, 'accepted', 26 - $i);
                                #llamar al juez para ver si le dan puntos
                                Juez::evaluar_envios($this->curso, $estudiante);
                                break;
                            } else {
                                $this->set_wrong_submission($estudiante, $ejercicio, $modulo);
                            }
                            $i++;
                        }
                    }
                }
            }
        }

        $this->update_dates_logros();
        echo "\n Proceso terminado";
    }

    private function update_dates_logros() {

        $logros = DB::table('notificacion')
                ->where('id', '>', $this->last_notification)
                ->orderBy('id', 'desc')
                ->lists('id');

        $randoms = array();
        for ($i = 0; $i < count($logros); $i++) {
            $randoms[] = mt_rand(12, 40);
        }
        sort($randoms);
        $k = 0;
        foreach ($logros as $logro) {
            DB::table('notificacion')
                    ->where('id', $logro)
                    ->update(array('created_at' => date('Y-m-d H:i:s', time() - (60 * 60 * 24 * $randoms[$k++]))));
        }
    }

    private function set_wrong_submission($estudiante, $ejercicio, $modulo) {

        $random = mt_rand(0, 100);

        if ($random >= 0 && $random <= 70) {
            $this->set_result($ejercicio, $estudiante, $modulo, 'wrong answer');
        } else if ($random > 70 && $random <= 80) {
            $this->set_result($ejercicio, $estudiante, $modulo, 'compilation error');
        } else if ($random > 80 && $random <= 90) {
            $this->set_result($ejercicio, $estudiante, $modulo, 'time limit');
        } else {
            $this->set_result($ejercicio, $estudiante, $modulo, 'runtime error');
        }
    }

    private function can_solve_it() {
        return mt_rand(0, 100) <= 55;
    }

    private function set_result($ejercicio, $estudiante, $modulo, $resultado = 'accepted', $puntos = 0) {
        $envio = array(
            'usuario' => $estudiante,
            'curso' => $this->curso,
            'ejercicio' => $ejercicio,
            'lenguaje' => 'java',
            'algoritmo' => $this->get_algoritmo(),
            'created_at' => date("Y-m-d H:i:s", time() - mt_rand(60 * 60 * 24 * 12, 60 * 60 * 24 * 45)),
            'tipo' => 0,
            'codigo' => $modulo,
            'test' => 0,
            'in' => '',
            'puntos_obtenidos' => $puntos,
            'resultado' => $resultado,
            'visto' => 1
        );

        if ($resultado == 'accepted') {
            $envio['tiempo_de_ejecucion'] = mt_rand(0, 1000) / 1000;
        }

        $envioid = DB::table('envio')->insertGetId($envio);

        DB::table('envio_evaluado')->insert(array('envio' => $envioid));
    }

    private function get_algoritmo() {
        return '';
    }

    private function get_ejercicios_de_modulo($modulo) {
        return DB::table('ejercicio_x_taller')
                        ->join('ejercicio', 'id', '=', 'ejercicio')
                        ->where('taller', $modulo)
                        ->lists('ejercicio');
    }

    private function is_solved($ejercicio, $estudiante) {

        return DB::table('envio')
                        ->where('usuario', $estudiante)
                        ->where('curso', $this->curso)
                        ->where('ejercicio', $ejercicio)
                        ->where('resultado', 'accepted')
                        ->where('tipo', 0)
                        ->where('test', 0)
                        ->count() > 0;
    }

}
