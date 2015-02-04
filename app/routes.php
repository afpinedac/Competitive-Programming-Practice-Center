<?php

Route::get('/load', function() {

  $file = fopen('estudiantes_analisis.txt', 'r') or die('unable to open file');


  $curso = 7;

  while (!feof($file)) {
    $s = trim(fgets($file));
    $s = preg_split("/[\s]+/", $s);
    $n = count($s);

    //echo implode(',', $s) .">>>";

    $id = trim($s[0]);
    $name = array_slice($s, 1, $n - 2);

    if (count($name) == 3) { // si tiene nombre de 4 letras
      $firstName = implode(" ", array_slice($name, 0, 1));
      $lastName = implode(" ", array_slice($name, 1, 3));
    } else {
      $firstName = implode(" ", array_slice($name, 0, 2));
      $lastName = implode(" ", array_slice($name, 2, 4));
    }


    $email = trim($s[$n - 1]);

    $mujeres = [ 1035916193
        , 1152192398
        , 97082219793
        , 1013623069
    ];

    if (!Usuario::where('email', $email)->count() == 1) {

      $genero = in_array($id, $mujeres) ? 0 : 1;

      echo "$id - [$firstName][$lastName]  - $email - $genero<br>";


      $user = [
          'nombres' => $firstName,
          'apellidos' => $lastName,
          'email' => $email,
          'password' => Hash::make($id),
          'foto' => '1.png',
          'rol' => 0,
          'fecha_registro' => date("Y-m-d"),
          'online' => 1,
          'universidad_id' => 1,
          'plata' => 0,
          'avatar_accesorios' => '[]',
          'genero' => $genero
      ];
      $user['avatar'] = $genero == 1 ? LMSController::$avatares['hombre'] : LMSController::$avatares['mujer'];


      //registramos el usuario
      $idUsuario = DB::table('usuario')->insertGetId($user);


      $register = [
          'usuario_id' => $idUsuario,
          'curso_id' => $curso,
          'fecha_inscripcion' => date("Y-m-d"),
          'puntos' => 0,
          'ultima_interaccion' => 0,
          'rol' => 0
      ];

      //lo registramos en el curso
      DB::table('curso_x_usuario')->insert($register);
    }
  }

  fclose($file);
});

Route::get('/create-avatar/{min}/{max}', function($min, $max) {
  $users = Usuario::where('id', '>=', $min)->where('id', '<=', $max)->get();

  foreach ($users as $usuario) {
    echo ($usuario->id) . "<br>";
    Usuario::saveImage($usuario->avatar, $usuario->id);
  }
});

Route::get('/au/{id}', function($id) {

  Auth::loginUsingId($id);
  return Redirect::to("/curso");
});

Route::get('/', function() {

  if (Auth::check()) {
    return Redirect::to("curso/all");
  } else {

    $videos = array(
        1 => 'nKIu9yen5nc',
        2 => 'Y1HHBXDL9bg',
        3 => 'a1OhqQVZ-54',
        4 => 'DHV8_vM-Juk',
        5 => '1OJf3OV-3BQ',
        6 => 'FNZjWlVQGS8',
        7 => 'BxBwqqZBfCc'
    );

    return View::make('inicio.login')
                    ->with('background', mt_rand(1, 1))
                    ->with('videorand', $videos[mt_rand(1, count($videos))])
                    ->with('back', mt_rand(1, 2));
  }
});




Route::get('/about', function() {
  return View::make('inicio.about');
});

Route::get('/lang/{lang}', array('as' => 'change_language', function($lang) {
Session::put('my.locale', $lang);
return Redirect::to('/');
})
);

Route::get('/registrar', array('as' => 'registrarse', function() {
return View::make('inicio.registrarse')
              ->with('universidades', universidad::all());
}));


Route::post('/registrar', function() {
  $rules = array(
      'usuario' => array(
          'nombres' => 'required|min:3|max:50',
          'apellidos' => 'required|min:3|max:50',
          'email' => 'email|unique:usuario|required',
          'password' => 'confirmed|min:3|required',
          'universidad' => 'required'
      ),
      'usuario_registrado' => array(
          'nombres' => 'required|min:3|max:50',
          'apellidos' => 'required|min:3|max:50',
          'email' => 'email|required',
          'universidad' => 'required'
      ),
      'mensajes' => array(
          'required' => 'El :attribute es obligatorio',
          'min' => 'La longitud del :attribute es mínimo de :min caracteres',
          'max' => 'La longitud del :attribute es máximo de :max caracteres',
          'alpha' => 'El :attribute solo puede contener caracteres',
          'unique' => 'El :attribute ya se encuentra registrado',
          'email' => 'El :attribute debe ser una dirección válida',
          'confirmed' => 'Las contraseñas no concuerdan',
      ),
  );

  // var_dump(Input::all());
  // exit;
  $fields = Input::except(array('_token'));
  $validator = Validator::make($fields, $rules['usuario'], $rules['mensajes']);

  if ($validator->passes()) {
    $usuario = $fields;
    $usuario['rol'] = Input::get('profesor', 0);
    $usuario['fecha_registro'] = date('Y-m-d');
    $usuario['password'] = Hash::make($usuario['password']);
    $usuario['universidad_id'] = Input::get('universidad');
    $usuario['genero'] = Input::get('genero');
    $usuario['avatar_accesorios'] = "[]";
    $usuario['avatar'] = $usuario['genero'] == 1 ? LMSController::$avatares['hombre'] : LMSController::$avatares['mujer'];

    unset($usuario['password_confirmation']);
    unset($usuario['profesor']);
    unset($usuario['universidad']);



    $new_user = DB::table('usuario')->insertGetId($usuario);

    #creamos la imagen para el usuario
    usuario::saveImage($usuario['avatar'], $new_user);


    if ($usuario['rol'] == 1) {
      DB::table('profesor')->insert(array('id' => $new_user));
    }
    Session::flash("valid", "Registro realizado correctamente");
    return Redirect::to('/');
  } else {

    return Redirect::route('registrarse')->withInput()->withErrors($validator);
    Session::flash("invalid", "Ha ocurrido algún problema");
  }
}
);





Route::get('/ver-logro/{logro}', function($logro) {

  $logro = LMSController::decoder($logro);

  $notificacion = notificacion::find($logro);
  if ($notificacion) {

    return View::make('curso.logro.ver_logro')
                    ->with('usuario', usuario::find($notificacion->usuario))
                    ->with('logro', logro::find(DB::table('curso_x_logro_x_usuario')->where('id', $notificacion->codigo)->first()->logro))
                    ->with('curso', curso::find($notificacion->curso));
  } else {
    return Redirect::to("/curso");
  }
});


Route::post('/loguear', function() {
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
});



Route::group(array('before' => 'auth'), function() {

  Route::controller('usuario', 'UsuarioController');
  Route::controller('curso', 'CursoController');
  Route::controller('taller', 'TallerController');
  Route::controller('evaluacion', 'EvaluacionController');
  Route::controller('recurso', 'RecursoController');
  Route::controller('modulo', 'ModuloController');
  Route::controller('contenido', 'ContenidoController');
  Route::controller('ejercicio', 'EjercicioController');
  Route::controller('notificacion', 'NotificacionController');
  Route::controller('mensaje', 'MensajeController');
  Route::controller('chat', 'ChatController');
  Route::controller('foro', 'ForoController');
  Route::controller('item', 'ItemController');
  Route::controller('api', 'ApiController');
  Route::controller('poli', 'PoliController');
  Route::controller('lms', 'LMSController');
});

#ruta para los cron jobs
//Route::controller('cronjob', 'CronjobController');






Route::get('/update-avatars/{from}/{to}', function($from, $to) {

  $usuarios = usuario::where('id', '>=', $from)->where('id', '<=', $to)->get();

  foreach ($usuarios as $usuario) {
    usuario::saveImage($usuario->avatar, $usuario->id);
  }
  echo "finished...";
});















