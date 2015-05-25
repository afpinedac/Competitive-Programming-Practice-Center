<?php

class MensajeController extends LMSController {

  public function postEnviar() {

    $data = Input::except(array('_token'));
    $data['remitente'] = Crypt::decrypt($data['remitente']);

    #validaciones        
    if ($data['remitente'] == Auth::user()->id) {
      $respuesta_a = isset($data['respuesta_a']) ? Crypt::decrypt($data['respuesta_a']) : null;

      #verificamos si el mensaje es para todos
      if ($data['destinatario'] == 0) {

        $amigos = usuario::find(Auth::user()->id)->get_amigos(Session::get('curso.estudiante'));
        foreach ($amigos as $amigo) {
          $this->enviar_mensaje(Auth::user()->id, $amigo->id, $data['asunto'], $data['mensaje'], $respuesta_a);
        }
      } else {
        $this->enviar_mensaje(Auth::user()->id, $data['destinatario'], $data['asunto'], $data['mensaje'], $respuesta_a);
      }
      Session::flash('valid', "El mensaje ha sido enviado");
    } else {
      echo "no se envia";
    }

    return Redirect::to('curso/ver/' . Session::get('curso.estudiante') . '/mensajes');
  }

  private function enviar_mensaje($de, $para, $asunto, $mensaje, $respuesta_a) {
    mensaje::create(
            array(
                'remitente' => $de,
                'destinatario' => $para,
                'asunto' => $asunto,
                'mensaje' => $mensaje,
                'curso' => Session::get('curso.estudiante'),
                'respuesta_a' => $respuesta_a
    ));

    #creamos la alerta
    alerta::create(
            array(
                'from' => $de,
                'to' => $para,
                'enlace' => url("curso/ver/" . Session::get('curso.estudiante') . "/mensajes"),
                'mensaje' => usuario::find($de)->nombres . " te ha enviado un nuevo mensaje",
                'curso' => Session::get('curso.estudiante')
            )
    );
  }

}
