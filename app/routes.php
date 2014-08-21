<?php

//rutas para agregar
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



Route::get('/db-fix', function() {

    $envios = envio::where('curso', 6)->orderBy('id')->get();

    foreach ($envios as $envio) {
        //echo "{$envio->resultado}<br/>";
        if ($envio->resultado == 'accepted') {
            DB::table('envio')
                    ->where('usuario', $envio->usuario)
                    ->where('ejercicio', $envio->ejercicio)
                    ->where('id', '>', $envio->id)
                    ->delete();
        }
    }
    echo "fixed";
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
    Route::controller('envio', 'EnvioController');
    Route::controller('api', 'ApiController');
    Route::controller('poli', 'PoliController');
    Route::controller('lms', 'LMSController');
});


Route::get('/tt', function() {
    session_start();
    $_SESSION['rr'] = 'xx';


    return Redirect::to('/ttt');
});

Route::get('/ttt', function() {

    session_start();
    session_name();


    echo "<pre>";
    var_dump(Request::getSession());
    echo "</pre>";
});


Route::get('/t', function() {

    $usuario = usuario::find(1);

    echo "<pre>";
    var_dump(curso::find(6)->get_programador_de_la_semana());

    echo "</pre>";
});



Route::get('/update-avatars/{from}/{to}', function($from, $to) {

    $usuarios = usuario::where('id', '>=', $from)->where('id', '<=', $to)->get();

    foreach ($usuarios as $usuario) {
        usuario::saveImage($usuario->avatar, $usuario->id);
    }
    echo "finished...";
});







Route::get('/test', function() {


    #usuarios que estaran inscritos en la bd;


    $usuarios = array(
        array(
            'nombres' => 'Andrés Felipe',
            'apellidos' => 'Pineda Corcho',
            'email' => 'pineda',
            'password' => Hash::make('pineda'),
            'foto' => '1.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Julián',
            'apellidos' => 'Moreno Cadavid',
            'email' => 'moreno',
            'password' => Hash::make('moreno'),
            'foto' => '4.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Luis Fernando',
            'apellidos' => 'Montoya Gómez',
            'email' => 'montoya',
            'password' => Hash::make('pineda'),
            'foto' => '8.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Sebastian Fernando',
            'apellidos' => 'Múnera',
            'email' => 'munera',
            'password' => Hash::make('munera'),
            'foto' => '15.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Alejandro',
            'apellidos' => 'Escobar Garcés',
            'email' => 'escobar',
            'password' => Hash::make('munera'),
            'foto' => '4.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Manuel',
            'apellidos' => 'Leudo Asprilla',
            'email' => 'leudo',
            'password' => Hash::make('leudo'),
            'foto' => '7.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Juan Javier',
            'apellidos' => 'Caciedo',
            'email' => 'caciedo',
            'password' => Hash::make('caciedo'),
            'foto' => '20.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Thomas',
            'apellidos' => 'Quiroz Vasquez',
            'email' => 'quiroz',
            'password' => Hash::make('quiroz'),
            'foto' => '18.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Roger Alexander',
            'apellidos' => 'Alvarez',
            'email' => 'alvarez',
            'password' => Hash::make('alvarez'),
            'foto' => '21.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Carlos Alberto',
            'apellidos' => 'Botero',
            'email' => 'botero',
            'password' => Hash::make('botero'),
            'foto' => '16.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Javier Esteban',
            'apellidos' => 'Barraza',
            'email' => 'barraza',
            'password' => Hash::make('barraza'),
            'foto' => '15.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Danny Alexander',
            'apellidos' => 'Alvarez',
            'email' => 'alvarez2',
            'password' => Hash::make('alvarez'),
            'foto' => '17.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Milton Fernando',
            'apellidos' => 'Velilla',
            'email' => 'velilla',
            'password' => Hash::make('velilla'),
            'foto' => '21.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Daniel',
            'apellidos' => 'Rendón Cortes',
            'email' => 'rendon',
            'password' => Hash::make('rendon'),
            'foto' => '22.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Johanna',
            'apellidos' => 'López Pinto',
            'email' => 'lopez',
            'password' => Hash::make('lopez'),
            'foto' => '19.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Natalia',
            'apellidos' => 'Pino Patiño',
            'email' => 'pino',
            'password' => Hash::make('pino'),
            'foto' => '25.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Beatriz',
            'apellidos' => 'Mazo',
            'email' => 'mazo',
            'password' => Hash::make('mazo'),
            'foto' => '27.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Cristian Alberto',
            'apellidos' => 'Rico',
            'email' => 'rico',
            'password' => Hash::make('rico'),
            'foto' => '2.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Sergio Nicolas',
            'apellidos' => 'Salamanca Cabezas',
            'email' => 'salamanca',
            'password' => Hash::make('salamanca'),
            'foto' => '11.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Cristian',
            'apellidos' => 'Salamanca Cabezas',
            'email' => 'salamanca2',
            'password' => Hash::make('salamanca'),
            'foto' => '11.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Juan Camilo',
            'apellidos' => 'Pineda',
            'email' => 'pineda2',
            'password' => Hash::make('pineda2'),
            'foto' => '11.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Mariana ',
            'apellidos' => 'Rosas Pino',
            'email' => 'rosas',
            'password' => Hash::make('rosas'),
            'foto' => '11.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Daniel Fernando',
            'apellidos' => 'Jaramillo',
            'email' => 'jaramillo',
            'password' => Hash::make('jaramillo'),
            'foto' => '13.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Fabian Arturo',
            'apellidos' => 'Zapata',
            'email' => 'zapata',
            'password' => Hash::make('zapata'),
            'foto' => '13.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
        array(
            'nombres' => 'Daniel Armando',
            'apellidos' => 'Aterhotua',
            'email' => 'aterhotua',
            'password' => Hash::make('aterhotua'),
            'foto' => '12.jpg',
            'rol' => 0,
            'fecha_registro' => date('Y-m-d'),
            'online' => 0,
            'universidad_id' => 1
        ),
    );

    $curso = 7;
    foreach ($usuarios as $usuario) {
        $user = DB::table('usuario')
                ->insertGetId($usuario);


        DB::table('curso_x_usuario')
                ->insert(array(
                    'curso_id' => $curso,
                    'usuario_id' => $user,
                    'fecha_inscripcion' => date('Y-m-d'),
                    'puntos' => 0,
                    'ultima_interaccion' => 1391557878
        ));
    }
}
);


