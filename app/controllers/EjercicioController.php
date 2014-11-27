<?php

class EjercicioController extends LMSController {

  public function postCrear() {
    $tipo_formulacion = Input::get('tipo_formulacion') == "0" ? 0 : 1;
    // var_dump($tipo_formulacion);


    $ejer = DB::table('ejercicio')->insertGetId(array(
        'profesor' => Auth::user()->id,
        'nombre' => Input::get('nombre'),
        'tipo_formulacion' => $tipo_formulacion,
        'formulacion' => Input::get('formulacion'),
        'created_at' => date('Y-m-d H:i:s')
    ));

    #guarda los archivos de entrada y salida en la base de datos
    if (Input::hasFile('archivo_entrada')) {
      $file_in = $ejer . $this->extension['in'];
      Input::file('archivo_entrada')->move($this->ruta['in'], $file_in);
      $in = LMSController::get_text_file($this->ruta['in'] . '/' . $file_in);
      ejercicio::find($ejer)->update(array('in' => $in));
      @unlink($this->ruta['in'] . '/' . $file_in);
    }


    if (Input::hasFile('archivo_salida')) {
      $file_out = $ejer . $this->extension['out'];
      Input::file('archivo_salida')->move($this->ruta['out'], $file_out);
      $out = LMSController::get_text_file($this->ruta['out'] . '/' . $file_out);
      ejercicio::find($ejer)->update(array('out' => $out));
      @unlink($this->ruta['out'] . '/' . $file_out);
    }



    # si hay la formulacion es un archivo  lo guarda en el servidor
    if ($tipo_formulacion == 1) {

      Input::file('archivo_formulacion')->move($this->ruta['formulacion'], LMSController::encoder($ejer) . $this->extension['formulacion']);
    }




    # si seleccionó que desea agregarlo a un taller

    $revision = Input::has('revision') ? 1 : 0;

    if (Input::get('taller') != -1) {
      $taller = Input::get('taller');
      DB::table('ejercicio_x_taller')->insert(
              array(
                  'ejercicio' => $ejer,
                  'taller' => $taller,
                  'tipo_entrada' => Input::get('tipo_entrada'),
                  'revision' => $revision,
                  'time_limit' => Input::get('time_limit'),
                  'prioridad' => ejercicioxtaller::get_maxima_prioridad($taller) + 1 # se asigna nueva prioridad
              )
      );
    }

    Session::flash("valid", 'Ejercicio creado correctamente');

    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  public function getEliminar($ejercicio) {
    $ejercicio = ejercicio::find($ejercicio);

    if ($ejercicio && $ejercicio->profesor == Auth::user()->id) {

      #eliminamos el archivo si tiene formulacion

      if ($ejercicio->tipo_formulacion == 1) {
        @unlink($this->ruta['formulacion'] . '/' . LMSController::encoder($ejercicio->id) . ".pdf");
      }
      ejercicio::destroy($ejercicio->id);

      Session::flash("valid", 'Ejercicio eliminado correctamente');
    } else {
      Session::flash("invalid", "Usted no tiene permisos sobre este objeto");
    }

    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar/lista-ejercicios');
  }

  public function getEnvios($curso, $ejercicio, $taller) {

    $envios = envio::get_envios($curso, $ejercicio, Auth::user()->id, 0, $taller);

    return View::make('curso.contenido.components.envios')
                    ->with('envios', $envios)
                    ->with('veredicto', $this->veredicto);
  }

  public function getDescargarIn($ejercicio) {
    $ejercicio = ejercicio::find(LMSController::decoder($ejercicio));
    if ($ejercicio) {
      $filename = Auth::user()->id . "_" . time() . "_" . $ejercicio->id . ".txt";
      LMSController::create_text_file($this->ruta['in'] . "/" . $filename, $ejercicio->in);

      return Response::download($this->ruta['in'] . '/' . $filename, "[in]-{$ejercicio->id}.txt");
    } else {
      Session::flash("invalid", 'No tiene permisos sobre este objeto');
    }


    return Redirect::to("curso/all");
  }

  #funcion que descarga la formulacion de un ejercicio

  public function getDescargarFormulacion($ejercicio) {
    $hash = $ejercicio;
    $ejercicio = ejercicio::find(LMSController::decoder($ejercicio));
    if ($ejercicio) {
      //var_dump($ejercicio->nombre);
      // exit;
      return Response::download($this->ruta['formulacion'] . "/{$hash}.pdf", "formulacion");
    } else {
      Session::flash("invalid", "No tiene permisos sobre este objeto");
    }
    return Redirect::to("curso/all");
  }

  #aumenta la prioridad de un ejercicio en un taller

  public function getCambiarPrioridad($curso, $taller, $ejercicio, $tipo = 'u') {

    $taller = taller::find($taller);
    $ejercicio = ejercicio::find($ejercicio);
    $curso = curso::find($curso);
    $usuario = usuario::find(Auth::user()->id);



    if ($curso && $taller && $ejercicio && $curso->tiene_taller($taller->id) && $usuario->es_propietario($curso->id) && $taller->tiene_ejercicio($ejercicio->id)) {
      //   echo "pasa";
      $tipo = $tipo == "u" ? '<' : '>';
      $e = ejercicioxtaller::buscar_ejercicio_siguiente_prioridad($taller->id, $ejercicio->id, $tipo);
      // var_dump($e);
      #si existe el ejercicio
      if ($e) {
        ejercicioxtaller::cambiar_prioridad($taller->id, $e, $ejercicio->id);
        Session::flash("valid", "Prioridad cambiada correctamente");
      }



      return Redirect::to("curso/ver/{$curso->id}/editar");
    } else {
      Session::flash("invalid", 'No tiene permisos sobre este objeto');
    }

    return Redirect::to("curso/all");
  }

  #funcion que edita un ejercicio

  public function postEditar() {

    //   dd(Input::all());
    $ejercicio = ejercicio::find(Crypt::decrypt(Input::get('ejercicio')));

    if ($ejercicio && $ejercicio->profesor == Auth::user()->id) {
      $ejercicio->nombre = Input::get('nombre');

      $ejercicio->tipo_formulacion = Input::get('tipo_formulacion');
      if ($ejercicio->tipo_formulacion == 0) { # si
        $ejercicio->formulacion = Input::get('formulacion0');
      } else { #si la formulacion es tipo 1 osea con un pdf
        #miramos si va a cambiar la formulacion
        if (Input::hasFile('formulacion1')) {
          Input::file('formulacion1')->move($this->ruta['formulacion'], LMSController::encoder($ejercicio->id) . $this->extension['formulacion']);
        }
      }

      #miramos si cambio el input y el output
      #guarda los archivos de entrada y salida en la base de datos
      if (Input::hasFile('in')) {
        $file_in = $ejercicio->id . $this->extension['in'];
        Input::file('in')->move($this->ruta['in'], $file_in);
        $in = LMSController::get_text_file($this->ruta['in'] . '/' . $file_in);
        $ejercicio->in = $in;
        @unlink($this->ruta['in'] . '/' . $file_in);
      }

      #el out
      if (Input::hasFile('out')) {
        $file_out = $ejercicio->id . $this->extension['out'];
        Input::file('out')->move($this->ruta['out'], $file_out);
        $out = LMSController::get_text_file($this->ruta['out'] . '/' . $file_out);
        $ejercicio->out = $out;
        @unlink($this->ruta['out'] . '/' . $file_out);
      }


      $ejercicio->save();
      Session::flash("valid", 'Ejercicio actualizado correctamente');


      return Redirect::to("curso/ver/" . Session::get('curso') . "/editar/lista-ejercicios/{$ejercicio->id}");
    } else {
      Session::flash("invalid", 'Usted no tiene permiso sobre este objeto');
    }
  }

  //funcion que acepta la respuesta de un algoritmo
  public function postAceptar($ver = 1) {
    $envio = envio::find(Input::get('envio'));
    if ($envio && $envio->usuario == Auth::user()->id) {
      if ($ver == 1) {
        $envio->visto = 1;
        $envio->save();
      }
      return Response::json($envio);
    }
  }

  public function postCalcularPuntos() {
    $envio = envio::find(Input::get('envio'));
    Juez::evaluar_envios($envio->curso);
    return Response::json(envio::find($envio->id));
  }

}

?>
