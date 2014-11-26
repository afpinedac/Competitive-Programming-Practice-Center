<?php

class EvaluacionController extends LMSController {

  public function postCrear() {
    //dd(Input::all());

    $data = Input::except(array('_token'));

    $data['fecha_activacion'] = date('Y-m-d H:i:s', strtotime($data['fecha_activacion']));
    $data['modulo'] = Session::get('modulo_profesor');

    $eval = DB::table('evaluacion')->insertGetId($data);

    Session::flash('valid', "La evaluación '{$data['nombre']}' se creó correctamente");  // para mostrar en verde la evaluacion que se creó        


    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  //funcion que agrega un ejercicio a una evaluación
  public function postAgregarEjercicio() {
    if (Request::ajax()) {
      $ee = DB::table('ejercicio_x_evaluacion')->insertGetId(Input::all());

      //obtenemos los datos para devolverlos en el json
      $ejercicio = ejercicio::find(Input::get('ejercicio'));
      $evaluacion = Input::get('evaluacion');


      $respuesta = ejercicioxevaluacion::where('ejercicio', $ejercicio->id)->where('evaluacion', $evaluacion)->first()->tipo_entrada == 0 ? 'out' : 'código completo';

      $arr = array(
          'id' => $ejercicio->id,
          'nombre' => $ejercicio->nombre,
          'n' => ejercicioxevaluacion::where('evaluacion', $evaluacion)->count(),
          'respuesta' => $respuesta
      );

      return Response::json($arr);
    }
  }

  public function getEliminar($evaluacion) {

    $evaluacion = evaluacion::find($evaluacion);



    if ($evaluacion) {
      #validamos que el profesor pueda eliminar la evaluacion
      if (Auth::user()->id == curso::find(modulo::find($evaluacion->modulo)->curso)->profesor_id) {

        evaluacion::destroy($evaluacion->id);
        Session::flash("valid", "La evaluación fue eliminada correctamente");
      }
    } else {
      Session::flash("invalid", "La acción es inválida");
    }

    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  #funcion que devuelve los ejercicios que una evaluación tiene y los ejercicios que son agregables
  #warning: verificar que se pueda eliminar;

  public function postInformacion() {
    if (Request::ajax()) {

      $evaluacion = Input::get('evaluacion');

      $response['posibles'] = evaluacion::find($evaluacion)->get_posibles_ejercicios();
      $response['actuales'] = evaluacion::find($evaluacion)->get_ejercicios();

      return Response::json($response);
    }
  }

  #funcion para eliminar un ejercicio de una evaluación

  public function postEliminarEjercicio() {
    if (Request::ajax()) {

      $ejercicio = Input::get('ejercicio');
      $evaluacion = Input::get('evaluacion');

      DB::table('ejercicio_x_evaluacion')
              ->where('ejercicio', $ejercicio)
              ->where('evaluacion', $evaluacion)
              ->delete();

      $ejercicio = ejercicio::find($ejercicio);
      $arr = array(
          'id' => $ejercicio->id,
          'nombre' => $ejercicio->nombre,
          'n' => ejercicioxevaluacion::where('evaluacion', $evaluacion)->count()
      );


      return Response::json($arr);
    }
  }

  #funcion que retorna la informacion de una evaluacion en formato en json

  public function postInfojson() {
    if (Request::ajax())
      return Response::json(evaluacion::find(Input::get('evaluacion')));
  }

  #funcion que edita una evaluación

  public function postEditar() {


    $data = Input::except(array('_token'));
    $data['fecha_activacion'] = date('Y-m-d H:i:s', strtotime($data['fecha_activacion']));

    $evaluacion = array(
        'nombre' => Input::get('nombre'),
        'descripcion' => Input::get('descripcion'),
        'porcentaje_aprobacion' => Input::get('porcentaje_aprobacion'),
        'fecha_activacion' => date('Y-m-d H:i:s', strtotime($data['fecha_activacion'])),
        'duracion' => Input::get('duracion')
    );



    $eval = Input::get('evaluacion');
    evaluacion::where('id', $eval)->update($evaluacion);

    Session::flash('valid', "La evaluación se editó correctamente");  // para mostrar en verde la evaluacion que se creó       


    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  public function getRanking($id) {
    $evaluacion = evaluacion::find($id);

    //if ($evaluacion && usuario::find(Auth::user()->id)->es_propietario(modulo::find($evaluacion->modulo)->curso)) {
      
      return View::make('curso.evaluacion.ranking')
              ->with('evaluacion', $evaluacion);
                     
   // } else {
   //   Session::flash('invalid', "No tiene permisos para ver este recurso");
    //  return Redirect::to("/curso");
    //}
  }

  public function getJson($id) {

    $evaluacion = evaluacion::find($id);
    $ejercicios = $evaluacion->get_ejercicios();
    $curso = curso::find(modulo::find($evaluacion->modulo)->curso);
    $estudiantes = $curso->get_estudiantes(false);


    $data = [];
    foreach ($estudiantes as $estudiante) {

      $arr = [
          'nombre_completo' => ucfirst($estudiante->nombres) . " " . ucfirst($estudiante->apellidos),
      ];

      $puntos = [];
      $puntos_totales = 0;
      $nombre_ejercicios = [];
      foreach ($ejercicios as $ejercicio) {
        $nombre_ejercicios[] = $ejercicio->nombre;
        $puntos_obtenidos = $evaluacion->puntos_en_ejercicio_para_estudiante($estudiante->id, $ejercicio->id);
        $puntos[] = $puntos_obtenidos;
        $puntos_totales += $puntos_obtenidos;
      }


      $arr['puntos'] = $puntos;
      $arr['puntos_totales'] = $puntos_totales;

      $data[] = $arr;
    }
    
    
    $res['points'] = $data;
    $res['ejercicios'] = $nombre_ejercicios;
    
    
   return Response::json($res); 
  }

}
