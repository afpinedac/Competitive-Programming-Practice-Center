<?php

class Juez extends LMSController {
    #funcion que checkea si los envios generan logros

    public static function evaluar_envios($curso, $user = null) {

      
   
      
        $usuario = usuario::find($user == null ? Auth::user()->id : $user);
        $envios = $usuario->get_envios_evaluados_en_curso($curso);



        foreach ($envios as $envio) {
            if ($envio->tipo == 0 && $envio->resultado == 'accepted') { #si es un envio de un taller              
                Logros::taller($usuario->id, $curso);
                Puntos::ejercicio_taller($usuario->id, $curso, $envio->id);
            }else if ($envio->tipo == 1 && $envio->resultado == 'accepted') { #si es un envio de una evaluacion
              Puntos::ejercicio_evaluacion($usuario->id, $curso, $envio->id);
            }

            #se elimina el envio evaluado
            DB::table('envio_evaluado')
                    ->where('envio', $envio->id)
                    ->delete();
        }
    }

}
