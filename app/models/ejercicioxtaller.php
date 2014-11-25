<?php

class EjercicioxTaller extends Eloquent {

    protected $table = 'ejercicio_x_taller';
    protected $guarded = array();

    #retorna las soluciones de un ejercicio en un taller

    public static function get_soluciones($ejercicio, $taller) {
        return DB::table('envio')
                        ->where('envio.ejercicio', $ejercicio)
                        ->where('envio.codigo', $taller)
                        ->where('envio.resultado', 'accepted')
                        ->where('test', 0)
                        ->where('usuario','<>',1)
                        ->orderBy('envio.id', 'desc')
                        ->get();
    }

    #retorna la maxima prioridad en un curso

    public static function get_maxima_prioridad($taller) {

        $max = ejercicioxtaller::where('taller', $taller)
                ->max('prioridad');

        return $max ? $max : 0;
    }

    #retorna el ejercicio con la siguiente prioridad mas baja que e1

    public static function buscar_ejercicio_siguiente_prioridad($taller, $ejercicio, $tipo = '<') {

        $ejercicio = ejercicioxtaller::where('taller', $taller)
                        ->where('ejercicio', $ejercicio)->first();



        $ejercicio = ejercicioxtaller::where('taller', $taller)
                ->where('prioridad', $tipo, $ejercicio->prioridad)
                ->orderBy('prioridad', $tipo == '<' ? 'desc' : 'asc')
                ->first();



        return $ejercicio ? $ejercicio->ejercicio : null;
    }

    #cambia las prioridades de 2 ejercicios

    public static function cambiar_prioridad($taller, $e1, $e2) {

        #guardamos la prioridad del ejercicio e1
        $prioridad = ejercicioxtaller::where('taller', $taller)->where('ejercicio', $e1)->first()->prioridad;


        #ponemos la prioridad de e2 en e1

        DB::table("ejercicio_x_taller")->where('taller', $taller)
                ->where('ejercicio', $e1)
                ->update(
                        array('prioridad' => ejercicioxtaller::where('taller', $taller)
                            ->where('ejercicio', $e2)->first()->prioridad
                        )
        );


        #ponemos la prioridad aux en e2

        DB::table("ejercicio_x_taller")->where('taller', $taller)
                ->where('ejercicio', $e2)
                ->update(array('prioridad' => $prioridad)
        );
    }

    public static function get_tipo_entrada($ejercicio, $taller) {
        DB::table('ejercicio_x_taller')
                        ->where('ejercicio', $ejercicio)
                        ->where('taller', $taller)
                        ->first()
                ->tipo_entrada;
    }

}

?>
