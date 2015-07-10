<?php

class ModuloController extends LMSController {

  private $found;

  public function postCrear() {

    $curso = Session::get('curso');

    $modulo = [
        'nombre' => Input::get('nombre'),
        'curso' => $curso
    ];

    $module = DB::table('modulo')->insertGetId($modulo);
    taller::create(array(
        'id' => $module
    ));

    Session::put('modulo_profesor', $module);
    Session::flash("valid", "El módulo '{$modulo['nombre']}' fue creado correctamente");

    return Redirect::to("curso/ver/$curso/editar");
  }

  public function getEliminar($modulo) {
    $modulo = modulo::find($modulo);
    if ($modulo && $this->curso_tiene_modulo($modulo->id)) {
      #se eliminan todos los recursos y materiales del profesores 


      $materiales = $modulo->get_materiales();
      $recursos = $modulo->get_recursos();

      #eliminar todos los materiales
      foreach ($materiales as $material) {
        $this->eliminar_archivo($this->ruta['contenido'] . '/' . $material->archivo);
      }

      #eliminar todos los recursos subidos por los estudiantes
      foreach ($recursos as $recurso) {
        $this->eliminar_archivo($this->ruta['recurso'] . '/' . $recurso->archivo);
      }




      modulo::destroy($modulo->id);
      Session::put('modulo_profesor', curso::find(Session::get('curso'))->get_primer_modulo());

      Session::flash("valid", "Módulo eliminado correctamente");
    } else {
      Session::flash("invalid", 'No tiene permiso sobre este objeto');
    }


    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  private function curso_tiene_modulo($modulo) {

    return DB::table('modulo')
                    ->where('curso', Session::get('curso'))
                    ->where('id', $modulo)->count() == 1;
  }

  public function getEditar($modulo) {
    Session::put('modulo_profesor', $modulo);

    //var_dump($modulo);
    // exit;
    return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
  }

  public function getEstablecerPrerequisito() {
    if (Request::ajax()) {

      $a = Input::get('a');
      $b = Input::get('b');
      $tipo = Input::get('tipo');

      if ($tipo == 'true') {  // se va a establecer una nueva relacion
        $this->found = false;
        $this->is_conexion_valida($b, $a);
        if ($this->found) {
          echo "0";
        } else {
          DB::table('modulo_requisito')->insert(array('modulo' => $b, 'requisito' => $a));
          echo "1";
        }
      } else {  // se va a quitar un prerequisito
        modulorequisito::where('modulo', $b)->where('requisito', $a)->delete();
        echo '1';
      }
      exit;
    }
  }

  public function is_conexion_valida($start, $end) {

    if ($start == $end) {
      $this->found = true;
    }

    $hijos = modulorequisito::where('requisito', $start)->get();

    foreach ($hijos as $hijo) {
      $this->is_conexion_valida($hijo->modulo, $end);
    }
  }

  #edita la informacion de un modulo

  public function postEditar($modulo) {
    $curso = Crypt::decrypt(Input::get('curso'));
    $modulo = modulo::find($modulo);

    if ($modulo->curso == $curso && curso::find($curso)->profesor_id == Auth::user()->id) {
      $minimo = Input::get('minimo_para_desbloquear');
      $total = $modulo->get_numero_ejercicios($modulo);

      if ($minimo <= $total && $minimo >= 0) {

        modulo::where('id', $modulo->id)->update(array(
            'nombre' => Input::get('nombre', '-'),
            'minimo_para_desbloquear' => $minimo
        ));
        Session::flash("valid", 'Información actualizada correctamente');
      } else {
        Session::flash('error', true);
      }


      return Redirect::to("curso/ver/{$curso}/editar");
    } else {
      return Redirect::to("curso/all");
    }
  }

}
