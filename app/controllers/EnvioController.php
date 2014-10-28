<?php

class EnvioController extends LMSController {
  #TODO::terminar
  #retorna todos los envios que se hacen despues del time

  public function getAll($curso = 4) { //recibe el id del curso
    $time = time() - (60 * 1000* 80 * 200 );    
    $time = date("Y-m-d H:i:s", $time);
  //  echo "--> {$curso}";
    $envios = DB::table('envio')
    ->where('created_at', '>', $time)
            ->where('curso', $curso)
            ->get();

    $data = array();
    foreach ($envios as $envio) {
      $sub['id'] = $envio->id;
      $sub['ejercicio'] = $envio->ejercicio;
      $sub['estudiante'] = $envio->usuario;
      $sub['respuesta'] = $envio->resultado;
      $res = $sub['respuesta'];
      $sub['color'] = ($res == 'accepted')? 'info' : ($res == 'wrong answer')? 'alert' :  ($res == 'time limit')? 'info' : ($res == 'compilation error')? 'success' : '';
      
      $data[] = $sub;
    }
    return Response::json($data);
  }

}
