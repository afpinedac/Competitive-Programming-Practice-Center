<?php

class Puntos extends LMSController {
    #funcion que checkea cuantos puntos se le debe dar a un usuario por resolver un ejercicio de un taller

    public static function ejercicio_taller($usuario, $curso, $envio) {

        $usuario = usuario::find($usuario);
        $envio = envio::find($envio);
        $taller = taller::find($envio->codigo);

        $envios = envio::get_envios($curso, $envio->ejercicio, $usuario->id, 0, $envio->codigo, 'asc'); #obtengo los envios de tipo ejercicios de taller, aqui se cambiaría el cero por $envio->tipo por si una evaluacion da puntos
        
        $accepteds = 0;
        $last = false;
        $n_envios = count($envios);
        for ($i = 0; $i < $n_envios; $i++) {
            if ($i == $n_envios - 1 && $envios[$i]->resultado == 'accepted') {
                $last = true;
            }
            if ($envios[$i]->resultado == 'accepted') {
                $accepteds++;
                if ($accepteds > 1)
                    break;
            }
        }



        #si solo tiene un accepted y este es el ultimo entonces lo tomo en cuenta
        $puntos = 0;
        if ($last && $accepteds == 1) {
            $puntos = max(static::$MAX_PUNTAJE_EJERCICIO - $n_envios, 15);



            ################# OPCIONES AVANZADAS ########################

            if ($taller->tiene_fin == 1 && strtotime($envio->created_at) > strtotime($envio->fecha_fin)) { #tiene fecha de fin  y el envio fue posterior
                if ($taller->envios_tardios == 1) { #permite envios tardios
                    if ($taller->disminucion_puntaje == 1) { #usa un porcentaje de disminucion para los puntos
                        $puntos = $this->puntos_con_disminucion($puntos, $envio->id);
                    }#else se le dan los puntos normalmente
                } else { # si el taller no permite envios tardíos
                    $puntos = 0;
                }
            }
            ################### FIN OPCIONES AVANZADAS ##################
        }
       
        $envio->puntos_obtenidos = $puntos;
        #se le da plata al usuario
     //   $usuario->sumar_plata($puntos * 2);

        $envio->save();
    }

    #funcion que reduce los puntos de une envio de acuerdo al retraso del envio

    private static function puntos_con_disminucion($puntos, $envio) {

        $taller = taller::find($envio->codigo);
        $envio = envio::find($envio);


        $segundos = strtotime(time()) - strtotime($envio->created_at);


        $unidad_disminucion = $taller->unidad_disminucion;
        $cuantos = 0;
        if ($unidad_disminucion == 'm') {
            $cuantos = $segundos / 60;
        } elseif ($unidad_disminucion == 'h') {
            $cuantos = $segundos / (3600);
        } else if ($unidad_disminucion == 'd') {
            $cuantos = $segundos / (86400);
        }

        $puntos = $puntos * ($taller->porcentaje_disminucion / (100 * ($cuantos + 1)));

        return $puntos;
    }
    
    
    
    
   public static function ejercicio_evaluacion($usuario, $curso, $envio) {

        $usuario = usuario::find($usuario);
        $envio = envio::find($envio);
        $evaluacion = evaluacion::find($envio->codigo);

        $envios = envio::get_envios($curso, $envio->ejercicio, $usuario->id, 1, $envio->codigo, 'asc'); #obtengo los envios de tipo ejercicios de taller, aqui se cambiaría el cero por $envio->tipo por si una evaluacion da puntos
        
 
        
        $accepteds = 0;
        $last = false;
        $n_envios = count($envios);
        for ($i = 0; $i < $n_envios; $i++) {
            if ($i == $n_envios - 1 && $envios[$i]->resultado == 'accepted') {
                $last = true;
            }
            if ($envios[$i]->resultado == 'accepted') {
                $accepteds++;
                if ($accepteds > 1)
                    break;
            }
        }

        #si solo tiene un accepted y este es el ultimo entonces lo tomo en cuenta
        $puntos = 0;
        $puesto_en_resolverlo = $evaluacion->get_posicion_en_ejercicio(Auth::user()->id, $envio->ejercicio);
        
        if ($last && $accepteds == 1) {
            $puntos = max(static::$MAX_PUNTAJE_EJERCICIO_EVALUACION - (5 * $puesto_en_resolverlo) -  $n_envios + 1, 15);
        }
        
        $envio->puntos_obtenidos = $puntos;
        #se le da plata al usuario

        $envio->save();
    }
    
    #funcion que retorna la posicion en que una persona obtiene un punto

    
    
    
}

?>
