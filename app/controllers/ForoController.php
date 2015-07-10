<?php

#controlador que maneja la informacion relacionada con temas y respuestas en un foro

class ForoController extends LMSController {

  public function postCrearTema() {

    $curso = curso::find(Session::get('curso.estudiante'));
    $usuario = usuario::find(Auth::user()->id);
    $tema = [
        'nombre' => Input::get('nombre'),
        'descripcion' => Input::get('descripcion'),
        'curso' => $curso->id,
        'usuario' => $usuario->id
    ];
    //se va a crear el tema de un foro
    temaforo::create($tema);
    //se puso relenteo desde que le agregué eso
    $estudiantes = $curso->get_estudiantes();
    foreach ($estudiantes as $estudiante) {
      if ($estudiante->id != $usuario->id) {
        alerta::create(
                array(
                    'from' => $usuario->id,
                    'to' => $estudiante->id,
                    'enlace' => url("curso/ver/{$curso->id}/foro"),
                    'mensaje' => $usuario->nombres . " ha creado un nuevo tema en el foro",
                    'curso' => $curso->id,
                )
        );
      }
    }

    #se crea la alerta para todos los estudiantes del curso

    Session::flash("valid", "Tema creado correctamente");
    $ff = "esot es un string";

    return Redirect::to("curso/ver/" . $curso->id . "/foro");
  }

  public function getEliminarTema($tema) {
    $curso = Session::get('curso.estudiante');
    $tema = temaforo::find($tema);

    if ($tema->usuario == Auth::user()->id) { #el usuario es dueño del tema
      temaforo::destroy($tema->id);
      Session::flash("valid", "Tema eliminado correctamente");
    }


    return Redirect::to("curso/ver/" . $curso . "/foro");
  }

  public function postResponder($tema) {


    $usuario = usuario::find(Auth::user()->id);
    $curso = curso::find(Session::get('curso.estudiante'));
    $tema = temaforo::find($tema);

    $respuesta = [
        'usuario' => $usuario->id,
        'tema_foro' => $tema->id,
        'respuesta' => Input::get('respuesta')
    ];

    respuestaforo::create($respuesta);
    #creamos la alerta de que alguien ha respondido en un tema en que has participado

    $participantes = $tema->get_participantes();

    foreach ($participantes as $participante) {
      if ($participante->usuario != $usuario->id && $participante->usuario != $tema->usuario) {

        #creamos la alerta
        alerta::crear($participante->usuario, $usuario->id, url("curso/ver/{$curso->id}/foro/{$tema->id}"), 3, $usuario->nombres . " ha respondido en un tema del foro en el que has participado");
      }
    }
    #le enviamos la notificacion al que creo el tema

    if ($tema->usuario != $usuario->id)
      alerta::crear($tema->usuario, $usuario->id, url("curso/ver/{$curso->id}/foro/{$tema->id}"), 4, $usuario->nombres . " ha respondido en un tema que creaste");

    Logros::check250(Auth::user()->id, $curso->id);  // verificamos si comenta 10 veces en el foro

    Session::flash("valid", "HAS RESPONDIDO EN ESTE TEMA");

    return Redirect::to("curso/ver/{$curso->id}/foro/{$tema->id}");
  }

  public function getEliminarRespuesta($respuesta, $tema) {

    $respuesta = respuestaforo::find($respuesta);

    #si existe la respuesta y el usuario logueado es el dueño
    if ($respuesta && Auth::user()->id == $respuesta->usuario) {
      respuestaforo::destroy($respuesta->id);
      Session::flash('valid', "La respuesta fue eliminada correctamente");
    }
    return Redirect::to('curso/ver/' . Session::get('curso.estudiante') . '/foro/' . $tema);
  }

}

?>
