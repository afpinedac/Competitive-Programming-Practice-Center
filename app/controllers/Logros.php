<?php

class Logros extends LMSController {
    #TODO: verificar todos los logros-prerequisitos y parametros
    //TROFEOS
#pre-requisitos y parametros para los logros

    public static $logros = array(
        1 => array(
            'min_num_estudiantes' => 5, #minimo numero de estudiantes para poder hacer ranking por fechas
            'min_num_ejercicios' => 10, #minimo numero de ejercicios que debe resolver para contar con los porcentajes en el curso 
        ),
        2 => array(
            'min_estudiantes' => 3, #minimo numero de estudiantes que deben haber para considerar la medallería
            'min_porcentaje_ganar' => 60, #porcentaje para considerar que un estudiante gano una evaluacion
            'min_num_evaluaciones' => 3, #numero de evaluaciones minimas que deben haber para considerar que gano (puede ser cambiada por el numero de módulos)
        ),
        4 => array(
            'a' => 20, #parametro de la formula 20log(#estudiantes) a = 20  
            'num_publicaciones' => 5 //(10) #publicaciones que debe publicar para darle el premio
        ),
        250 => array(
            'numero_participaciones_en_foro' => 10,
            'numero_de_comentarios_en_notificaciones' => 10,
            'tiempo_logueo_en_minutos' => 1440
        ), //logros de tiempo
        'tiempo' => array(
            '1' => 600, //300
            '2' => 1200, //600
            '3' => 2400, //1200
        )
    );

    ######## EJERCICIO #############
    #100 - 1 Ejercicio
    #101 - Cumplir el 25%
    #102 - Cumplir el 50%
    #103 - Cumplir el 75%
    #104 - Cumplir el 100%
    #105 - Resolver 3 ejercicios en el primer intento
    #106 - Estar en el top 1   (pendiente)
    #107 - Estar en el top 5   (pendiente)
    #108 - Desbloquear todos los modulos

    public static function taller($usuario, $curso) {


        static::check100($usuario, $curso);
        static::check101($usuario, $curso);
        static::check102($usuario, $curso);
        static::check103($usuario, $curso);
        static::check104($usuario, $curso);
        static::check105($usuario, $curso);
        //static::check107($usuario, $curso);
        // static::check108($usuario, $curso);
        static::check108($usuario, $curso);
    }

    ###################  OTROS LOGROS ########################
    #start in 250 otro tipo de logros
    #250 - Comentar en el foro
    #251 - Comentar en las notificaciones
    #25234 - Logueo(time)
    #funcion que le da un logro en caso que el usuario comente 10 veces en el foro

    public static function check250($usuario, $curso) {
        $usuario = usuario::find($usuario);

        if ($usuario && !$usuario->tiene_logro($curso, 250)) {

            $ncomentarios = $usuario->get_numero_de_participaciones_en_foro($curso);

            if ($ncomentarios >= static::$logros['250']['numero_participaciones_en_foro']) {
                $code = static::crear_logro(250, $usuario->id, $curso, 4);
                #se crea la notificación
                static::crear_notificacion($usuario->id, $curso, 4, $code);
            }
        }
    }

    #funcion que le da un logro en caso que el usuario comente 10 notificaciones

    public static function check251($usuario, $curso) {
        $usuario = usuario::find($usuario);
        if ($usuario && !$usuario->tiene_logro($curso, 251)) {
            $ncomentarios = $usuario->get_numero_comentarios_en_notificaciones($curso);

            if ($ncomentarios >= static::$logros['250']['numero_de_comentarios_en_notificaciones']) {
                $code = static::crear_logro(251, $usuario->id, $curso, 4);
                #se crea la notificación
                static::crear_notificacion($usuario->id, $curso, 4, $code);
            }
        }
    }

    #2 = 5 horas
    #3 = 10 horas
    #4 = 20 horas

    public static function check25234($usuario, $curso) {
        $usuario = usuario::find($usuario);
        if ($usuario) {
            $tiempologueado = $usuario->get_tiempo_logueado($curso) / 60;
            if (!$usuario->tiene_logro($curso, 252) && $tiempologueado >= self::$logros['tiempo']['1']) {
                $code = static::crear_logro(252, $usuario->id, $curso, 4);
                #se crea la notificación
                static::crear_notificacion($usuario->id, $curso, 4, $code);
            }
            if (!$usuario->tiene_logro($curso, 253) && $tiempologueado >= self::$logros['tiempo']['2']) {
                $code = static::crear_logro(253, $usuario->id, $curso, 4);
                #se crea la notificación
                static::crear_notificacion($usuario->id, $curso, 4, $code);
            }
            if (!$usuario->tiene_logro($curso, 254) && $tiempologueado >= self::$logros['tiempo']['3']) {
                $code = static::crear_logro(254, $usuario->id, $curso, 4);
                #se crea la notificación
                static::crear_notificacion($usuario->id, $curso, 4, $code);
            }
        }
        // exit;
    }

    ############# FIN DE OTROS LOGROS #######################3
    ######### EVALUACION ###############
    #300 - Ganar una evaluación
    #301 - Ganar todas las evaluaciones
    #302 - Sacar un 5 (con cardinalidad)
    #303 - Medalleria (Oro) (Cardinalidad)
    #304 - Medalleria (Plata) (Cardinalidad)
    #305 - Medalleria (Bronce) (Cardinalidad)

    public static function evaluacion($usuario, $evaluacion) {
        #se evaluan todos los logros de las evaluaciones
        static::check300($usuario, $evaluacion);
        static::check301($usuario, $evaluacion);
        static::check302($usuario, $evaluacion);
        static::check30345($usuario, $evaluacion);
    }

    ######## REDES SOCIALES ###############
    #400- obtener alogbx likes en un curso
    #401- compartir N publicaciones en facebook
    #402- compartir N publicaciones en twitter
    #403- Cambiar el avatar (publica)
    #404- Comentar publicaciones (publica)

    public static function redes_sociales($usuario, $curso, $amigo = null) {
        static::check400($amigo, $curso);
        static::check401($usuario, $curso);
        static::check402($usuario, $curso);
        //static::check403($usuario, $curso);  --cambiar el avatar
    }

    ###-------------------------------------------------------------------------------------------###
    ##################### TALLERES ########################
    #codigo 100  valida realizar el primer ejercicio de un curso
    #lista :)
    ###################################
    # codigo 403 - cambiar el avatar
    #logro cuando cambia el avatar

    private static function check100($usuario, $curso) {
        $usuario = usuario::find($usuario);
        if ($usuario && !$usuario->tiene_logro($curso, 100)) {

            if ($usuario->numero_de_ejercicios_resueltos_curso($curso) > 0) {
                #insertamos el logro
                $code = static::crear_logro(100, $usuario->id, $curso, 1);
                #se crea la notificación
                static::crear_notificacion($usuario->id, $curso, 1, $code);
            }
        }
    }

#101 - Cumplir el 25%
    #pre-debe haber minimo 10 ejercicios en el curso

    private static function check101($usuario, $curso) {
        $usuario = usuario::find($usuario);
        $curso = curso::find($curso);
        if ($usuario && !$usuario->tiene_logro($curso->id, 101) && $curso->get_numero_ejercicios() >= static::$logros[1]['min_num_ejercicios']) {
            $corte = ceil($curso->get_numero_ejercicios() * 0.25);
            if ($usuario->numero_de_ejercicios_resueltos_curso($curso->id) >= $corte) {
                #se crea el logro
                $code = static::crear_logro(101, $usuario->id, $curso->id, 1);

                #se crea la notificación
                static::crear_notificacion($usuario->id, $curso->id, 1, $code);
            }
        }
    }

    #102 - Cumplir el 50%
#pre-debe haber minimo 10 ejercicios en el curso

    private static function check102($usuario, $curso) {
        $usuario = usuario::find(Auth::user()->id);
        $curso = curso::find($curso);
        if ($usuario && $curso && !$usuario->tiene_logro($curso->id, 102) && $curso->get_numero_ejercicios() >= static::$logros[1]['min_num_ejercicios']) {
            $corte = ceil($curso->get_numero_ejercicios() * 0.5);
            if ($usuario->numero_de_ejercicios_resueltos_curso($curso->id) >= $corte) {

                #creamos el logro
                $code = static::crear_logro(102, $usuario->id, $curso->id, 1);

                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso->id, 1, $code);
            }
        }
    }

    #102 - Cumplir el 75%
#pre-debe hacer minimo 10 ejercicios en el curso

    private static function check103($usuario, $curso) {
        $usuario = usuario::find($usuario);
        $curso = curso::find($curso);
        if ($usuario && $curso && !$usuario->tiene_logro($curso->id, 103) && $curso->get_numero_ejercicios() >= static::$logros[1]['min_num_ejercicios']) {
            $corte = ceil($curso->get_numero_ejercicios() * 0.75);
            if ($usuario->numero_de_ejercicios_resueltos_curso($curso->id) >= $corte) {


                #crear el logro
                $code = static::crear_logro(103, $usuario->id, $curso->id, 1);
                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso->id, 1, $code);
            }
        }
    }

    #102 - Cumplir el 100%
    #pre-debe haber minimo 10 ejercicios

    private static function check104($usuario, $curso) {
        $usuario = usuario::find($usuario);
        $curso = curso::find($curso);
        if ($usuario && $curso && !$usuario->tiene_logro($curso->id, 104) && $curso->get_numero_ejercicios() >= static::$logros[1]['min_num_ejercicios']) {
            $corte = ceil($curso->get_numero_ejercicios());
            if ($usuario->numero_de_ejercicios_resueltos_curso($curso->id) >= $corte) {
                #se crea el logro
                $code = static::crear_logro(104, $usuario->id, $curso->id, 1);
                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso->id, 1, $code);
            }
        }
    }

    #resolver 3 ejercicios en el primer intento

    private static function check105($usuario, $curso) {
        $usuario = usuario::find($usuario);
        if ($usuario && !$usuario->tiene_logro($curso, 105)) {

            $ejercicios = $usuario->get_ejercicios_resueltos($curso);

            $c = 0;
            foreach ($ejercicios as $ejercicio) {
                if ($usuario->numero_de_intentos_ejercicio_accepted($curso, $ejercicio->ejercicio, 0) == 1) # no son en el primer intento pero OK
                    $c++;
            }

            if ($c >= 3) { # si lleva 3 en primer intento
                #se crea el logro
                $code = static::crear_logro(105, $usuario->id, $curso, 1);
                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso, 1, $code);
            }
        }
    }

    #desbloquear todos los modulos

    static function check108($usuario, $curso) {
        $usuario = usuario::find($usuario);
        $curso = curso::find($curso);
        if ($usuario && $curso && !$usuario->tiene_logro($curso->id, 108)) {

            if ($curso->get_numero_modulos() <= $curso->get_numero_modulos_desbloqueados()) {

                #se crea el logro
                $code = static::crear_logro(108, $usuario->id, $curso->id, 1);

                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso->id, 1, $code);
            }
        }
    }

    ################## FIN-TALLERES ################################
    ################## LOGROS DE REDES SOCIALES ##############################
    #funcion que checkea si un usuario tiene alogb likes 

    private static function check400($usuario, $curso) {

        $usuario = usuario::find($usuario);
        $curso = curso::find($curso);

        if ($usuario && $curso && !$usuario->tiene_logro($curso->id, 400)) {
            $n_estudiantes = $curso->numero_de_estudiantes();
            $corte = floor(static::$logros['4']['a'] * log($n_estudiantes));
            if ($usuario->numero_de_me_gusta_en_curso($curso->id) >= $corte) {  #gana el premio 400
#se crea el logro
                $code = static::crear_logro(400, $usuario->id, $curso->id, 4);

                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso->id, 4, $code);
            }
        }
    }

    #funcion que checkea si un usuario a compartido N publicaciones en facebook

    private static function check401($usuario, $curso) {

        $usuario = usuario::find($usuario);
        $curso = curso::find($curso);


        if ($usuario && $curso && !$usuario->tiene_logro($curso->id, 401)) {

            if ($usuario->get_numero_publicaciones_compartidas_en_redes_sociales($curso->id, 'fb') >= static::$logros['4']['num_publicaciones']) {

                #se crea el logro
                $code = static::crear_logro(401, $usuario->id, $curso->id, 4);

                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso->id, 4, $code);
            }
        }
    }

    #funcion que checkea si un usuario ha compartido N publicaciones en twitter

    private static function check402($usuario, $curso) {

        $usuario = usuario::find($usuario);
        $curso = curso::find($curso);


        if ($usuario && $curso && !$usuario->tiene_logro($curso->id, 402)) {

            if ($usuario->get_numero_publicaciones_compartidas_en_redes_sociales($curso->id, 'tw') >= static::$logros['4']['num_publicaciones']) {

                #se crea el logro
                $code = static::crear_logro(402, $usuario->id, $curso->id, 4);

                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso->id, 4, $code);
            }
        }
    }

#logro que se obtiene cuando se cambiar el avatar

    public static function check403($usuario) {

        $usuario = usuario::find($usuario);
        if ($usuario) {
            $cursos = $usuario->get_cursos_inscritos();

            foreach ($cursos as $curso) {
                if (!$usuario->tiene_logro($curso->curso_id, 403)) {
                    $code = static::crear_logro(403, $usuario->id, $curso->curso_id, 4);
                    static::crear_notificacion($usuario->id, $curso->curso_id, 1, $code);
                }
            }
        }
    }

    ##muestra el logro obtenido por un usuario cuando viene de un link de una fb o tw

    public function getVer($hash) {

        $notificacion = notificacion::find(LMSController::decoder($hash));

        #si encuentra un logro para mostrar, muestra su contenido
        if ($notificacion) {
            $logro = logro::get_info_logro($notificacion->codigo);
            return View::make('curso.logro.ver_logro')
                            ->with('logro', $logro)
                            ->with('usuario', usuario::find($notificacion->usuario))
                            ->with('curso', curso::find($notificacion->curso))
                            ->with('estado', usuario::get_informacion_curso($notificacion->usuario, $notificacion->curso));
        } else {
            return Redirect::to('/');
        }
    }

    ##########################FIN-REDES-SOCIALES ###############################
    #################### EVALUACIONES #######################################
    #300 - Ganar una evaluación

    public static function check300($usuario, $evaluacion) {
        $usuario = usuario::find($usuario);
        $evaluacion = evaluacion::find($evaluacion);

        #si el usuario ya no ha ganado el logro 300
        if ($usuario && $evaluacion && !$usuario->tiene_logro($evaluacion->curso, 300)) {

            #obtiene el logro 
            if ($usuario->gano_evaluacion()) {
                $curso = modulo::find($evaluacion->modulo)->curso;
                #se crea el logro
                $code = static::crear_logro(300, $usuario->id, $curso, 3);
                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso, 3, $code);
            }
        }
    }

    #301 - Ganar todas las evaluaciones
    #pre- deben haber al menos el mismo numero de modulos

    private static function check301($usuario, $evaluacion) {
        $usuario = usuario::find($usuario);
        $evaluacion = evaluacion::find($evaluacion);
        $curso = curso::find($curso);




        if ($usuario && $evaluacion && $curso && !$usuario->tiene_logro($curso->id, 301)) {

            $modulos = $curso->get_modulos();
            $nmodulos = count($modulos);

            $evaluaciones_ganadas = 0;
            foreach ($modulos as $modulo) {
                $evaluaciones = modulo::find($modulo->id)->get_evaluaciones();
                foreach ($evaluaciones as $evaluacion) {
                    if ($usuario->gano_evaluacion($evaluacion->id)) {
                        $evaluaciones_ganadas++;
                    }
                }
            }


            if ($evaluaciones_ganadas >= $nmodulos) {
                $code = static::crear_logro(301, $usuario->id, $curso->id, 3);
                #se crea la notificacion
                static::crear_notificacion($usuario->id, $curso->id, 3, $code);
            }
        }
    }

    #302 - Sacar un 100% (cardinalidad)

    private static function check302($usuario, $evaluacion) {
        $usuario = usuario::find($usuario);
        $evaluacion = evaluacion::find($evaluacion);

        #si el usuario no tiene el logro en determinado curso
        if ($usuario && $evaluacion && !$usuario->tiene_logro($evaluacion->curso, 302)) {
            if ($usuario->get_porcentaje_en_evaluacion($evaluacion->id) == 100) {
                $code = static::crear_logro(302, $usuario->id, $evaluacion->curso, 3);
                #se crea la notificacion
                static::crear_notificacion($usuario->id, $evaluacion->curso, 3, $code);
            }
        }
    }

    #303 - Medallería (3 - Oro, 4- Plata, 5 - Bronce) (Cardinalidad)

    private static function check30345($usuario, $evaluacion) {


        $usuario = usuario::find($usuario);
        $evaluacion = evaluacion ::find($evaluacion);
        $curso = curso::find(modulo::find($evaluacion->modulo)->curso);

        if ($usuario && $evaluacion) { #si existe el usuario y la evaluacion
            #generamos la tabla de posiciones de la evaluacion
            $ranking = $evaluacion->get_ranking();

            $pos = 0;
            foreach ($ranking as $posicion) {
                $pos++;
                if ($posicion->usuario == $usuario->id) {
                    break;
                }
            }

            #var_dump($posicion);
            #esta en el top 3
            if ($pos <= 3 && $pos >= 1 && $usuario->gano_evaluacion($evaluacion->id)) {

                #  exit;
                $codigo = 303;
                if ($pos == 2) {
                    $codigo = 304;
                } else if ($pos == 3) {
                    $codigo = 305;
                }

                if ($usuario->tiene_logro($evaluacion->curso, $codigo)) { #si el usuario ya lo tiene aumentamos su cardinalidad
                    $logro = DB::table('curso_x_logro_x_usuario')
                            ->where('curso', $curso->id)
                            ->where('logro', logro::where('codigo', $codigo)->first()->id)
                            ->where('usuario', $usuario->id)
                            ->first();

                    $code = $logro->id;
                    $cardinalidad = $logro->cardinalidad + 1;


                    #actualizamos el valor
                    $logro = DB::table('curso_x_logro_x_usuario')
                            ->where('curso', $curso->id)
                            ->where('logro', logro::where('codigo', $codigo)->first()->id)
                            ->where('usuario', $usuario->id)
                            ->update(array('cardinalidad' => $cardinalidad));
                } else { #si el usuario no ha ganado el logro
                    #se crea el logro
                    $code = static::crear_logro($codigo, $usuario->id, $curso->id, 3, 0, $evaluacion->id);
                }


                static::crear_notificacion($usuario->id, $curso->id, 3, $code);
            }
        }
    }

    #inserta en la tabla de logros

    public static function crear_logro($codigo, $usuario, $curso, $tipo, $cardinalidad = 0, $evaluacion = 0) {


        return DB::table('curso_x_logro_x_usuario')
                        ->insertGetId(array(
                            'curso' => $curso,
                            'logro' => logro::where('codigo', $codigo)->first()->id,
                            'usuario' => $usuario,
                            'cardinalidad' => $cardinalidad,
                            'fecha_obtencion' => date('Y-m-d H:i:s'),
                            'visto' => false,
                            'tipo' => $tipo,
                            'codigo_evaluacion' => $evaluacion
        ));
    }

    public static function crear_notificacion($usuario, $curso, $tipo, $codigo) {
        notificacion::create(array(
            'curso' => $curso,
            'usuario' => $usuario,
            'tipo' => $tipo,
            'codigo' => $codigo
        ));
    }

    ###################### FIN-EVALUACION ##############################
}

?>
