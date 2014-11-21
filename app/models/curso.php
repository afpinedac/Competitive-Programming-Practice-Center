<?php

class Curso extends Eloquent {

  protected $table = 'curso';
  //  public $timestamps = false;
  protected $guarded = array();

  public static function get_disponibles($usuario) {
    return( DB::table('usuario')
                    ->select(DB::raw('nombre,nombres,apellidos,created_at, lms_curso.id ,descripcion,publico,imagen'))
                    ->join('curso', 'usuario.id', '=', 'curso.profesor_id')
                    ->whereNotIn('curso.id', array_add(DB::table('curso_x_usuario')
                                    ->where('curso_x_usuario.usuario_id', $usuario)
                                    ->lists('curso_id'), -1, -1))
                    ->where('eliminado', 0) //que el curso no este eliminado
                    ->orderBy('created_at', 'desc')
                    ->get());
  }

  public function tiene_soluciones_visibles() {
    return $this->soluciones_visibles == 1;
  }

  public function asignar_monitor($estudiante) {
    $estudiante = usuario::find($estudiante);
    $rol = ($estudiante->es_monitor($this->id)) ? 0 : 1;
    DB::table('curso_x_usuario')
            ->where('curso_id', $this->id)
            ->where('usuario_id', $estudiante->id)
            ->update(array('rol' => $rol));
    return $rol;
  }

  public function terminado() {
    return $this->terminado == 1;
  }

  public function get_programador_de_la_semana($dias = 7) {
    $estudiantes = $this->get_estudiantes();
    $max = -1;
    $programmer_week = null;
    foreach ($estudiantes as $est) {
      $user = usuario::find($est->id);
      if ($user->es_monitor($this->id) || $user->es_propietario($est->id))
        continue;
      $puntos = $user->get_puntos_en_curso($this->id, $dias);
      if ($max < $puntos) {
        $programmer_week = $est;
        $max = $puntos;
      }
    }
    //return null;
    return $programmer_week;
  }

  public function tiene_chat() {

    return $this->chat == 1;
  }

  public static function get_inscritos($usuario) {


    return DB::table('curso')
                    ->select(DB::raw('lms_curso.id,lms_curso.nombre,lms_curso_x_usuario.fecha_inscripcion,lms_usuario.nombres as nombre_profesor,lms_usuario.apellidos as apellido_profesor, lms_curso.descripcion, lms_curso.publico, lms_curso.imagen , lms_curso.profesor_id as profesor'))
                    ->join('curso_x_usuario', 'curso.id', '=', 'curso_x_usuario.curso_id')
                    ->join('usuario', 'usuario.id', '=', 'curso.profesor_id')
                    ->where('curso_x_usuario.usuario_id', $usuario)
                    ->where('eliminado', 0) // que el curos no este eliminado
                    ->orderBy('created_at', 'desc')
                    ->get();
  }

  public static function get_creados($usuario) {
    return DB::table('curso')
                    ->where('profesor_id', $usuario)
                    ->where('eliminado', 0)
                    ->get();
  }

  public function desmatricular($usuario) {
    DB::table('curso_x_usuario')
            ->where('curso_id', $this->id)
            ->where('usuario_id', $usuario)
            ->delete();
  }

  public function freshTimestamp() {
    return date('Y-m-d h:i:s');
  }

  public function get_estudiantes() {
    return DB::table('usuario')
                    ->join('curso_x_usuario', 'curso_x_usuario.usuario_id', '=', 'usuario.id')
                    ->where('curso_x_usuario.curso_id', $this->id)
                    ->orderBy('nombres')
                    ->get();
  }

  public function numero_de_estudiantes() {
    return count(DB::table('usuario')
                    ->join('curso_x_usuario', 'curso_x_usuario.usuario_id', '=', 'usuario.id')
                    ->where('curso_x_usuario.curso_id', $this->id)
                    ->get()
    );
    //  var_dump($estudiantes);
  }

  public function get_modulos() {
    return DB::table('modulo')->where('curso', $this->id)->get();
  }

  public function get_primer_modulo() {

    $modulo = DB::table('modulo')
            ->where('curso', $this->id)
            ->first();

    return $modulo ? $modulo->id : -1;
  }

  public function get_numero_estudiantes_inscritos() {
    return cursoxusuario::where('curso_id', $this->id)->count();
  }

  public function get_numero_modulos() {
    return DB::table('curso')
                    ->join('modulo', 'curso.id', '=', 'modulo.curso')
                    ->where('curso.id', $this->id)
                    ->count();
  }

  public function get_numero_ejercicios() {
    return DB::table('modulo')
                    ->join('taller', 'modulo.id', '=', 'taller.id')
                    ->join('ejercicio_x_taller', 'ejercicio_x_taller.taller', '=', 'taller.id')
                    ->where('modulo.curso', $this->id)
                    ->count();
  }

  public function get_notificaciones() {
    return DB::table('notificacion')
                    ->select(DB::raw('lms_notificacion.id,lms_notificacion.created_at,lms_notificacion.publicacion,lms_usuario.nombres,lms_usuario.apellidos,lms_usuario.foto,lms_notificacion.codigo,lms_notificacion.tipo,lms_usuario.id as propietario'))
                    ->join('usuario', 'usuario.id', '=', 'notificacion.usuario')
                    ->where('curso', $this->id)
                    ->where('tipo', '<>', 5) //no traiga los comentarios
                    ->whereNotIn('notificacion.id', array_add(DB::table('notificacion')
                                    ->join('curso_x_usuario', 'curso_x_usuario.usuario_id', '=', 'notificacion.usuario')
                                    ->whereIn('curso_x_usuario.rol', [1, 2]) //rol de monitor y de estudiante
                                    ->where('curso_x_usuario.curso_id', $this->id)
                                    ->whereBetween('curso_x_usuario.curso_id', [1, 4])
                                    ->where('notificacion.curso', $this->id)
                                    ->lists('notificacion.id'), -1, -1))
                    ->orderBy('created_at', 'desc')
                    ->get();
  }

  #retorna el ranking como un array

  public function get_ranking($top = -1) {

    $ranking = DB::table('usuario')
            ->join('curso_x_usuario', 'usuario.id', '=', 'curso_x_usuario.usuario_id')
            ->where('curso_x_usuario.curso_id', $this->id)
            ->get();
    return $this->sort_ranking($ranking, $top);
  }

  #ordena el ranking

  private function sort_ranking($ranking, $top) {
    $nrank = array();
    foreach ($ranking as $user) {
      $newpos = (array) $user;
      $user = usuario::find($newpos['id']);
      if (!$user->es_propietario($this->id) && !$user->es_monitor($this->id)) {
        $newpos['puntos'] = usuario::find($newpos['id'])->get_puntos_en_curso($this->id);
        $nrank[] = $newpos;
      }
    }
    usort($nrank, function($a, $b) {
      return($b['puntos'] - $a['puntos']);
    });


    return $top == -1 ? $nrank : array_slice($nrank, 0, $top);
  }

  #numero de modulos desbloqueados

  public function get_numero_modulos_desbloqueados() {

    $modulos = $this->get_modulos();
    $c = 0;
    foreach ($modulos as $modulo) {
      if (modulo::find($modulo->id)->esta_desbloqueado()) {
        $c++;
      }
    }
    return $c;
  }

  #-----VALIDACIONES--------------
  #verifica si un curso tiene un taller

  public function tiene_taller($taller) {
    return DB::table('modulo')
                    ->where('curso', $this->id)
                    ->where('id', $taller)
                    ->count() == 1;
  }

  #verifica si un curso tiene un evaluacion

  public function tiene_evaluacion($evaluacion) {
    return DB::table('modulo')
                    ->join('evaluacion', 'evaluacion.modulo', '=', 'modulo.id')
                    ->where('modulo.curso', $this->id)
                    ->where('evaluacion.id', $evaluacion)
                    ->count() == 1;
  }

  #verifica se tenga un tema_foro

  public function tiene_tema_foro($tema) {
    return DB::table('tema_foro')
                    ->where('id', $tema)
                    ->where('curso', $this->id)
                    ->count() == 1;
  }

  #funciones de monitoreo ordenables
  #s hace referencia al id por ejemplo del taller o de la evaluación

  public function monitorear_taller($s) {
    $estudiantes = $this->get_estudiantes();
    foreach ($estudiantes as $estudiante) {
      $usuario = usuario::find($estudiante->id);
      $arr = [
          'id' => $estudiante->id,
          'nombre_completo' => ucfirst($estudiante->nombres). " " . ucfirst($estudiante->apellidos),
          'porcentaje' => $usuario->get_porcentaje_en_taller($s),
          'ultimo_envio' => $usuario->get_fecha_ultimo_envio_en_taller($s),
          'ejercicios_resueltos' => $usuario->get_numero_ejercicios_resultos_en_taller($s),
          'envios_en_taller' => $usuario->get_numero_envios_en_taller($s)
      ];
      $estud_sort[] = $arr;
    }
    return $estud_sort;
  }
  
  
  public function monitorear_estudiantes() {
    $estudiantes = $this->get_estudiantes();
    $estud_sort = [];
   foreach ($estudiantes as $estudiante) {
     $usuario = usuario::find($estudiante->id);
                $arr = array(
                    'id' => $estudiante->id,
                    'nombre_completo' => ucfirst($estudiante->nombres) . " " . ucfirst($estudiante->apellidos),
                    'ultimo_acceso' => $usuario->get_ultimo_acceso($this->id),
                    'ejercicios_resueltos' => $usuario->numero_de_ejercicios_resueltos_curso($this->id),
                    'puntos' => $usuario->get_puntos_en_curso($this->id),
                    'tiempo_logueado' => $usuario->get_tiempo_logueado($this->id),
                    'es_monitor' => $usuario->es_monitor($this->id)
                );
                $estud_sort[] = $arr;
            }
    return $estud_sort;
  }

  //función que retorna la cola de envios 
  public function get_cola_envios($mins = 10) {
    $time = time() - (60 * $mins);    //los envios en los útimos 10 minutos
    $time = date("Y-m-d H:i:s", $time);
    //  echo "--> {$curso}";
    $envios = DB::table('envio')
            ->where('created_at', '>', $time)
            ->where('curso', $this->id)
            ->where('test', 0)
            ->orderBy('id', 'desc')
            ->get();
  }

}
