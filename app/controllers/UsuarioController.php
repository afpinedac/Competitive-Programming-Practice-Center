<?php

class UsuarioController extends LMSController  {

  public function postEditar() {

    $data = Input::except(array('_token'));
    $data['id'] = Auth::user()->id;
    // dd(Input::all());

    $validator = Validator::make($data, $this->rules['usuario_registrado'], $this->rules['mensajes']);

    if ($validator->passes()) {
      if ($this->cambio_de_email_valido($data['id'], $data['email'])) {

        #si el usuario cambio de genero
        if ($data['genero'] != Auth::user()->genero) {
          $genero = $data['genero'] == 1 ? 1 : 2;
          $avatar = $genero == 1 ? LMSController::$avatares['hombre'] : LMSController::$avatares['mujer'];
        }

        DB::table('usuario')
                ->where('id', $data['id'])
                ->update(array(
                    'nombres' => $data['nombres'],
                    'apellidos' => $data['apellidos'],
                    'email' => $data['email'],
                    'universidad_id' => $data['universidad'],
                    'genero' => $genero,
                    'avatar' => $avatar,
        ));
        #cambiar la imagen de la persona
        usuario::saveImage($avatar, Auth::user()->id);

        Session::flash("valid", "Información editada correctamente");
      } else {
        Session::flash('email_invalido', true);
        return Redirect::to('usuario/informacion');
      }

      return Redirect::to('usuario/informacion');
    } else {

      return Redirect::to('usuario/informacion')->withInput()->withErrors($validator);
    }
  }

  private function cambio_de_email_valido($id, $email) {
    if (usuario::where('email', $email)->count() == 1) {
      return usuario::where('email', $email)->first()->id == $id;
    }
    return true;
  }

  public function postEditarPassword() {
    // dd(Input::all());
    #la contraseña vieja es la que tengo

    if (!Hash::check(Input::get('old_password'), Auth::user()->password)) {
      Session::flash('invalid_old', true);
    } else if (Input::get('new_password1') != Input::get('new_password2')) { #pass1 and pass2 no coinciden 
      Session::flash('invalid_coincidence', true);
    } else {

      #se cambia la contraseña            
      DB::table('usuario')->where('id', Auth::user()->id)->update(
              array(
                  'password' => Hash::make(Input::get('new_password1')
                  )
              )
      );

      Session::flash('valid', "Contraseña cambiada correctamente");
    }

    Session::flash('pass', true);
    return Redirect::to('usuario/informacion');
  }

  #se va a actualizar el avatar de un usuario

  public function postActualizarAvatar() {

    DB::table('usuario')
            ->where('id', Auth::user()->id)
            ->update(array('foto' => Input::get('avatar')));


    Session::flash("valid", "Avatar cambiado correctamente");
    return Redirect::to('curso/all');
  }

  

  public function postLoguear() {
    // dd(Input::all());
    $usuario = array(
        'email' => Input::get('email'),
        'password' => Input::get('password')
    );

    if (Auth::attempt($usuario)) {
      return Redirect::action('CursoController@getIndex');
    } else {
      Session::flash("invalid", 'Usuario o contraseña incorrectos');
      return Redirect::to("/");
    }
  }

  public function getLogout() {

    # si habia entrado a un curso
    if (Session::has('session_id')) {
      $bitacora = bitacora::find(Session::get('session_id'));
      if ($bitacora)
        $bitacora->update_ultima_vista();
    }
    #Logout del usuario
    Auth::logout();
    #eliminar todas las variables de sesion que se crearon
    Session::flush();
    return Redirect::to('/');
  }

  public function getInformacion() {
    if (Request::ajax()) {
      $usuario = usuario::find(Input::get('usuario_id'));
      //var_dump($usuario);
      $curso = Input::get('curso');
      $info = array(
          'nombres' => $usuario->nombres,
          'apellidos' => $usuario->apellidos,
          'email' => $usuario->email,
          'rol' => $usuario->rol,
          'online' => $usuario->online,
          'foto' => $usuario->foto,
          'puntos' => $usuario->get_puntos_en_curso($curso),
          'posicion' => $usuario->get_posicion_en_ranking($curso),
          'tiempo_logueado' => LMSController::formatear_tiempo($usuario->get_tiempo_logueado($curso), 's'),
          'id' => $usuario->id
      );


      $data['info'] = $info;
      $data['logros'] = usuario::find($usuario->id)->get_logros_obtenidos($curso);



      return Response::json($data);
    } else {
      return View::make('estudiante.perfil')
                      ->with('universidades', universidad::all())
                      ->with('usuario', usuario::find(Auth::user()->id));
    }
  }

  public function postAvatar() {
    if (Request::ajax()) {
      $task = Input::get('task');
      if ($task == "getUserData") {
        $user = Auth::user();
        echo "{\"sex\":{$user->genero},\"current\":" . $user->avatar . ",\"unlocked\":" . $user->avatar_accesorios . "}";
      } else if ($task == "saveUserData") {
        $avatar = Input::get('userdata');

        DB::table('usuario')
                ->where('id', Auth::user()->id)
                ->update(array('avatar' => $avatar));

        usuario::saveImage($avatar, Auth::user()->id);
        Logros::check403(Auth::user()->id); // se le da el logro del avatar
      }
    }
  }

}
