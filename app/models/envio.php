<?php

class Envio extends Eloquent {

    protected $table = 'envio';
    protected $guarded = array();

    public static function get_envios($curso, $ejercicio, $usuario, $tipo = 0, $codigo = 0, $order = 'desc') {

        return DB::table('ejercicio')
                        ->select(DB::raw('nombre,lms_envio.created_at, lms_envio.resultado, lms_ejercicio.id , lms_envio.ejercicio, lms_envio.id as envio_id, lms_envio.tiempo_de_ejecucion'))
                        ->join('envio', 'ejercicio.id', '=', 'envio.ejercicio')
                        ->where('ejercicio.id', $ejercicio)
                        ->where('envio.usuario', $usuario)
                        ->where('envio.tipo', $tipo)
                        ->where('envio.curso', $curso)
                        ->where('envio.test', 0)
                        ->where('envio.codigo', $codigo)
                        ->orderBy('envio.id', $order)
                        ->get();
    }

    #funcion que retorna los ultimos n envios

    public static function get_ultimos_n_envios($n = 10) {
        
    }

    #funcion que retorna los envios hechos despues de t tiempo (t en segundos)

    public static function get_envios_despues_de_t($t) {
        $fecha = date("Y-m-d H:i:s", $t);
        return envio::where("created_at", '>', $fecha)->get();
    }

    #funcion que devuelve el veredicto de un envio que no haya sido visto

    public static function get_veredicto_no_visto($curso, $usuario) {

        return envio::where('curso', $curso)
                        ->where('usuario', $usuario)
                        ->where('visto', 0)
                        ->whereNotNull('resultado')
                        ->first();
    }

    #super funcion para sacar grafcia de envios
    #tipo  = 'estudiante' , s = 'estudiante' 

    public static function get_numero_envios($tipo, $s = null, $t = null) {

        if (in_array($tipo, array('estudiante', 'taller', 'ejercicioentaller', 'usuarioentaller'))) {

            if ($tipo == "estudiante") {
                $query = "SELECT date_format(created_at,'%M %d, %Y') as dia,count(*) nenvios FROM lms_envio WHERE test=0 AND usuario='{$s}' GROUP BY date_format(created_at,'%Y-%m-%d')";
            } else if ($tipo == "taller") {
                $query = "SELECT date_format(created_at,'%M %d, %Y') as dia,count(*) nenvios FROM lms_envio WHERE test=0 AND tipo=0 AND codigo='{$s}' GROUP BY date_format(created_at,'%Y-%m-%d')";
            } else if ($tipo == "ejercicioentaller") {
                $query = "SELECT date_format(created_at,'%M %d, %Y') as dia,count(*) nenvios FROM lms_envio WHERE test=0 AND tipo=0 AND codigo='{$s}' and ejercicio = '{$t}' GROUP BY date_format(created_at,'%Y-%m-%d')";
            } else if ($tipo == "usuarioentaller") {
                $query = "SELECT date_format(created_at,'%M %d, %Y') as dia,count(*) nenvios FROM lms_envio WHERE test=0 AND tipo=0 AND usuario='{$s}' and codigo = '{$t}' GROUP BY date_format(created_at,'%Y-%m-%d')";
            }






            //$stoday = date("F d, Y");


            $result = DB::select(DB::raw($query));

            $firstdate = $result[0] ? $result[0] : date('F d, Y');
            $lastdate = $result[0] ? $result[count($result) - 1] : date('F d, Y');


            //  var_dump($firstdate);
            // var_dump($lastdate);






            $diastotales = envio::getDatesFromRange(date('Y-m-d', strtotime($firstdate->dia)), date('Y-m-d'));

            $response = array();




            foreach ($diastotales as $dia) {

                $found = false;

                $r = array('dia' => date('F d, Y', strtotime($dia)), 'nenvios' => 0);
                $nenvios = 0;
                foreach ($result as $envio) {
                    if ($envio->dia == date('F d, Y', strtotime($dia))) {
                        $found = true;
                        $nenvios = $envio->nenvios;
                        break;
                    }
                }

                if ($found) {
                    $r['nenvios'] = $nenvios;
                }

                $response[] = (Object) $r;
            }

            //  var_dump($response);

            return $response;
        }
    }

    public static function getDatesFromRange($start, $end) {
        $dates = array($start);
        while (end($dates) <= $end) {
            $dates[] = date('Y-m-d', strtotime(end($dates) . ' +1 day'));
        }
        return $dates;
    }

    public function get_similares($porcentaje = 90) {

        $envios = envio::where('curso', $this->curso)
                ->where('id', '<', $this->id)
                ->where('ejercicio', $this->ejercicio)
                ->where('resultado', 'accepted')
                ->where('usuario', '<>', $this->usuario)
                ->get();
        $similares = array();
        foreach ($envios as $envio) {
            similar_text($this->algoritmo, $envio->algoritmo, $similitude);
            if ($similitude >= $porcentaje) {
                $similares[] = $envio->id;
            }
        }
        
        return $similares;
    }

}

?>
