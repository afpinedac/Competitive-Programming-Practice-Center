<?php

class TallerController extends LMSController {
  #Meta funcion que evalua un ejercicios del cual solo se necesita ingresar su salida
  #tipo 0 = si es un ejercicio  1 = si es una evaluacion

  public function postEvaluarEjercicio() {


    #obtenemos los datos
    $ejercicio = Crypt::decrypt(Input::get('ejercicio'));
    $curso = Crypt::decrypt(Input::get('curso'));
    #determinamos si es una evaluación o un taller
    $tipo = Input::has('evaluacion') ? 1 : 0;
    $code = $tipo == 0 ? Crypt::decrypt(Input::get('taller')) : Crypt::decrypt(Input::get('evaluacion'));

    $correct_out = $this->get_respuesta_out($ejercicio);
    $user_out = Input::get('respuesta');

    //si el usuario eligió archivo, se tienen esso datos        
    if (Input::hasFile('out')) {
      #creamos el nuevo nombre del archivo
      $file_name = Auth::user()->id . "_" . time() . "_" . Input::file('out')->getClientOriginalName();
      #lo ponemos en la carpeta de textos
      Input::file('out')->move(public_path() . '/' . $this->ruta['envio'], $file_name);
      #leemos su contenido
      $user_out = LMSController::get_text_file($this->ruta['envio'] . '/' . $file_name);
      #eliminamos el archivo
      unlink(public_path() . '/' . $this->ruta['envio'] . '/' . $file_name);
    }

    #evaluamos ambos strings (la respuesta correcta y la enviada por el usuario)
    $resultado = $this->evaluar_out($correct_out, $user_out);

#creamos el registro del envio en la base de datos
    $envio = DB::table('envio')->insertGetId(array(
        'usuario' => Auth::user()->id,
        'curso' => $curso,
        'ejercicio' => $ejercicio,
        'resultado' => $resultado,
        'tipo' => $tipo,
        'codigo' => $code,
        'created_at' => date("Y-m-d H:i:s")
    ));

    #si es un ejercicio de taller lo insertamoe en la tabla de envios evaluados
    if ($tipo == 0) {
      DB::table('envio_evaluado')
              ->insert(array('envio' => $envio));
      Juez::evaluar_envios($curso); #se evaluan todos los envios para ver si generan logros de taller.
    }

    Session::flash('valid', "Su envio ha sido recibido correctamente con id {$envio}");




    if ($tipo == 1) { #es una evaluacion
      return Redirect::to("curso/ver/{$curso}/evaluacion/{$code}/{$ejercicio}");
    } else { #es un ejercicio de taller
      return Redirect::to("curso/ver/{$curso}/ejercicio/{$ejercicio}");
    }
  }

  private function evaluar_out($correct_out, $user_out) {
    //procesamos ambas respuestas  

    $x = trim($correct_out);
    $y = trim($this->procesar_out($user_out));

    if (strlen($x) != strlen(($y))) {
      return 'wrong answer';
    }


    for ($i = 0; $i < strlen($x); $i++) {
      if (trim($x[$i]) != trim($y[$i])) {
        return 'wrong answer';
      }
    }

    return 'accepted';
  }

  private function get_respuesta_out($ejercicio) {
    return ejercicio::find($ejercicio)->out;
  }

  private function procesar_out($s) {
    $s = explode("\n", $s);
    $t = "";
    foreach ($s as $r) {
      $t.=$r;
    }
    return $t;
  }

  //----------------------------------------------
  //Meta funcion que evalua un ejercicio el cual se escribio el código
  public function postEvaluarEjercicioCodigo() {
    // dd(Input::all());

    $ejercicio = Crypt::decrypt(Input::get('ejercicio'));
    $lenguaje = Input::get('lenguaje');
    $curso = Crypt::decrypt(Input::get('curso'));
    $user_code = Input::get('respuesta');
    $tipo = Input::has('evaluacion') ? 1 : 0;
    $test = Input::has('test') ? 1 : 0;
    $in = Input::get('in');
    $codigo = Input::has('evaluacion') ? Crypt::decrypt(Input::get('evaluacion')) : Crypt::decrypt(Input::get('taller'));

    //obtenemos el codigo de la persona
    if (Input::hasFile('out')) {
      //creamos el nuevo nombre del archivo
      $file_name = Auth::user()->id . "_" . time() . "_" . Input::file('out')->getClientOriginalName();
      //lo ponemos en la carpeta de textos
      Input::file('out')->move(public_path() . '/' . $this->ruta['envio'], $file_name);
      //leemos su contenido
      $user_code = LMSController::get_text_file($this->ruta['envio'] . '/' . $file_name);
      //eliminamos el archivo
      unlink(public_path() . '/' . $this->ruta['envio'] . '/' . $file_name);
    }



    $envio = array(
        'usuario' => Auth::user()->id,
        'curso' => $curso,
        'ejercicio' => $ejercicio,
        'lenguaje' => $lenguaje,
        'algoritmo' => $user_code,
        'created_at' => date("Y-m-d H:i:s"),
        'tipo' => $tipo,
        'codigo' => $codigo,
        'test' => $test,
        'in' => $in,
    );

    //  echo "pasa";
    //exit;

    try {
      #creamos el socket y llamamos al Juez en linea
      $socket = fsockopen(LMSController::$SOCKET_HOST, LMSController::$SOCKET_PORT);
    } catch (Exception $e) {
      Session::flash("invalid", "Ha ocurrido un problema con el juez en línea, Inténtelo mas tarde");
      return Redirect::to("curso/ver/{$curso}/ejercicio/{$ejercicio}");
    }
    #prueba
    
    if ($socket) {
      #creamos el envio
      $envio = DB::table('envio')->insertGetId($envio);
      fwrite($socket, $envio);
      fclose($socket);
      if ($test == 0) {
        Session::flash('valid', "Su envio ha sido recibido correctamente con id {$envio}");
      } else {
        Session::flash('valid', "Se ha testeado correctamente su código");
      }
    } else {
      Session::flash("invalid", "Ha ocurrido un problema con su envio");
      //envio::destroy($envio);
    }

    if ($tipo == 1) { # si es evaluacion
      return Redirect::to("curso/ver/{$curso}/evaluacion/{$codigo}/{$ejercicio}");
    } else { # si es de un taller
      if ($test == 1) { //esperamos un poco
        sleep(7);
      }else{
        sleep(2); //espermaos un poquito
      }
      return Redirect::to("curso/ver/{$curso}/ejercicio/{$ejercicio}");
    }
  }

  #funcion para agregar un ejercicio a un taller

  public function postAgregarEjercicio() {

    $taller = Session::get('modulo_profesor');
    $ejercicio = Input::get('ejercicio');

    DB::table('ejercicio_x_taller')->insert(
            array(
                'taller' => $taller,
                'ejercicio' => $ejercicio,
                'tipo_entrada' => Input::get('tipo_entrada'),
                'time_limit' => Input::get('time_limit'),
                'prioridad' => ejercicioxtaller::get_maxima_prioridad($taller) + 1 # se asigna nueva prioridad
            )
    );
    Session::flash("valid", "Ejercicio añadido correctamente");
    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  public function getEliminarEjercicio($ejercicio) {
    $ejercicio = ejercicio::find($ejercicio);
    $error = false;
    if ($ejercicio) {
      if ($ejercicio->profesor == Auth::user()->id) {
        DB::table('ejercicio_x_taller')
                ->where('ejercicio', $ejercicio->id)
                ->where('taller', Session::get('modulo_profesor'))->delete();
        Session::flash("valid", "Ejercicio eliminado del taller correctamente");
      } else {
        $error = true;
      }
    } else {
      $error = true;
    }
    if ($error) {
      Session::flash("invalid", "No tiene permisos sobre este objeto");
    }

    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  //funcion que retorna informacion de un ejercicio dentro de un taller en formato json
  public function postInfojson() {
    // echo Input::get('ejercicio')  ." - " . Session::get('modulo');
    // exit;
    $ejercicio = Input::get('ejercicio');
    $modulo = Session::get('modulo_profesor');
    $resultado = DB::table('ejercicio')
            ->join('ejercicio_x_taller', 'ejercicio.id', '=', 'ejercicio_x_taller.ejercicio')
            ->where('ejercicio.id', $ejercicio)
            ->where('ejercicio_x_taller.ejercicio', $ejercicio)
            ->where('ejercicio_x_taller.taller', $modulo)
            ->first();

    return Response::json($resultado);
  }

  #funcion que edita un ejercicio en un taller

  public function postEditarEjercicio() {

    //dd(Input::all());

    $ejercicio = Input::get('ejercicio');
    $old = Input::get('old_ejercicio');
    $taller = Session::get('modulo_profesor');



    DB::table('ejercicio_x_taller')->where('ejercicio', $old)->where('taller', $taller)->update(
            array(
                'taller' => $taller,
                'ejercicio' => $ejercicio,
                'tipo_entrada' => Input::get('tipo_entrada'),
                'time_limit' => Input::get('time_limit')
            )
    );

    Session::flash("valid", "Ejercicio editado correctamente");
    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  #funcion para mover un ejercicio con su prioridad
  #funcion que configura las opciones avanzadas

  public function postOpcionesavanzadas() {

    $data = Input::except(array('_token'));


    $record['tiene_inicio'] = isset($data['tiene_inicio']) ? 0 : 1;
    $record['tiene_fin'] = isset($data['tiene_fin']) ? 0 : 1;



    //fechas
    if ($record['tiene_inicio'] == 1) {
      $record['fecha_inicio'] = $data['fecha_inicio'];
    }
    if ($record['tiene_fin'] == 1) {
      $record['fecha_fin'] = $data['fecha_fin'];
    }

    //envios tardios
    if (isset($data['envios_tardios'])) {
      $record['tiempo_disminucion'] = $data['tiempo_disminucion'];
      $record['unidad_disminucion'] = $data['unidad_disminucion'];
      $record['porcentaje_disminucion'] = $data['porcentaje_disminucion'];
      $record['envios_tardios'] = true;
    } else {
      $record['envios_tardios'] = false;
    }

    //disminucion puntaje 
    if (isset($data['disminucion_puntaje'])) {
      $record['disminucion_puntaje'] = true;
    } else {
      $record['disminucion_puntaje'] = false;
    }


    $taller = Session::get('modulo_profesor');

    // var_dump($taller);
    //  exit;

    $taller = taller::find($taller);
    if ($taller) {
      $taller->update($record);
      Session::flash("valid", "Opciones avanzadas del taller configuradas correctamente");
    } else {
      Session::flash("invalid", "Ha ocurrido un error");
    }


    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  #funcion que cambia la prioridad de un ejercicio
}
