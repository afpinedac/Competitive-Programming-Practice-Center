<?php

class NotificacionController extends LMSController {

  public function postMeGusta() {

    if (Request::ajax()) {

      $usuario = Input::get('usuario');
      $notificacion = notificacion::find(Input::get('notificacion'));

      $tipo = '1';
      if ($notificacion && !$notificacion->gusta($usuario)) {
        DB::table('me_gusta')->insert(array(
            'notificacion' => $notificacion->id,
            'usuario' => $usuario
        ));
        #se crea la alerta
        alerta::crear($notificacion->usuario, $usuario, url("curso/ver/{$notificacion->curso}/inicio#p{$notificacion->id}"), 1, "A " . usuario::find($usuario)->nombres . " le gusta tu publicación");
      } else if ($notificacion) {
        $tipo = '0';
        DB::table('me_gusta')
                ->where('notificacion', $notificacion->id)
                ->where('usuario', $usuario)
                ->delete();
      }


      if ($notificacion) {
        Logros::redes_sociales(Auth::user()->id, $notificacion->curso, $notificacion->usuario);
      }
       return Response::json(['tipo' => $tipo]);
    }
  }

  #funcion que elimina una notificacion

  public function postEliminar() {
    if (Request::ajax()) {
      $notificacion = notificacion::find(Input::get('notificacion'));
      if ($notificacion->usuario == Auth::user()->id) {
        DB::table('notificacion')->where('id', $notificacion->id)->delete();
        return 1;
      }
    }
  }

  #funcion que actualiza cuando se ve una alerta

  public function postVerAlerta() {

    if (Request::ajax()) {
      $alerta = alerta::find(Input::get('alerta'));

      if ($alerta->to == Auth::user()->id) {

        $alerta->visto = true;
        $alerta->save();
      }

      $arr['alerta'] = $alerta->id;
      $arr['nalertas'] = usuario::find(Auth::user()->id)->get_alertas('c');

      return Response::json($arr);
    }
  }

  #funcion que comenta una notificacion

  public function postComentar() {
    

    if (Request::ajax()) {

      $notificacion = notificacion::find(Input::get('notificacion'));

      if ($notificacion && Session::get('curso.estudiante') == $notificacion->curso) {
        $comentario = DB::table('notificacion')->insertGetId(array(
            'curso' => $notificacion->curso,
            'usuario' => Auth::user()->id,
            'tipo' => 5, //el tipo comentario, el codigo es la notificacion a la que pertenece
            'codigo' => $notificacion->id,
            'publicacion' => Input::get('comentario'),
            'created_at' => date('Y-m-d')
        ));

        $comentador = usuario::find(Auth::user()->id);
        $info = array(
            'id' => $comentario,
            'comentadorid' => $comentador->id,
            'nombres' => $comentador->nombres,
            'apellidos' => $comentador->apellidos,
            'publicacion' => Input::get('comentario')
        );

        #creamos la alerta
        if ($comentador->id != $notificacion->usuario) {
          alerta::crear($notificacion->usuario, $comentador->id, url("curso/ver/{$notificacion->curso}/inicio#c{$notificacion->id}"), 2, $comentador->nombres . " ha comentado tu publicación");
        }
        Logros::check251(Auth::user()->id, $notificacion->curso); //chequeamos el logro para ver si gana por comentar
        return Response::json($info);
      } else {
        return "0";
      }
    }
  }

  public function postEliminarComentario() {

    if (Request::ajax()) {
      $comentario = notificacion::find(Input::get('comentario'));
      if ($comentario && Auth::user()->id == $comentario->usuario) {
        notificacion::destroy($comentario->id);
        return "1";
      } else {
        return "0";
      }
    }
  }

  #funcion que retorna los estudiantes que les ha gustado una notificacon

  public function postLikes() {
    $notificacion = notificacion::find(Input::get('notificacion'));

    if ($notificacion && $notificacion->curso == Session::get('curso.estudiante')) {

      $usuarios = $notificacion->get_a_quien_le_gusta();

      return Response::json($usuarios);
    } else {
      echo "0";
    }
  }

  ############### FACEBOOK METHODS #########################
  //funcion que comparte un contenido en facebook

  public function getLoginFacebook($notificacion) {

    $notificacion = notificacion::find($notificacion);

    #existe la notificacion
    if (!$notificacion) {
      return Redirect::to('curso');
    }

    #si va a postear algo que no es de él
    if (Auth::user()->id == $notificacion->usuario) {
      Session::put('state', md5(uniqid(rand(), TRUE))); // CSRF protection
      Session::flash('notificacion', $notificacion->id); //Guardamos la notificacion que queremos
      Session::flash('curso', $notificacion->curso); //Guardamos la notificacion que queremos
      $dialog_url = "https://www.facebook.com/dialog/oauth?client_id="
              . $this->redes_sociales['facebook']['app_id'] . "&redirect_uri=" . URL::to('/notificacion/compartir-en-facebook') . "&state="
              . Session::get('state') . "&scope=email,publish_stream,status_update,offline_access";

      echo("<script> top.location.href='" . $dialog_url . "'</script>");
    } else {
      return Redirect::to('curso');
    }
  }

  #funcion que muesta el logro ganado por un usario

  public function getVer($logro) {

    return Redirect::to("/ver-logro/{$logro}");
  }

  //funcion que postea en facebook
  public function getCompartirEnFacebook() {

    $notificacion = notificacion::find(Session::get('notificacion'));
    $app_id = $this->redes_sociales['facebook']['app_id'];
    $app_secret = $this->redes_sociales['facebook']['app_secret'];

    $my_url = URL::to('/notificacion/compartir-en-facebook');


    if (Session::get('state') && (Session::get('state') === Input::get('state'))) {
      $code = Input::get("code");
      $token_url = "https://graph.facebook.com/oauth/access_token?"
              . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
              . "&client_secret=" . $app_secret . "&code=" . $code;




      $response = $this->get_data($token_url);
      $params = null;
      parse_str($response, $params);
      Session::put('access_token', $params['access_token']);

      $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];

      $user = json_decode($this->get_data($graph_url));





      if ($notificacion->tipo == 0) {
        $parameters = array(
            'message' => $notificacion->publicacion
        );
      } else {

        $logro = logro::find(DB::table('curso_x_logro_x_usuario')->where('id', $notificacion->codigo)->first()->logro);

        $parameters = array(
            'message' => $this->get_texto_a_publicar($notificacion->id, 'f'),
            'description' => $logro->descripcion,
            'picture' => url("/img/logros/{$logro->codigo}.png"), //cambiar aqui por una ruta fija
            'link' => $this->get_link_notificacion($notificacion->id)
        );
      }
      $parameters['access_token'] = $params['access_token'];

      $error = false;

      try {
        $feed_URL = 'https://graph.facebook.com/' . $user->id . '/feed';

        $post = $this->hp($feed_URL, http_build_query($parameters));
      } catch (Exception $e) {
        $error = true;
      }


      if ($error) {
        Session::flash('invalid', 'Se ha producido un problema publicando, Inténtalo mas tarde');
      } else {
        #guardar en la bd la publicacion
        $notificacion->compartida_facebook = true;
        $notificacion->save();

        #chequeamos si gana algun logro 
        Logros::redes_sociales(Auth::user()->id, Session::get('curso'));


        Session::flash('valid', 'Publicacion compartida en Facebook!');
        //var_dump(Session::get('curso.estudiante'));
        // exit;
        return Redirect::to('curso/ver/' . Session::get('curso') . "/inicio");
      }
    }
  }

#------------------------------------------------------------------
  #funciones para poder postear en facebook

  function hp($uri, $postdata) {
    $html_brand = $uri;
    $ch = curl_init();

    $options = array(
        CURLOPT_URL => $html_brand,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_AUTOREFERER => true,
        CURLOPT_CONNECTTIMEOUT => 120,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_PROXY => Config::get('app.proxy'),
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postdata,
        CURLOPT_SSL_VERIFYPEER => false
    );
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    # var_dump(curl_getinfo($ch));
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode != 200) {
      echo "Return code is {$httpCode} \n"
      . curl_error($ch);
    } else {
      echo "<pre>" . htmlspecialchars($response) . "</pre>";
    }

    curl_close($ch);
  }

  function parse_signed_request($signed_request, $secret) {
    list($encoded_sig, $payload) = explode('.', $signed_request, 2);

    // decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);

    if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
      error_log('Unknown algorithm. Expected HMAC-SHA256');
      return null;
    }

    // check sig
    $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
    if ($sig !== $expected_sig) {
      error_log('Bad Signed JSON signature!');
      return null;
    }

    return $data;
  }

  function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
  }

  function get_data($url) {
    // Create context stream
    //'proxy.medellin.unal.edu.co:80'
    $context_array = array('http' => array('proxy' => Config::get('app.proxy'), 'request_fulluri' => true));
    $context = stream_context_create($context_array);

    // Use context stream with file_get_contents
    $data = file_get_contents($url, false, $context);

    // Return data via proxy
    return $data;
  }

  #########   END FACEBOK FUNCTIONS #########################3
  //-------------------------------------------------------------------
  ######## TWITTER FUNCTIONS #######################

  public function getLoginTwitter($notificacion) {
##validar si el usuario es el dueñ  o de la notificacion


    $notificacion = notificacion::find($notificacion);

    if ($notificacion->usuario == Auth::user()->id) {
      Session::flash('notificacion', $notificacion->id); #Guardamos la notificacion que queremos
      require '../public/libs/twitteroauth/twitteroauth.php';
      $CK = $this->redes_sociales['tw']['consumer_key'];
      $CS = $this->redes_sociales['tw']['consumer_secret'];
      $OC = URL::to('notificacion/compartir-en-twitter');


      if (Session::has('twitter_status') && Session::get('twitter_status')) {  // if the user is already logged
        //   exit;
        $tw = new TwitterOAuth($CK, $CS, Session::get('twitter_token'), Session::get('twitter_token_secret'));
        $data = array('status' => $this->get_texto_a_publicar($notificacion->id, 't'));
        #agreagar lo de los tiempos de posteo....  
        $error = false;
        try {
          $response = $tw->post('statuses/update', $data);
        } catch (Exception $e) {
          $error = true;
        }
      } else {

        $tw = new TwitterOAuth($CK, $CS);
        $tw_temp = $tw->getRequestToken($OC);
        Session::put('temp_token', $tw_temp['oauth_token']);
        Session::put('temp_token_secret', $tw_temp['oauth_token_secret']);
        $tw_url = $tw->getAuthorizeURL($tw_temp['oauth_token']);
        echo("<script> top.location.href='" . $tw_url . "'</script>");
      }



      if ($error) {
        Session::flash('invalid', 'Se ha producido un problema twitteando, Intentalo más tarde');
      } else {
        #guardar en la bd la publicacion
        $notificacion->compartida_twitter = true;
        $notificacion->save();
        #se chequea si eso lo hace ganar algo
        Logros::redes_sociales(Auth::user()->id, $notificacion->curso);


        Session::flash('valid', 'Publicación Compartida en Twitter!');
        //var_dump(Session::get('curso.estudiante'));
        return Redirect::to('curso/ver/' . $notificacion->curso . "/inicio");
      }
    } else {
      return Redirect::to('curso');
    }
  }

  #Funcion de callback cuando se ha logueado en twitter

  public function getCompartirEnTwitter() {

    require '../public/libs/twitteroauth/twitteroauth.php';


    $CK = $this->redes_sociales['tw']['consumer_key'];
    $CS = $this->redes_sociales['tw']['consumer_secret'];

    $tw = new TwitterOAuth($CK, $CS, Session::get('temp_token'), Session::get('temp_token_secret'));

    $tw_token = $tw->getAccessToken(Input::get('oauth_verifier'));


    if ($tw->http_code == 200) {
      Session::put('twitter_token', $tw_token['oauth_token']);
      Session::put('twitter_token_secret', $tw_token['oauth_token_secret']);
      Session::put('twitter_status', true);
      Session::put('twitter_user_id', $tw_token['user_id']);
      return Redirect::to('notificacion/login-twitter/' . Session::get('notificacion'));
    } else {

      Session::flash("invalid", "Se ha generado un error, código del error :  {$tw->http_info}");
      return Redirect::to("curso/ver/" . Session::get('curso.estudiante'));
    }

    ######### END TWITTER FUCNTIONS ####################
  }

  #funcion que retorna el texto a publicar en una red social,
  #puede devolver un false en caso que la publicacion contenga mas de X caracteres
  ## o en caso que la el tiempo de la API no lo permita

  private function get_texto_a_publicar($id, $rs) {

    $notificacion = notificacion::find($id);
    $message = "";
    $link = $this->get_link_publicacion($id);


    if ($notificacion->tipo == 0) {  #si es una publicacion
      if ($rs == 'f') {
        $message = $notificacion->publicacion;
      } else if ($rs == 't') {
        $message = $this->recortar_texto($notificacion->publicacion, $this->redes_sociales['tw']['MAX_LENGTH_PUBLICATION'] - strlen($link));
      }
    } else { #si es un logro
      $logro = logro::get_info_logro($notificacion->codigo);
      $message = "He conseguido el logro: " . $logro->nombre;

      if ($rs == 't') {
        $message = $this->recortar_texto($message, $this->redes_sociales['tw']['MAX_LENGTH_PUBLICATION'] - strlen($link) - 1) . " " . $this->get_link_publicacion($notificacion->id);
      }
    }

    return $message;
  }

  private function recortar_texto($str, $len) {
    $c = strlen($str);
    if ($c >= $len) {
      return substr($str, 0, $len - 3) . "...";
    }
    return $str;
  }

  private function get_link_publicacion($id) {
    return " 'LMS-GAMIFIED' " . $this->get_link_notificacion($id);
  }

  private function get_link_notificacion($id) {
    return URL::to('/ver-logro/') . '/' . LMSController::encoder($id);
  }

  public function postJson($type) {
    switch ($type) {
      case 'comentarios':
        $notificacion = notificacion::find(Input::get('notificacion'));
        return Response::json($notificacion->get_comentarios());
        break;
      case 'nlikes':
        $notificacion = notificacion::find(Input::get('notificacion'));
        return Response::json($notificacion->numero_de_me_gusta());
      default:
        return Response::json(['error' => 'no existe un dato para este request']);
        break;
    }
  }

}
