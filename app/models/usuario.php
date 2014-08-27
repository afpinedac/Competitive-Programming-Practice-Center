<?php

class Usuario extends Eloquent {

    protected $table = 'usuario';
    protected $guarded = array();

    public function es_propietario($curso) {
        return DB::table('curso')
                        ->where('id', $curso)
                        ->where('profesor_id', $this->id)
                        ->count() == 1;
    }

    public function es_monitor($curso) {
        return DB::table('curso_x_usuario')
                        ->where('curso_id', $curso)
                        ->where('usuario_id', $this->id)
                        ->where('rol', 1)
                        ->count() == 1;
    }

    public function tiene_inscrito($curso) {
        return DB::table('curso_x_usuario')
                        ->where('usuario_id', $this->id)
                        ->where('curso_id', $curso)
                        ->count() == 1;
    }

    public function get_bitacora($curso) {
        return DB::table('bitacora')
                        ->select(DB::raw('fecha_ingreso,fecha_salida, TIMEDIFF(fecha_salida,fecha_ingreso) as tiempo_total '))
                        ->where('usuario', $this->id)
                        ->where('curso', $curso)
                        ->orderBy('fecha_ingreso', 'desc')
                        ->get();
    }

    public function get_ultimo_acceso($curso) {
        $data = DB::table('bitacora')
                ->where('usuario', $this->id)
                ->where('curso', $curso)
                ->orderBy('fecha_ingreso', 'desc')
                ->first();

        return $data ? $data->fecha_ingreso : "-";
    }

    public function get_numero_publicaciones_compartidas_en_redes_sociales($curso, $rs = 'fb') {

        $column = $rs == 'fb' ? 'compartida_facebook' : 'compartida_twitter';

        return DB::table('notificacion')
                        ->where('curso', $curso)
                        ->where('usuario', $this->id)
                        ->where($column, 1)
                        ->count();
    }

    public function get_amigos($curso) {


        $amigos_off = DB::table('usuario')
                ->join('curso_x_usuario', 'curso_x_usuario.usuario_id', '=', 'usuario.id')
                ->where('curso_x_usuario.curso_id', $curso)
                ->where('curso_x_usuario.usuario_id', '<>', $this->id)
                ->where('curso_x_usuario.ultima_interaccion', '<', time() - LMSController::$MINUTES_TO_OFFLINE * 60)
                ->orderBy('usuario.nombres')
                ->get();

        $amigos_on = DB::table('usuario')
                ->join('curso_x_usuario', 'curso_x_usuario.usuario_id', '=', 'usuario.id')
                ->where('curso_x_usuario.curso_id', $curso)
                ->where('curso_x_usuario.usuario_id', '<>', $this->id)
                ->where('curso_x_usuario.ultima_interaccion', '>', time() - LMSController::$MINUTES_TO_OFFLINE * 60)
                ->orderBy('usuario.nombres')
                ->get();


        return array_merge($amigos_on, $amigos_off);
    }

    #retorna los mensajes en la bandeja de entrada de un usuario

    public function get_mis_mensajes($curso) {
        return DB::table('usuario')
                        ->join('mensaje', 'usuario.id', '=', 'mensaje.remitente')
                        ->where('destinatario', $this->id)
                        ->where('curso', $curso)
                        // ->whereNull('respuesta_a')
                        ->orderBy('mensaje.id', 'desc')
                        ->get();
    }

    public function numero_de_mensajes_sin_leer($curso) {
        return DB::table('mensaje')
                        ->where('destinatario', $this->id)
                        ->where('curso', $curso)
                        ->where('leido', false)
                        ->count();
    }

    public function get_logros_obtenidos($curso) {
        return DB::table('logro')
                        ->join('curso_x_logro_x_usuario', 'curso_x_logro_x_usuario.logro', '=', 'logro.id')
                        ->where('curso', $curso)
                        ->where('usuario', $this->id)
                        ->get();
    }

    public function get_ejercicios_resueltos($curso, $tipo = 0) {
        return DB::table('envio')
                        ->select(DB::raw('DISTINCT ejercicio'))
                        ->where('resultado', 'accepted')
                        ->where('test', 0)
                        ->where('usuario', $this->id)
                        ->where('curso', $curso)
                        ->where('tipo', $tipo)
                        ->get();
    }

    public function get_informacion_curso($curso) {

        $x = DB::table('curso_x_usuario')
                ->where('curso_id', $curso)
                ->where('usuario_id', $this->id)
                ->first();
        return $x;
    }

    #retorna los envios evaluados para el usuario

    public function get_envios_evaluados_en_curso($curso) {
        return DB::table('envio_evaluado')
                        ->join('envio', 'envio_evaluado.envio', '=', 'envio.id')
                        ->where('usuario', $this->id)
                        ->where('curso', $curso)
                        ->get();
    }

    #---------------LOGROS--------------------------------
    #EJERCICIOSS
    #retorna y actualiza el siguiente logro ganado por un ejercicios

    public function get_logro_ejercicio($curso) {
        $logro = DB::table('curso_x_logro_x_usuario')
                ->where('usuario', $this->id)
                ->where('curso', $curso)
                ->where('visto', 0)
                ->where('tipo', 1)
                ->first();


        if ($logro) {
            DB::table('curso_x_logro_x_usuario')
                    ->where('usuario', $logro->usuario)
                    ->where('curso', $logro->curso)
                    ->where('logro', $logro->logro)
                    ->update(array('visto' => true));


            return logro::where('id', $logro->logro)->first();
        }

        return null;
    }

    //retorna y actualiza el siguiente logro ganado por un ejercicios

    public function get_logro_redes_sociales($curso) {
        $logro = DB::table('curso_x_logro_x_usuario')
                ->where('usuario', $this->id)
                ->where('curso', $curso)
                ->where('visto', 0)
                ->where('tipo', 4) //logro de redes sociales
                ->first();


        if ($logro) {
            DB::table('curso_x_logro_x_usuario')
                    ->where('usuario', $logro->usuario)
                    ->where('curso', $logro->curso)
                    ->where('logro', $logro->logro)
                    ->update(array('visto' => true));


            return logro::where('id', $logro->logro)->first();
        }

        return null;
    }

    #retorna y actualiza el siguiente logro ganado por una evaluacion

    public function get_logro_evaluacion($curso) {
        $logro = DB::table('curso_x_logro_x_usuario')
                ->where('usuario', $this->id)
                ->where('curso', $curso)
                ->where('visto', 0)
                ->where('tipo', 3) //logro de evaluacion
                ->first();

        if ($logro) {
            DB::table('curso_x_logro_x_usuario')
                    ->where('usuario', $logro->usuario)
                    ->where('curso', $logro->curso)
                    ->where('logro', $logro->logro)
                    ->update(array('visto' => true));


            return logro::where('id', $logro->logro)->first();
        }

        return null;
    }

    public function get_logros_en_evaluacion($evaluacion) {
        return DB::table('curso_x_logro_x_usuario')
                        ->join('logro', 'logro.id', '=', 'curso_x_logro_x_usuario.logro')
                        ->where('curso_x_logro_x_usuario.codigo_evaluacion', $evaluacion)
                        ->get();
    }

    #logro X

    public function tiene_logro($curso, $logro) {

        $logro = logro::where('codigo', $logro)->first();
        return DB::table('curso_x_logro_x_usuario')
                        ->where('curso', $curso)
                        ->where('usuario', $this->id)
                        ->where('logro', $logro->id)->count() == 1;
    }

    #mira cuantos ejercicios tiene resuelto un usuario en un curso

    public function numero_de_ejercicios_resueltos_curso($curso) {
        return count(DB::table('envio')
                        ->select(DB::raw('DISTINCT usuario,curso, ejercicio, resultado'))
                        ->where('usuario', $this->id)
                        ->where('curso', $curso)
                        ->where('resultado', 'accepted')
                        ->where('test', 0)
                        ->where('tipo', 0)
                        ->get());
    }

    //rretorna el numero de intentos para resolver un ejercicios
    //tipo 0 si es de un taller 1 si es de un ejercico    
    public function numero_de_intentos_ejercicio_accepted($curso, $ejercicio, $tipo, $codigo = null) {


        if ($codigo) {
            $envios = DB::table('envio')
                    ->where('usuario', $this->id)
                    ->where('codigo', $codigo)
                    ->where('tipo', $tipo)
                    ->where('curso', $curso)
                    ->where('ejercicio', $ejercicio)
                    ->where('test', 0)
                    ->orderBy('id')
                    ->get();
        } else {
            $envios = DB::table('envio')
                    ->where('usuario', $this->id)
                    //->where('codigo', $codigo)
                    ->where('tipo', $tipo)
                    ->where('curso', $curso)
                    ->where('ejercicio', $ejercicio)
                    ->where('test', 0)
                    ->orderBy('id')
                    ->get();
        }

        $c = 0;
        foreach ($envios as $envio) {
            $c++;
            if ($envio->resultado == 'accepted') {
                return $c;
            }
        }
    }

    #retorna el numero de likes que tiene un usaurio en un curso

    public function numero_de_me_gusta_en_curso($curso) {



        return count(DB::table('me_gusta')
                        ->join('notificacion', 'me_gusta.usuario', '=', 'notificacion.usuario')
                        ->where('notificacion.usuario', $this->id)
                        ->where('notificacion.curso', $curso)
                        ->get());
    }

    //funciones que actualizan los puntajes y la plata

    public function sumar_puntos_en_curso($curso, $puntos) {
        $record = DB::table('curso_x_usuario')
                ->where('usuario_id', $this->id)
                ->where('curso_id', $curso)
                ->increment('puntos', $puntos);
    }

    public function sumar_plata($plata) {
        $usuario = usuario::find($this->id);

        DB::table('usuario')
                ->where('id', $usuario->id)
                ->update(array('plata' => ($usuario->plata + $plata)));
    }

    public function get_posicion_en_ranking($curso) {

        $pos = 0;
        $ranking = curso::find($curso)->get_ranking();

        foreach ($ranking as $user) {
            $pos++;
            if ($user['id'] == $this->id) {
                return $pos;
            }
        }
        return -1;
    }

    public function get_tiempo_logueado($curso) {


        $time_in_secs = DB::table('bitacora')
                ->select(DB::raw("SUM(TIME_TO_SEC(TIMEDIFF(fecha_salida,fecha_ingreso))) as tiempo"))
                ->where('usuario', $this->id)
                ->where('curso', $curso)
                ->first();


        return $time_in_secs->tiempo;
    }

    #funcion que verifica si un usuario ya ha valorado un contenido

    public function valoro_contenido($contenido) {
        return DB::table('valoracion_contenido')
                        ->where('usuario', $this->id)
                        ->where('contenido', $contenido)
                        ->count() == 1;
    }

    #actualiza la ultima interaccion del usuario con la aplicacion

    public function update_ultima_interaccion_en_curso($curso) {
        if ($curso) {
            DB::table('curso_x_usuario')
                    ->where('usuario_id', $this->id)
                    ->where('curso_id', $curso)
                    ->update(array('ultima_interaccion' => time()));
        }
    }

    public function get_ultima_interaccion_en_curso($curso) {

        return DB::table('curso_x_usuario')
                        ->where('usuario_id', $this->id)
                        ->where('curso_id', $curso)
                        ->first()->ultima_interaccion;
    }

    #funciones del monitoreo

    public function get_porcentaje_en_taller($taller) {
        $numero_ejercicios = taller::find($taller)->get_numero_ejercicios();
        $solucionados = $this->get_numero_ejercicios_resultos_en_taller($taller);

        if ($numero_ejercicios == 0) {
            return 0;
        } else {
            return (float) (($solucionados / $numero_ejercicios) * 100.0);
        }
    }

    public function get_porcentaje_en_evaluacion($evaluacion) {
        $numero_ejercicios = evaluacion::find($evaluacion)->get_numero_ejercicios();
        $solucionados = $this->get_numero_ejercicios_resultos_en_evaluacion($evaluacion);

        if ($numero_ejercicios == 0) {
            return 0;
        } else {
            return (float) (($solucionados / $numero_ejercicios) * 100.0);
        }
    }

    public function get_numero_ejercicios_resultos_en_taller($taller) {
        $x = DB::table('envio')
                ->select(DB::raw('(COUNT( DISTINCT ejercicio)) as resuelto'))
                ->where('codigo', $taller)
                ->where('usuario', $this->id)
                ->where('tipo', 0) //es un taller
                ->where('resultado', 'accepted')
                ->where('test', 0)
                ->groupBy('ejercicio')
                ->get();


        return count($x);
    }

    public function get_numero_ejercicios_resultos_en_evaluacion($evaluacion) {
        $x = DB::table('envio')
                ->select(DB::raw('(COUNT( DISTINCT ejercicio)) as resuelto'))
                ->where('codigo', $evaluacion)
                ->where('usuario', $this->id)
                ->where('tipo', 1) //es una evaluacion
                ->where('resultado', 'accepted')
                ->where('test', 0)
                ->groupBy('ejercicio')
                ->get();


        return count($x);
    }

    public function get_fecha_ultimo_envio_en_taller($taller) {

        $last = envio::where('tipo', 0)
                ->where('codigo', $taller)
                ->orderBy('created_at', 'desc')
                ->where('usuario', $this->id)
                ->first();

        return $last ? "" . $last->created_at : '-';
    }

    public function get_numero_envios_en_taller($taller) {
        return envio::where('usuario', $this->id)
                        ->where('codigo', $taller)
                        ->where('tipo', 0) //tipo taller
                        ->count();
    }

    public function get_numero_envios_en_evaluacion($evaluacion) {
        return envio::where('usuario', $this->id)
                        ->where('codigo', $evaluacion)
                        ->where('tipo', 1) //tipo evaluacion
                        ->count();
    }

    public function get_envios_en_taller($taller) {

        return DB::table('ejercicio')
                        ->join('envio', 'ejercicio.id', '=', 'envio.ejercicio')
                        ->where('envio.codigo', $taller)
                        ->where('envio.usuario', $this->id)
                        ->where('envio.tipo', 0) //son los tipos talleres
                        ->orderBy('envio.id', 'desc')
                        ->get();
    }

    public function get_envios_en_evaluacion($evaluacion) {

        return DB::table('ejercicio')
                        ->join('envio', 'ejercicio.id', '=', 'envio.ejercicio')
                        ->where('envio.codigo', $evaluacion)
                        ->where('envio.usuario', $this->id)
                        ->where('envio.tipo', 1) // tipo evaluacion
                        ->orderBy('envio.id', 'desc')
                        ->get();
    }

    public function gano_evaluacion($evaluacion) {
        $evaluacion = evaluacion::find($evaluacion);
        $usuario = usuario::find(Auth::user()->id);

        $resueltos = $usuario->get_numero_ejercicios_resultos_en_evaluacion($evaluacion->id);
        $totales = $evaluacion->get_numero_ejercicios();
        $porcentaje = $totales == 0 ? 0 : (($resueltos / $totales) * 100);
        #obtiene el logro 
        if ($porcentaje >= $evaluacion->porcentaje_aprobacion) {
            return true;
        }
        return false;
    }

    #retorna los items comprados por un estudiante

    public function get_items_comprados() {
        return DB::table('item_x_usuario')
                        ->join('item', 'item.id', '=', 'item_x_usuario.item')
                        ->where('item_x_usuario.usuario', $this->id)
                        ->get();
    }

    #retorna los items que estan disponibles para la compra de un usuario

    public function get_items_no_comprados() {
        return DB::table('item')
                        ->whereNotIn('item.id', array_add(DB::table('item_x_usuario')
                                        ->where('item_x_usuario.usuario', $this->id)
                                        ->lists('item_x_usuario.item'), -1, -1))
                        ->orderBy('precio', 'desc')
                        ->get();
    }

    #retorna el dinero total del usuario en todos los cursos

    public function get_dinero_total() {
        return $this->plata;
    }

    #verifica si un usario ha comprado un item

    public function ha_comprado_item($item) {

        return DB::table('item_x_usuario')
                        ->where('usuario', $this->id)
                        ->where('item', $item)
                        ->count() == 1;
    }

    #retorna los ejercicios creados por un profesor

    public function ejercicios_creados() {
        return ejercicio::where('profesor', $this->id)
                        ->orderBy('nombre')
                        ->get();
    }

    #----------ESTADÍSTICAS-----------------
    #numero de veredictos en un taller (tipo hace refencia a 0 taller, 1 evaluacion)

    public function get_numero_respuestas_en_modulo($curso, $tipo, $codigo, $resultado) {

        return DB::table('envio')
                        ->where('tipo', $tipo)
                        ->where('codigo', $codigo)
                        ->where('resultado', $resultado)
                        ->where('curso', $curso)
                        ->where('usuario', $this->id)
                        ->count();
    }

    #numero de veredictos en un taller (tipo hace refencia a 0 taller, 1 evaluacion)

    public function get_numero_respuestas_en_curso($curso, $resultado) {

        return DB::table('envio')
                        ->where('resultado', $resultado)
                        ->where('curso', $curso)
                        ->where('usuario', $this->id)
                        ->count();
    }

    #numero de envios en un taller de un curso

    public function get_numero_envios_en_modulo($tipo, $codigo) {

        return DB::table('envio')
                        ->where('tipo', $tipo)
                        ->where('codigo', $codigo)
                        ->where('usuario', $this->id)
                        ->where('test', 0)
                        ->count();
    }

    #retorna el numero de puntos en un curso

    public function get_puntos_en_curso($curso, $dias_antes = null) {

        if ($dias_antes) {
            $puntos = DB::table('envio')
                    ->where('usuario', $this->id)
                    ->where('curso', $curso)
                    ->where('created_at', '>=', date('Y-m-d H:i:s', time() - (60 * 60 * 24 * $dias_antes)))
                    ->sum('puntos_obtenidos');

            return $puntos ? $puntos : 0;
        } else {
            $puntos = DB::table('envio')
                    ->where('usuario', $this->id)
                    ->where('curso', $curso)
                    ->sum('puntos_obtenidos');
            return $puntos ? $puntos : 0;
        }
    }

    #retorna el mejor tiempo de ejecucion de un ejercicio en un taller

    public function get_mejor_tiempo_ejecucion_ejercicio($ejercicio, $code, $tipo = 0) { #defecto es un taller
        return DB::table('envio')
                        ->where('resultado', 'accepted')
                        ->where('usuario', $this->id)
                        ->where('ejercicio', $ejercicio)
                        ->where('codigo', $code)
                        ->where('tipo', $tipo)
                        ->where('test', 0)
                        ->min('tiempo_de_ejecucion');
    }

    #retorna todos los envios realizados por un usuario

    public function get_envios_en_curso($curso) {
        return DB::table('envio')
                        ->where('usuario', $this->id)
                        ->where('curso', $curso)
                        ->where('test', 0)
                        ->orderBy('id', 'desc')
                        ->get();
    }

    #retorna las alertas de un usuario ; // n = no vistas , t = todas

    public function get_alertas($curso, $type = 'n') {

        if ($type == 'n') {

            return DB::table('alerta')
                            // ->where('visto', 0)
                            ->where('to', $this->id)
                            ->where('curso', $curso)
                            ->orderBy('id', 'desc')
                            ->get();
        } else if ($type == 'c') {
            return DB::table('alerta')
                            ->where('to', $this->id)
                            ->where('visto', 0)
                            ->where('curso', $curso)
                            ->count();
        }
    }

    #retorna y actualiza el resultado de un test realizado

    public function get_resultado_test($curso, $ejercicio) {

        $envio = DB::table('envio')
                ->where('curso', $curso)
                ->where('ejercicio', $ejercicio)
                ->where('test', 1)
                ->where('visto', 0)
                ->where('usuario', $this->id)
                ->first();
        if ($envio) {
            DB::table('envio')
                    ->where('id', $envio->id)
                    ->update(array('visto' => 1));
        }
        return $envio;
    }

    //-------------imagenes avatares ---------------------------------

    public static function saveImage($udata, $user) {
        $userImage = public_path() . ("/avatares/userimages/{$user}.png");
        $udata = json_decode($udata);
        $xml = simplexml_load_file(public_path() . ("/avatares/avatar.xml"));
        $image = imagecreatetruecolor(500, 500);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 255, 255, 255, 0));
        $postImages = array();
        foreach ($xml->categories->children() as $category) {
            $item = $category->xpath('item[@id="' . $udata->{$category->getName()} . '"]');
            $item = $item[0];
            $color = @$udata->{$category->getName() . "Color"};
            if ($category->getName() == "mouth" || $category->getName() == "nose" || $category->getName() == "body") {
                $color = @$udata->headColor;
            }
            $obj = static::getItemImageData($item, $xml, $category->getName(), $color, @$udata->head);
            if (@$obj->bimage) {
                imagecopy($image, $obj->bimage, 0, 0, 50, 100, 500, 500);
                imagedestroy($obj->bimage);
                $postImages[] = $obj;
            } else if ($item->attributes()->post == "1") {
                $postImages[] = $obj;
            } else {
                imagecopy($image, $obj->image, 0, 0, 50, 100, 500, 500);
                imagedestroy($obj->image);
            }
        }
        foreach ($postImages as $obj) {
            imagecopy($image, $obj->image, 0, 0, 50, 100, 500, 500);
            imagedestroy($obj->image);
        }
        imagepng($image, $userImage);
        imagedestroy($image);
    }

    private static function getItemPosition($item, $xml, $catName, $size, $headid) {
        $pos = new stdClass;
        $pos->x = (int) $item->attributes()->x;
        $pos->y = (int) $item->attributes()->y;
        $pos->sx = (float) $item->attributes()->sx;
        $pos->sy = (float) $item->attributes()->sy;
        $pos->sx = $pos->sx == 0 ? 1 : $pos->sx;
        $pos->sy = $pos->sy == 0 ? 1 : $pos->sy;

        switch ($catName) {
            case "head":
                $pos->x = 300 - ($size[0] / 2 - $pos->x) * $pos->sx;
                $pos->y = 300 - ($size[1] / 2 - $pos->y) * $pos->sy;
                break;
            case "background":
                $pos->x = -100;
                $pos->y = 0;
                break;
            case "body":
            case "clothes":
                $pos->x = 300 - ($size[0] / 2 - $pos->x) * $pos->sx;
                $pos->y = 380;
                break;
            default:
                $props = $xml->categories->head->xpath("item[@id='$headid']");
                $props = $props[0]->{$catName};
                $sx = (float) @$props->attributes()->sx;
                $sy = (float) @$props->attributes()->sy;
                $sx = $sx == 0 ? 1 : $sx;
                $sy = $sy == 0 ? 1 : $sy;
                $pos->sx*=$sx;
                $pos->sy*=$sy;
                if ($size !== FALSE) {
                    $pos->x = 300 + ((int) @$props->attributes()->x) - ($size[0] / 2 - $pos->x) * $pos->sx;
                    $pos->y = 300 + ((int) @$props->attributes()->y) - ($size[1] / 2 - $pos->y) * $pos->sy;
                }
        }
        if ($size !== FALSE) {
            $pos->width = $pos->sx * $size[0];
            $pos->height = $pos->sy * $size[1];
        }
        return $pos;
    }

    #posiblemente cambiar la carpeta de la imagen

    private static function getItemImageData($item, $xml, $catName, $color, $headid) {
        $obj = new stdClass;
        $imageSrc = public_path() . "/avatares/images/$catName/" . $item->attributes()->id . ".png";
        $oimageSrc = public_path() . "/avatares/images/$catName/" . $item->attributes()->id . "_o.png";
        ;
        $bimageSrc = public_path() . "/avatares/images/$catName/" . $item->attributes()->id . "_b.png";
        ;
        $pos = static::getItemPosition($item, $xml, $catName, @getimagesize($imageSrc), $headid);
        $canvas = imagecreatetruecolor(600, 600);
        imagefill($canvas, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
        if ($image = @imagecreatefrompng($imageSrc)) {
            if ($color) {
                static::colorize($image, $color);
            }
            imagecopyresampled($canvas, $image, $pos->x, $pos->y, 0, 0, $pos->width, $pos->height, imagesx($image), imagesy($image));
        }
        if ($image = @imagecreatefrompng($oimageSrc)) {
            imagecopyresampled($canvas, $image, $pos->x, $pos->y, 0, 0, $pos->width, $pos->height, imagesx($image), imagesy($image));
        }
        if ($image = @imagecreatefrompng($bimageSrc)) {
            if ($color) {
                static::colorize($image, $color);
            }
            $canvasb = imagecreatetruecolor(600, 600);
            imagefill($canvasb, 0, 0, imagecolorallocatealpha($canvas, 0, 0, 0, 127));
            imagecopyresampled($canvasb, $image, $pos->x, $pos->y, 0, 0, $pos->width, $pos->height, imagesx($image), imagesy($image));
            $obj->bimage = $canvasb;
        }

        $obj->position = $pos;
        $obj->image = $canvas;
        return $obj;
    }

    private static function colorize($image, $color) {
        list($filter_r, $filter_g, $filter_b) = sscanf($color, "%02x%02x%02x");
        $imagex = imagesx($image);
        $imagey = imagesy($image);
        for ($x = 0; $x < $imagex; ++$x) {
            for ($y = 0; $y < $imagey; ++$y) {
                $rgb = imagecolorat($image, $x, $y);
                $TabColors = imagecolorsforindex($image, $rgb);
                $color_r = floor($TabColors['red'] * $filter_r / 255);
                $color_g = floor($TabColors['green'] * $filter_g / 255);
                $color_b = floor($TabColors['blue'] * $filter_b / 255);
                $newcol = imagecolorallocatealpha($image, $color_r, $color_g, $color_b, $TabColors['alpha']);
                imagesetpixel($image, $x, $y, $newcol);
            }
        }
    }

    public function get_cursos_inscritos() {

        return DB::table('curso_x_usuario')
                        ->where('usuario_id', $this->id)
                        ->get();
    }

    public function subir_nivel_en_curso($curso) {
        if (!$this->tiene_inscrito($curso))
            return;
        $data = DB::table('curso_x_usuario')
                ->where('curso_id', $curso)
                ->where('usuario_id', $this->id)
                ->first();

        DB::table('curso_x_usuario')
                ->where('curso_id', $curso)
                ->where('usuario_id', $this->id)
                ->update(array('nivel' => $data->nivel + 1));
    }

    public function get_nivel_en_curso($curso) {

        $data = DB::table('curso_x_usuario')
                ->where('usuario_id', $this->id)
                ->where('curso_id', $curso)
                ->first();

        return $data->nivel;
    }

    public function get_numero_comentarios_en_notificaciones($curso) {

        return DB::table('notificacion')
                        ->where('tipo', 5)
                        ->where('usuario', $this->id)
                        ->where('curso', $curso)
                        ->count();
    }

    public function get_numero_de_participaciones_en_foro($curso) {

        return DB::table('respuesta_foro')
                        ->join('tema_foro', 'tema_foro.id', '=', 'respuesta_foro.tema_foro')
                        ->where('respuesta_foro.usuario', $this->id)
                        ->where('curso', $curso)
                        ->count();
    }

}
