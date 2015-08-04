<?php

class TestController extends LMSController {

  public function getResizeAchievements() {   

    foreach (scandir(public_path() . '/img/logros') as $logro) {
      try {
        Image::make(public_path() . ("/img/logros/{$logro}"))->resize(100, 100)->save(public_path() . ("/img/logros/small/{$logro}"));
      } catch (Exception $e) {
        echo "error en {$logro} <br>";
      }
    }
  }

  public function getCreateImages($from = 1) {    
    $c = 0;
    $max = Usuario::max('id');
    while ($c < 40 && $from <= $max) {
      try {
        Image::make(public_path() . ("/avatares/userimages/{$from}.png"))->resize(20, 20)->save(public_path() . ("/avatares/userimages/thumbnail/{$from}.png"));
        Image::make(public_path() . ("/avatares/userimages/{$from}.png"))->resize(64, 64)->save(public_path() . ("/avatares/userimages/small/{$from}.png"));
      } catch (Exception $e) {
        echo "error en {$from}.png <br>";
      } finally {
        $from++;
        $c++;
      }
    }
    if ($from < $max) {
      return Redirect::to("/create-images/{$from}");
    }
  }

  public function getLoad() {



 //   DB::table('usuario')->where('id', '>=', 380)->delete();
  //  DB::table('curso_x_usuario')->where('curso_id',10)->delete();

    $file = fopen(public_path() . '/students.txt', 'r');

    $cursoId = DB::table('curso')->max('id');
    while ($line = fgets($file)) {

      $user = preg_split('/\s+/', trim($line));

      $c = count($user);

      if ($c <= 7 && $c >= 6) {
        $cedula = $user[0];
        $email = $user[$c - 2];
        $genero = $user[$c - 1];

        $lastName = $user[$c - 4] . " " . $user[$c - 3];

        if ($c - 2 == 4) {
          $firstName = $user[$c - 5];
        } else {
          $firstName = $user[$c - 6] . " " . $user[$c - 5];
        }

        $user = Usuario::where('email', $email)->first();
        $userId = null;
        if ($user) {
          echo "el usuaruio ya está $email<br>";
          $userId = $user->id;
        } else {

          $newUser = [
              'nombres' => $firstName,
              'apellidos' => $lastName,
              'email' => $email,
              'password' => Hash::make($cedula),
              'universidad_id' => 1,
              'genero' => $genero,
              'avatar' => LMSController::$avatares[$genero == 1 ? 'hombre' : 'mujer'],
              'online' => 0,
              'fecha_registro' => date('Y-m-d')
          ];



          $userId = DB::table('usuario')
                  ->insertGetId($newUser);
        }

        //ingresamos al usuario en la tabla de usuario_x_curso

        DB::table('curso_x_usuario')
                ->insert([
                    'usuario_id' => $userId,
                    'curso_id' => $cursoId,
                    'fecha_inscripcion' => date('Y-m-d H:i:s'),
                    'puntos' => 0,
                    'rol' => 0
        ]);
      } else {
        echo "error en $line<br>";
      }
    }

    //others 
    //error en 1152448813	Edward	Calderon	ecalderon@unal.edu.co    
//error en 1040048419	Cristian Daniel De Jesus	Ramirez Higinio	cdramirezh@unal.edu.co 

    $user1 = [

        'nombres' => "Edward",
        'apellidos' => "Calderon",
        'email' => "ecalderon@unal.edu.co",
        'password' => Hash::make(1152448813),
        'universidad_id' => 1,
        'genero' => 1,
        'avatar' => LMSController::$avatares['hombre'],
        'online' => 0,
        'fecha_registro' => date('Y-m-d')
    ];
    $user2 = [
                'nombres' => 'Cristian Daniel De Jesus',
                'apellidos' => 'Ramirez Higinio',
                'email' => 'cdramirezh@unal.edu.co',
                'password' => Hash::make(1040048419),
                'universidad_id' => 1,
                'genero' => 1,
                'avatar' => LMSController::$avatares['hombre'],
                'online' => 0,
                'fecha_registro' => date('Y-m-d')
    ];


    $user1id = DB::table('usuario')->insertGetId($user1);
    $user2id = DB::table('usuario')->insertGetId($user2);



    DB::table('curso_x_usuario')
            ->insert([
                'usuario_id' => $user1id,
                'curso_id' => $cursoId,
                'fecha_inscripcion' => date('Y-m-d H:i:s'),
                'puntos' => 0,
                'rol' => 0
    ]);

    DB::table('curso_x_usuario')
            ->insert([
                'usuario_id' => $user2id,
                'curso_id' => $cursoId,
                'fecha_inscripcion' => date('Y-m-d H:i:s'),
                'puntos' => 0,
                'rol' => 0
    ]);

    echo "registro completo.";
  }

}
