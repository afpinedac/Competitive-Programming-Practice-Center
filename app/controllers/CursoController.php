<?php

class CursoController extends LMSController {

  public function getInscribir($curso) {

    $curso = curso::find($curso);

    #el curso es publico
    if ($curso->publico == 1) {
      $registro = array(
          'fecha_inscripcion' => date('Y-m-d'),
          'puntos' => 0,
          'usuario_id' => Auth::user()->id,
          'curso_id' => $curso->id,
      );
      Session::flash('valid', "El curso '{$curso->nombre}' se inscribió correctamente");
      cursoxusuario::create($registro);
    }
    return Redirect::to('curso/all');
  }

  #se inscribe cuando el curso es privado

  public function postInscribir() {
    $curso = curso::find(Input::get('curso'));
    $pass = Input::get('password');


    if ($curso && $curso->password == $pass) {
      $registro = array(
          'fecha_inscripcion' => date('Y-m-d'),
          'puntos' => 0,
          'usuario_id' => Auth::user()->id,
          'curso_id' => $curso->id,
      );
      cursoxusuario::create($registro);
      Session::flash('valid', "El curso '{$curso->nombre}' se inscribió correctamente");
    } else {
      Session::flash('invalid', "La contraseña del curso es inválida");
    }

    return Redirect::to("curso/all");
  }

  public function getDesmatricular($curso) {
    $curso = curso::find($curso);
    if ($curso) {
      $curso->desmatricular(Auth::user()->id);
      Session::flash('valid', "El curso {$curso->nombre} se ha desmatriculado");
    }

    return Redirect::to('curso/all');
  }

  public function getIndex() {

    return Redirect::action('CursoController@getAll');
  }

  # Funcion que hace entrar a un curso y guarda la bitacora del curso 

  public function getEntrar($curso, $tab = 'inicio') {
    if (($this->tiene_curso_inscrito($curso) && !curso::find($curso)->terminado()) || (Auth::user()->id == curso::find($curso)->profesor_id)) {
      $cursos = curso::find($curso);
      #guardar en la tabla de logueo del curso, cuadrar cuando sale...            
      $session_id = DB::table('bitacora')->insertGetId(array(
          'usuario' => Auth::user()->id,
          'curso' => $curso,
          'fecha_ingreso' => date('Y-m-d H:i:s')
      ));

      #mirar si se ha estado X tiempo en la plataforma
      Logros::check25234(Auth::user()->id, $curso);

      Session::put('modulo.estudiante', $cursos->get_primer_modulo());
      Session::put('session_id', $session_id);
      Session::flash("valid", "BIENVENIDO AL CURSO  '{$cursos->nombre}'");

      return Redirect::to("curso/ver/" . Str::slug($curso . "-" . $cursos->nombre) . "/" . $tab);
    } else {
      return Redirect::to('curso');
    }
  }

  public function getEditar($curso, $option = null, $value = null) {

    $curso = curso::find($curso);
    $usuario = usuario::find(Auth::user()->id);

    if ($curso && $usuario && $usuario->es_propietario($curso->id)) {

      if ($curso->id != Session::get('curso')) { # si hay un cambio de curso
        Session::put('modulo_profesor', $curso->get_primer_modulo());
      }



      Session::put('modulo_profesor', Session::get('modulo_profesor', $curso->get_primer_modulo()));
      Session::put('curso', $curso->id);



      $modulo = modulo::find(Session::get('modulo_profesor'));



      if ($option == null) { #se va a editar un modulo del curso
        return View::make('profesor.editar_curso.editar_modulo')
                        ->with('curso', $curso)
                        ->with('modulos', $curso->get_modulos())
                        ->with('modulo', $modulo)
                        ->with('materiales', $modulo ? $modulo->get_materiales() : null)
                        ->with('ejercicios', $modulo ? $modulo->get_ejercicios() : null)
                        ->with('evaluaciones', $modulo ? $modulo->get_evaluaciones() : null)
                        ->with('taller', taller::find(Session::get('modulo_profesor')));
      } else if ($option == "lista-ejercicios") { #el profesor va a ver su lista de ejercicios
        Session::forget("modulo_profesor");

        if ($value == null) { #se va a ver toda la lista de ejercicios
          return View::make('profesor.editar_curso.lista_ejercicios')
                          ->with('curso', $curso)
                          ->with('modulos', $curso->get_modulos())
                          ->with('ejercicios', $usuario->ejercicios_creados());
        } else { #se va a ver un ejercicio en particular
          $ejercicio = ejercicio::find($value);
          if ($ejercicio && $ejercicio->profesor == Auth::user()->id) { #si el ejercicio existe y ademas el profesor es el dueño
            return View::make('profesor.editar_curso.editar_ejercicio')
                            ->with('curso', $curso)
                            ->with('modulos', $curso->get_modulos())
                            ->with('ejercicio', $ejercicio);
          } else {
            Session::flash("invalid", "No tienes permiso sobre este objeto");
          }
        }
      } else if ($option == "editar-informacion") {
        Session::forget("modulo_profesor");
        return View::make('profesor.editar_curso.editar_informacion')
                        ->with('curso', $curso)
                        ->with('modulos', $curso->get_modulos());
      } else {
        Session::flash("invalid", "Usted no tiene permisos sobre este curso");
        return Redirect::action("curso/ver/{$curso->id}/editar");
      }
    } else {
      Session::flash("invalid", "Usted no tiene permisos sobre este curso");
      return Redirect::action('CursoController@getAll');
    }
  }

  public function getEliminar($curso) {
    $curso = curso::find($curso);
    $usuario = usuario::find(Auth::user()->id);
    if ($curso && $usuario->es_propietario($curso->id)) {
      $curso->eliminado = 1;
      $curso->save();
      Session::flash("valid", "El curso {$curso->nombre} fue eliminado correctamente");
    } else {
      Session::flash("invalid", "No tiene permisos para esta acción");
    }

    return Redirect::to('curso/all');
  }

  /*
   * Muestra el curso al que estudiante acabo de entrar 
   */
#siempre el estudiante entra por esta URL

  public function getVer($curso = null, $tab = null, $param1 = -1, $param2 = -1) {

    if (!$curso) {
      return Redirect::to('curso/all');
    }

    if (!$tab) {
      return Redirect::to("curso/ver/{$curso}/inicio");
    }

    //dd($this->modulo);
    $values = explode("-", $curso);

    $curso = $values[0];



    if ($this->tiene_curso_inscrito($curso)) {

      Session::put('curso.estudiante', $curso); #curso actual
      #actualizamos la ultima accion 
      if (Session::has('session_id')) {
        $session_id = Session::get('session_id', 0);
        bitacora::find($session_id)->update_ultima_vista();
      }

      $available_tabs = array('inicio', 'contenido', 'envios', 'perfil', 'ejercicio', 'mensajes', 'evaluacion', 'foro', 'personalizar-avatar', 'mis-envios', 'editar');

      if (in_array($tab, $available_tabs)) {



        $curso = curso::find($curso);
        $usuario = usuario::find(Auth::user()->id);

        #se pone el id del primer modulo si no se encuentra setiado
        if (!Session::has('modulo.estudiante')) {
          Session::put('modulo.estudiante', $curso->get_primer_modulo());
        }

        $modulo = modulo::find(Session::get('modulo.estudiante', $curso->get_primer_modulo())); //se tiene el valor de la sesion, si este no existe se selecciona el primer modulo del curso



        if ($tab == 'evaluacion') {
          #param1 = el id de la evaluacion
          #param2 = el id del ejercicio

          $evaluacion = modulo::find(Session::get('modulo.estudiante', $curso->get_primer_modulo()))->get_evaluacion($param1);

          if ($evaluacion) { # si existe la evaluacion y es del modulo
            if ($param2 == 'resultado') { // si va a ver los resultados de su evaluacion
              $evaluacion = evaluacion::find($param1);
              if ($evaluacion->termino()) {

                #verificar si ya se calcularon los resultados para este usuario
                if (!$evaluacion->ha_sido_calculado_para_usuario(Auth::user()->id)) {

                  #se calculan los logros de un usuario en evaluacion
                  Logros::evaluacion(Auth::user()->id, $param1);

                  #se inserta el registro de calculo
                  DB::table('calculo_usuario_evaluacion')
                          ->insert(array(
                              'evaluacion' => $param1,
                              'usuario' => Auth::user()->id
                  ));
                }




                return View::make('curso.evaluacion.resultados2')
                                ->with('evaluacion', $evaluacion)
                                ->with('curso', $curso)
                                ->with('logro', $usuario->get_logro_evaluacion($curso->id))
                                ->with('logros', $usuario->get_logros_en_evaluacion($param1))
                                ->with('amigos', $usuario->get_amigos($curso->id));
                //->with('logros', $usuario->get_amigos($curso->id));
              } else {
                return Redirect::to("curso/ver/{$curso->id}/evaluacion/{$param1}");
              }
            } else if (evaluacion::find($param1)->get_time_fin() <= time() || evaluacion::find($param1)->get_time_ini() >= time()) { #la evaluacion ya paso o no ha comenzado
              return Redirect::to("curso/ver/{$curso->id}/contenido");
            } else if ($param2 == -1) { # si es la evaluacion
              
              
              return View::make("curso.$tab.$tab")
                              ->with('curso', $curso)
                              ->with('modulo', $modulo)
                              ->with('modulos', $curso->get_modulos())
                              ->with('amigos', $usuario->get_amigos($curso->id))
                              ->with('mensajes', $usuario->get_mis_mensajes($curso->id))
                              ->with('ejercicios', evaluacion::find($param1)->get_ejercicios($curso->id))
                              ->with('evaluacion', $param1); //pasamos como parametro la evaluacion
            } else { # si elige un ejercicio de la evaluacion
              if (evaluacion::find($param1)->tiene_ejercicio($param2)) {
                
                
                return View::make('curso.ejercicio.ejercicio2')
                                ->with('curso', $curso)
                                ->with('ejercicio', evaluacion::find($param1)->get_ejercicio($param2))
                                ->with('amigos', $usuario->get_amigos($curso->id))
                                ->with('logro', $usuario->get_logro_ejercicio($curso->id))
                                ->with('evaluacion', $param1);
              } else {
                return Redirect::to("curso/ver/{$curso->id}/contenido");
              }
            }
          } else {
            return Redirect::to("curso/ver/{$curso->id}/contenido");
          }
        } else if ($tab == 'mensajes') {



          if ($param1 == 'leer' || ($param1 == 'nuevo' && $param2 != -1)) {

            #ponemos como leido el mensaje
            $error = false;
            $mensaje = mensaje::find($param2);
            #si el mensaje no es para el redireccione
            if ($mensaje) {
              if ($mensaje->destinatario != Auth::user()->id) {
                $error = true;
              }
            } else {
              $error = true;
            }
            if ($error) {
              return Redirect::to("curso/ver/{$curso->id}/mensajes");
            } else {
              $mensaje->update(array('leido' => true));
            }
          }

          return View::make('curso.mensajes.mensajes')
                          ->with('curso', $curso)
                          ->with('amigos', $usuario->get_amigos($curso->id))
                          ->with('mensajes', $usuario->get_mis_mensajes($curso->id))
                          ->with('accion', $param1)//acccion que se va realizar con el mensaje
                          ->with('mensaje', mensaje::find($param2)); // se carga el mensaje que se va a abrir
        } else if ($tab == "mis-envios") {
          if ($param1 == -1) { #todos los envios
            $envios = $usuario::find(Auth::user()->id)->get_envios_en_curso($curso->id);

            return View::make('curso.envio.todos')
                            ->with('curso', $curso)
                            ->with('envios', $envios);
          } else { #un envio en particular
            $envio = envio::find($param1);
            $usuario = $envio ? usuario::find($envio->usuario) : null;

            if (!$usuario || ($usuario && !$usuario->tiene_inscrito($curso->id))) {
              return Redirect::to("curso/");
            }


            return View::make('curso.envio.uno')
                            ->with('curso', $curso)
                            ->with('usuario', $usuario)
                            ->with('envio', $envio);
          }
        } else if ($tab == "foro") {

          if ($param1 != -1) { #se selecciono un tema en especial
            if (!$curso->tiene_tema_foro($param1)) {
              return Redirect::to("curso/ver/{$curso->id}/foro");
            }

            return View::make('curso.foro.tema')
                            ->with('curso', $curso)
                            ->with('amigos', $usuario->get_amigos($curso->id))
                            ->with('tema', temaforo::get_tema($param1))
                            ->with('respuestas', temaforo::find($param1)->get_respuestas());
          } else { #se va a listar todos los temas del foro
            return View::make('curso.foro.foro')
                            ->with('curso', $curso)
                            ->with('amigos', $usuario->get_amigos($curso->id))
                            ->with('temas', temaforo::get_temas($curso->id));
          }
        } else if ($tab == 'perfil') {
          $usuario = usuario::find(Auth::user()->id);
          return View::make("curso.$tab.$tab")
                          ->with('curso', $curso)
                          ->with('amigos', $usuario->get_amigos($curso->id))
                          ->with('mensajes', $usuario->get_mis_mensajes($curso->id))
                          ->with('logros', $usuario->get_logros_obtenidos($curso->id))
                          ->with('logro', $usuario->get_logro_redes_sociales($curso->id))
                          ->with('ranking', $curso->get_ranking($this->top_ranking))
                          ->with('top', $this->top_ranking);
        } else if ($tab == 'ejercicio') {  // si es un ejercicio  
          $ejercicio = modulo::find(Session::get('modulo.estudiante', $curso->get_primer_modulo()))->get_ejercicio($param1);
          if ($ejercicio) { #si existe el ejercicio al que quiero acceder   
#se mira si un envio a sido evaluado y se dan los respectivos logros de un taller y puntos
         

            return View::make('curso.ejercicio.ejercicio2')
                            ->with('curso', $curso)
                            ->with('ejercicio', $ejercicio)
                            ->with('amigos', $usuario->get_amigos($curso->id))
                            ->with('test', $usuario->get_resultado_test($curso->id, $ejercicio->id))
                            ->with('logro', $usuario->get_logro_ejercicio($curso->id))
                            ->with('evaluacion', $param2);
          } else {
            return Redirect::to("curso/ver/{$curso->id}/contenido");
          }
        } else if ($tab == "inicio") {  //    si no es ejercicio  , . inicio
          return View::make("curso.$tab.$tab")
                          ->with('curso', $curso)
                          ->with('amigos', $usuario->get_amigos($curso->id))
                          //  ->with('logros', $usuario->get_logros_obtenidos(Auth::user()->id, $curso->id))
                          ->with('logro', $usuario->get_logro_redes_sociales($curso->id))
                          ->with('notificaciones', $curso->get_notificaciones());
        } else if ($tab == "contenido") {


          #se mira si un envio a sido evaluado y se dan los respectivos logros de un taller y puntos
          #mira si hay respuesta a algun ejercicio
        //  $this->has_some_veredict($curso->id);


          return View::make("curso.$tab.$tab")
                          ->with('curso', $curso)
                          ->with('modulo', $modulo)
                          ->with('modulos', $curso->get_modulos())
                          ->with('amigos', $usuario->get_amigos($curso->id))
                          //    ->with('logros', $usuario->get_logros_obtenidos($curso->id))
                          ->with('logro', $usuario->get_logro_ejercicio($curso->id));
          // ->with('notificaciones', $curso->get_notificaciones())
          // ->with('ranking', $curso->get_ranking());
        } else if ($tab == "envios") {


          return View::make('curso.envio.envio')
                          ->with('curso', $curso);
        } else if ($tab == "editar") { ## super funcion de edición-------------------------------------------------------------------------------------------------------->>>>>EDICIÓN
          if ($curso->profesor_id == Auth::user()->id) {



            if ($curso->id != Session::get('curso.estudiante')) { # si hay un cambio de curso
              Session::put('modulo_profesor', $curso->get_primer_modulo());
            }

            Session::put('modulo_profesor', Session::get('modulo_profesor', $curso->get_primer_modulo()));
            Session::put('curso', $curso->id);


            $modulo = modulo::find(Session::get('modulo_profesor'));



            if ($param1 == -1) {

              return View::make('profesor.editar_curso.editar_modulo')
                              ->with('curso', $curso)
                              ->with('modulos', $curso->get_modulos())
                              ->with('modulo', $modulo)
                              ->with('materiales', $modulo ? $modulo->get_materiales() : null)
                              ->with('ejercicios', $modulo ? $modulo->get_ejercicios() : null)
                              ->with('evaluaciones', $modulo ? $modulo->get_evaluaciones() : null)
                              ->with('taller', taller::find(Session::get('modulo_profesor')))
                              ->with('amigos', $usuario->get_amigos($curso->id));
            } else if ($param1 == "lista-ejercicios") { #el profesor va a ver su lista de ejercicios
              Session::forget("modulo_profesor");
              if ($param2 == -1) { #se va a ver toda la lista de ejercicios
                return View::make('profesor.editar_curso.lista_ejercicios')
                                ->with('curso', $curso)
                                ->with('modulos', $curso->get_modulos())
                                ->with('ejercicios', $usuario->ejercicios_creados());
              } else { #se va a ver un ejercicio en particular
                $ejercicio = ejercicio::find($param2);
                if ($ejercicio && $ejercicio->profesor == Auth::user()->id) { #si el ejercicio existe y ademas el profesor es el dueño
                  return View::make('profesor.editar_curso.editar_ejercicio')
                                  ->with('curso', $curso)
                                  ->with('modulos', $curso->get_modulos())
                                  ->with('ejercicio', $ejercicio);
                } else {
                  Session::flash("invalid", "No tienes permiso sobre este objeto");
                }
              }
            } else if ($param1 == "informacion") {
              Session::forget("modulo_profesor");
              return View::make('profesor.editar_curso.editar_informacion')
                              ->with('curso', $curso)
                              ->with('modulos', $curso->get_modulos());
            } else {
              Session::flash("invalid", "Usted no tiene permisos sobre este objeto");
              return Redirect::to("curso/ver/{$curso->id}/editar");
            }
          } else {
            return Redirect::to("curso/all");
          }
        } else {
          Session::flash("invalid", "No tiene permisos sobre este objeto");
        }
      } else {
        return Redirect::to('curso/entrar/' . $curso);
      }
    } else {
      Session::flash("valid", "Uste no tiene este curso matriculado");
      return Redirect::action('CursoController@getAll');
    }
  }

  #funcion que mira si se tiene respuesta de algun ejercicio enviado anteriormente
  #en caso que si lo trae, y lo asigna a una variable de sesion para ser 
  #mostrada en el siguiente request.

  private function has_some_veredict($curso) {

    $veredict = envio::get_veredicto_no_visto($curso, Auth::user()->id);

    if ($veredict) { #si hay un veredicto
      if ($veredict->resultado == 'accepted') {
        $response = "El envío {$veredict->id} ha sido ACEPTADO!";
        Session::flash("valid", $response);
        Session::flash("valid2", "has obtenido {$veredict->puntos_obtenidos} puntos");
      } else {

        if (in_array($veredict->resultado, array_keys($this->veredicto))) {
          $response = "El envío {$veredict->id} ha sido calificado como '{$this->veredicto[$veredict->resultado]}'";

          Session::flash("invalid", $response);
        }
      }

      $veredict->visto = 1;
      $veredict->save();
    }
  }

  /*
   * Funcion para crear un nuevo curso en la base de datos
   */

  public function postCrear() {
    //var_dump(Auth::user());
    //  dd(Input::all());
    $curso = array(
        'nombre' => Input::get('nombre'),
        'descripcion' => Input::get('descripcion'),
        'publico' => Input::has('publico') ? 0 : 1,
        'password' => Input::get('password', ''),
        'profesor_id' => Auth::user()->id,
        'created_at' => date('Y-m-d H:i:s')
    );



    $curso = DB::table('curso')->insertGetId($curso);

    $info = array(
        'usuario_id' => Auth::user()->id,
        'curso_id' => $curso,
        'fecha_inscripcion' => date('Y-m-d H:i:s'),
        'ultima_interaccion' => time()
    );

    DB::table('curso_x_usuario')->insert($info);
    Session::flash("valid", "Curso creado correctamente");

    return Redirect::to("curso/ver/$curso/editar");
  }

  #Verifica si el usuario logueado tiene el curso actual inscrito

  private function tiene_curso_inscrito($curso) {
    return usuario::find(Auth::user()->id)->tiene_inscrito($curso);
  }

  # Funcion que establece en una variable de sesion el modulo que se va a mostrar  al usuario

  public function getModulo($modulo) {
    $modulo = modulo::find($modulo);
    if ($this->tiene_curso_inscrito($modulo->curso)) {
      Session::put('modulo.estudiante', $modulo->id);
      return Redirect::to("curso/ver/{$modulo->curso}/contenido");
    } else {
      //echo "no lo tiene";
      return Redirect::to('curso');
    }
  }

  #funcion para hacer pruebas

  public function getTest() {
    //  session_start();
    //session_destroy();
    //return View::make('curso.contenido.components.envios');

    dd(curso::find(1)->get_modulos());


    return URL::to('/curso/inicio/');
  }

  public function postEditar() {


    $curso = Crypt::decrypt(Input::get('curso'));

    $curso = curso::find($curso);

    if ($curso) { # si existe el curso;
      $publico = Input::has('privado') ? 0 : 1;

      $curso->update(array(
          'nombre' => Input::get('nombre', '-'),
          'descripcion' => Input::get('descripcion'),
          'publico' => $publico,
          'password' => $publico == 1 ? "" : Input::get('password'),
          'chat' => Input::has('chat') ? 1 : 0,
          'soluciones_visibles' => Input::has('soluciones_visibles') ? 1 : 0,
          'terminado' => Input::has('terminado') ? 1 : 0
      ));


      #miramos si quizo cambiar la foto del curso
      if (Input::hasFile('imagen')) {
        $file = Input::file('imagen')->getClientOriginalName();
        $idx = strpos($file, '.');
        $name = $curso->id . '.' . (substr($file, $idx + 1));
        //renombramos el archivo a id_nombre.extension
        $curso->imagen = $name;
        $curso->save();

        //movemos el archivo
        Input::file('imagen')->move($this->ruta['img_cursos'], $name);
      }

      Session::flash("valid", "La información del curso fue actualizada correctamente");
    } else {
      return Redirect::to("curso/all");
    }

    return Redirect::to("curso/ver/{$curso->id}/editar/informacion");
  }

  #funcion que muestra todos los cursos

  public function getAll() {
    return View::make('curso.all.all')
                    ->with('cursos_inscritos', curso::get_inscritos(Auth::user()->id))
                    ->with('cursos_disponibles', curso::get_disponibles(Auth::user()->id))
                    ->with('mis_cursos', curso::get_creados(Auth::user()->id));
  }

  #--------------------------------  MONITOREO -------------------------------------

  public function getMonitorear($curso, $opcion = 'talleres', $value1 = null, $method = null, $value2 = null) {

    $curso = curso::find($curso);
    if (!$curso) {
      return Redirect::to("curso/all");
    }

    if ($curso->profesor_id == Auth::user()->id) {     #si el logueado es el profesor que creo el curso
      $path[1] = array('nombre' => strtoupper($curso->nombre), 'enlace' => '#');


      #-----------------TALLERES------------------------------
      if ($opcion == "talleres") {
      
        $path[2] = array('nombre' => 'Talleres', 'enlace' => url("curso/monitorear/{$curso->id}/talleres"));
        if ($value1 == null) { #mostrar todos los talleres      
          
          return View::make('profesor.monitorear.talleres.talleres')
                          ->with('curso', $curso)
                          ->with('modulos', $curso->get_modulos())
                          ->with('path', $path);
        } else { #se va a mostrar informacion de un taller en particular   
          #validamos que un curso si tenga un taller
          if (!$curso->tiene_taller($value1)) {
            return Redirect::to("curso/monitorear/{$curso->id}");
          }

          $path[3] = array('nombre' => modulo::find($value1)->nombre, 'enlace' => url("curso/monitorear/{$curso->id}/talleres/{$value1}"));
          if ($method != null) { #se va a mostrar algo del taller seleccionado
            if ($method == "ejercicios") {
              #validacion


              $path[4] = array('nombre' => 'Ejercicios', "#");
              if ($value2 == null) {
                
                return View::make('profesor.monitorear.talleres.ejercicios')
                                ->with('curso', $curso)
                                ->with('modulos', $curso->get_modulos())
                                ->with('path', $path)
                                ->with('taller', taller::find($value1))
                                ->with('ejercicios', modulo::find($value1)->get_ejercicios());
              } else { #un ejercicio en particular (se mostraran estadisticas)
                #validacion si un taller tiene un ejercicio
                if (!taller::find($value1)->tiene_ejercicio($value2)) {
                  return Redirect::to("curso/monitorear/{$curso->id}/talleres/{$value1}/ejercicios");
                }
                $ejercicio = ejercicio::find($value2);
                $path[4] = array('nombre' => 'Ejercicios', 'enlace' => url("curso/monitorear/{$curso->id}/talleres/{$value1}/ejercicios"));
                $path[5] = array('nombre' => $ejercicio->nombre, 'enlace' => "#");

                return View::make('profesor.monitorear.talleres.ejercicio')
                                ->with('curso', $curso)
                                ->with('path', $path)
                                ->with('taller', taller::find($value1))
                                ->with('ejercicio', $ejercicio);
              }
            } else if ($method == 'estudiantes') {
                
              if ($value2 == null) { #todos los estudiantes
                
                return View::make('profesor.monitorear.talleres.estudiantes')
                                ->with('curso', $curso)
                                ->with('modulos', $curso->get_modulos())
                                ->with('path', $path)
                                ->with('taller', taller::find($value1))
                                ->with('estudiantes_inscritos', $curso->get_estudiantes()); //aqui va el sort
              } else { #un estudiante en particular
                #validacion si un estudiante pertenece a un curso
                $usuario = usuario::find($value2);
                if (!$usuario || ($usuario && !usuario::find($value2)->tiene_inscrito($curso->id))) {
                  return Redirect::to("curso/monitorear/{$curso->id}/talleres/{$value1}");
                }

                $usuario = usuario::find($value2);
                $path[4] = array('nombre' => $usuario->nombres . " " . $usuario->apellidos, 'enlace' => '#');
                return View::make('profesor.monitorear.talleres.estudiante')
                                ->with('curso', $curso)
                                ->with('modulos', $curso->get_modulos())
                                ->with('taller', taller::find($value1))
                                ->with('path', $path)
                                ->with('usuario', $usuario);
              }
            } else if ($method == "envios") {

              $envio = envio::find($value2);
              $usuario = $envio ? usuario::find($envio->usuario) : null;

              if (!$usuario || ($usuario && !$usuario->tiene_inscrito($curso->id))) {
                return Redirect::to("curso/monitorear/{$curso->id}/talleres/{$value1}");
              }



              $path[4] = array('nombre' => $usuario->nombres . " " . $usuario->apellidos, 'enlace' => url("curso/monitorear/{$curso->id}/talleres/{$value1}/estudiantes/{$usuario->id}"));
              $path[5] = array('nombre' => 'Envio ' . $envio->id, 'enlace' => '#');
              return View::make('profesor.monitorear.envio.envio')
                              ->with('curso', $curso)
                              ->with('modulos', $curso->get_modulos())
                              ->with('taller', taller::find($value1))
                              ->with('path', $path)
                              ->with('usuario', $usuario)
                              ->with('envio', $envio);
            }
          } else { # es la informacion del taller en general   
            return View::make('profesor.monitorear.talleres.estudiantes')
                            ->with('curso', $curso)
                            ->with('modulos', $curso->get_modulos())
                            ->with('path', $path)
                            ->with('taller', taller::find($value1));
                            //->with('estudiantes_inscritos', $estudiantes);
          }
        }
        #----------------------EVALUACIONES-------------------------------------------------------------
      } else if ($opcion == "evaluaciones")  {   

        if ($value1 == null) { #todas las evaluaciones
          $path[2] = array('nombre' => 'Evaluaciones', 'enlace' => '#');
          return View::make('profesor.monitorear.evaluaciones.lista')
                          ->with('curso', $curso)
                          ->with('modulos', $curso->get_modulos())
                          ->with('path', $path);
        } else { #una evaluacion en particular
          #validamos que un curso si tenga un taller
          if (!$curso->tiene_evaluacion($value1)) {
            return Redirect::to("curso/monitorear/{$curso->id}");
          }


          if ($method != null) { #se va a mostrar algo de la evaluaciones seleccionada
            $evaluacion = evaluacion::find($value1);
            $path[2] = array('nombre' => 'Evaluaciones', 'enlace' => url("curso/monitorear/{$curso->id}/evaluaciones"));
            $path[3] = array('nombre' => $evaluacion->nombre, 'enlace' => url("curso/monitorear/{$curso->id}/evaluaciones/{$evaluacion->id}"));
            if ($method == "estudiantes") {



              $usuario = usuario::find($value2);
              $evaluacion = evaluacion::find($value1);
              #verificamos que el usuario tenga inscrito el curso
              if (!$usuario || ($usuario && !$usuario->tiene_inscrito($curso->id))) {
                return Redirect::to("curso/monitorear/{$curso->id}/evaluaciones/{$value1}");
              }

              $path[4] = array('nombre' => $usuario->nombres . " " . $usuario->apellidos, 'enlace' => '#');
              return View::make('profesor.monitorear.evaluaciones.estudiante')
                              ->with('curso', $curso)
                              ->with('modulos', $curso->get_modulos())
                              ->with('evaluacion', $evaluacion)
                              ->with('path', $path)
                              ->with('usuario', $usuario);
            } else if ($method == "envios") {



              $envio = envio::find($value2);
              $usuario = $envio ? usuario::find($envio->usuario) : null;

              if (!$usuario || ($usuario && !$usuario->tiene_inscrito($curso->id))) {
                return Redirect::to("curso/monitorear/{$curso->id}/evaluaciones/{$value1}");
              }


              $path[4] = array('nombre' => $usuario->nombres . " " . $usuario->apellidos, 'enlace' => url("curso/monitorear/{$curso->id}/evaluaciones/{$value1}/estudiantes/{$usuario->id}"));
              $path[5] = array('nombre' => 'Envio ' . $envio->id, 'enlace' => '#');
              return View::make('profesor.monitorear.envio.envio')
                              ->with('curso', $curso)
                              ->with('modulos', $curso->get_modulos())
                              ->with('path', $path)
                              ->with('usuario', $usuario)
                              ->with('envio', $envio);
            } else if ($method == "ejercicios") {

              if ($value2 == null) { #todos los ejercicios
                $path[4] = array('nombre' => 'Ejercicios', "#");
                return View::make('profesor.monitorear.evaluaciones.ejercicios')
                                ->with('curso', $curso)
                                ->with('path', $path)
                                ->with('evaluacion', evaluacion::find($value1))
                                ->with('ejercicios', evaluacion::find($value1)->get_ejercicios());
              } else { #un ejercicio en particular
                #verificar que un ejercicio si este en una evaluacion
                if (!evaluacion::find($value1)->tiene_ejercicio($value2)) {
                  return Redirect::to("curso/monitorear/{$curso->id}/evaluaciones/{$value1}/ejercicios");
                }


                $ejercicio = ejercicio::find($value2);

                $path[4] = array('nombre' => 'Ejercicios', 'enlace' => url("curso/monitorear/{$curso->id}/evaluaciones/{$value1}/ejercicios"));
                $path[5] = array('nombre' => $ejercicio->nombre, 'enlace' => "#");

                return View::make('profesor.monitorear.evaluaciones.ejercicio')
                                ->with('curso', $curso)
                                ->with('path', $path)
                                ->with('evaluacion', evaluacion::find($value1))
                                ->with('ejercicio', $ejercicio);
              }
            }
          } else { #se mostrara la evaluacion en particular seleccionada
            $evaluacion = evaluacion::find($value1);
            $path[2] = array('nombre' => 'Evaluaciones', 'enlace' => url("curso/monitorear/{$curso->id}/evaluaciones"));
            $path[3] = array('nombre' => $evaluacion->nombre, 'enlace' => "#");

            return View::make('profesor.monitorear.evaluaciones.evaluacion')
                            ->with('curso', $curso)
                            ->with('evaluacion', $evaluacion)
                            ->with('modulos', $curso->get_modulos())
                            ->with('path', $path)
                            ->with('estudiantes', $curso->get_estudiantes());
          }
        }
      } else if ($opcion == 'estudiantes') {
        $path[2] = array('nombre' => 'Estudiantes', 'enlace' => '#');
        $estudiantes = $curso->get_estudiantes();
        return View::make('profesor.monitorear.estudiantes.lista')
                        ->with('curso', $curso)
                        ->with('estudiantes', $estudiantes)
                        ->with('path', $path);
      } else if ($opcion == "estudiante") {

        #si no tiene el id del estudiante que quiere mirar
        if ($value1 == null) {
          return Redirect::to("curso/monitorear/{$curso->id}/estudiantes");
        }

        #verificamos que el usuario si pertenezca al curso
        $usuario = usuario::find($value1);
        if (!$usuario || ($usuario && !$usuario->tiene_inscrito($curso->id))) {
          return Redirect::to("curso/monitorear/{$curso->id}/estudiantes");
        }


        $path[2] = array('nombre' => 'Estudiantes', 'enlace' => url('curso/monitorear/') . '/' . $curso->id . '/estudiantes');
        $path[3] = array('nombre' => $usuario->nombres . " " . $usuario->apellidos, 'enlace' => url("curso/monitorear/{$curso->id}/estudiante/{$value1}"));
        if ($method == null) { #se mostrara toda la informacion de un estudiante
          return View::make('profesor.monitorear.estudiantes.estudiante')
                          ->with('curso', $curso)
                          ->with('estudiante', $usuario)
                          ->with('logueos', $usuario->get_bitacora($curso->id))
                          ->with('path', $path);
        } else { #se mostrara la información de un taller o una evaluacion correspondiente a un estudiante
          if ($method == "taller") {
            if ($value2 == null) { #informacion de todos los talleres 
              $path[4] = array('nombre' => 'Estadisticas talleres', 'enlace' => '#');
              return View::make('profesor.monitorear.estudiantes.talleres')
                              ->with('curso', $curso)
                              ->with('estudiante', usuario::find($value1))
                              ->with('path', $path);
            } else { #informacion de un solo taller
              #verificamos que tiene taller
              if (!$curso->tiene_taller($value2)) {
                return Redirect::to("curso/monitorear/{$curso->id}/estudiante/{$value1}");
              }

              $taller = taller::find($value2);
              $path[4] = array('nombre' => 'Estadísticas taller ' . $taller->nombre, 'enlace' => '#');

              return View::make('profesor.monitorear.estudiantes.taller')
                              ->with('curso', $curso)
                              ->with('estudiante', usuario::find($value1))
                              ->with('path', $path)
                              ->with('taller', $taller);
            }
          } else if ($method == "evaluacion") {
            if ($value2 == null) { #informacion de todas las evaluaciones 
              $path[4] = array('nombre' => 'Estadisticas evaluaciones', 'enlace' => '#');
              return View::make('profesor.monitorear.estudiantes.evaluaciones')
                              ->with('curso', $curso)
                              ->with('estudiante', usuario::find($value1))
                              ->with('path', $path);
            } else { #información de una sola evaluacion
              #verificamos que tenga evaluaciones
              if (!$curso->tiene_evaluacion($value2)) {
                return Redirect::to("curso/monitorear/{$curso->id}/estudiante/{$value1}");
              }

              $evaluacion = evaluacion::find($value2);
              $path[4] = array('nombre' => 'Estadísticas evaluacion ' . $evaluacion->nombre, 'enlace' => '#');
              return View::make('profesor.monitorear.estudiantes.evaluacion')
                              ->with('curso', $curso)
                              ->with('estudiante', usuario::find($value1))
                              ->with('path', $path)
                              ->with('evaluacion', $evaluacion);
            }
          }
        }
      } else {
        return Redirect::to('curso/monitorear/' . $curso);
      }
    } else {
      Session::flash("invalid", "Usted no tiene permisos sobre este objeto");
      return Redirect::to("curso/all");
    }
  }

  #funcion para personalizar el avatar 

  public function getPersonalizarAvatar() {

    $usuario = usuario::find(Auth::user()->id);

    return View::make('estudiante.personalizar_avatar')
                    ->with('comprados', $usuario->get_items_comprados())
                    ->with('disponibles', $usuario->get_items_no_comprados());
  }

  #funcion que muestra los mensajes recibidos

  public function getMensajes($curso) {
    Return View::make('curso.mensajes.mensajes');
  }

  //publica en el muro del curso

  public function postPublicar() {
    $notificacion = array(
        'curso' => Crypt::decrypt(Input::get('curso')),
        'usuario' => Auth::user()->id,
        'publicacion' => Input::get('publicacion'),
        'tipo' => 0,
        'codigo' => 0
    );

    notificacion::create($notificacion);
    Session::flash("valid", 'Tu publicación ha sido posteada');
    return Redirect::to("curso/ver/{$notificacion['curso']}/inicio");
  }

  //retorna el ranking de un curso

  public function postRanking() {
    if (Request::ajax()) {
      $top = Input::get('top');
      $curso = Input::get('curso');

      $ranking = curso::find($curso)->get_ranking($top);

      return Response::json($ranking);
    }
  }

  #asigna monitores al curso

  public function getAsignarMonitor() {
    if (Request::ajax()) {
      $curso = curso::find(Input::get('curso'));
      $profesor = Auth::user()->id;
      if ($curso && $curso->profesor_id == $profesor) {
        echo $curso->asignar_monitor(Input::get('monitor'));
         
      }
    }
  }

  #muesta los avatars disponibles

  public function getAvatars() {

    return View::make('curso.avatars.avatars2');
  }

  #retorna a todos los cursos en caso que se intente ingresar a un metodo no valido

  public function missingMethod($parameters = array()) {
    Session::flash("invalid", 'Operación inválida');
    return Redirect::to('curso');
  }

  public function postJson($tipo) {
    switch ($tipo) {
      case 'notificaciones': //retorna las notificaciones del curso
        $curso = Input::get('curso');
        $curso = curso::find($curso);
        return Response::json($curso->get_notificaciones());
        break;
      case 'monitorear_taller':
            $taller= Input::get('taller');
            $curso = curso::find(modulo::find($taller)->curso);
            return Response::json($curso->monitorear_taller($taller));
      break;
      case 'monitorear_estudiantes':
            $curso = curso::find(Input::get('curso'));            
            return Response::json($curso->monitorear_estudiantes());
      break;
      case 'envios': //retorna las notificaciones del curso
        $curso = curso::find($curso);
        $envios = $curso->get_cola_envios();
        $data = array();
        foreach ($envios as $envio) {
          $sub['id'] = $envio->id;
          $sub['ejercicio'] = $envio->ejercicio;
          $sub['estudiante'] = $envio->usuario;
          $sub['respuesta'] = $envio->resultado;
          $res = $sub['respuesta'];
          $sub['color'] = ($res == 'accepted') ? 'info' : ($res == 'wrong answer') ? 'alert' : ($res == 'time limit') ? 'info' : ($res == 'compilation error') ? 'success' : '';
          $data[] = $sub;
        }
        return Response::json($data);
        break;
        
      case 'info_logros':
        $curso = curso::find(Input::get('curso'));
        return Response::json($curso->get_info_logros());
        break;
        
      default:
        return Response::json(['error' => 'No existe el tipo de request']);
        break;
    }
  }

}
