<?php

class RecursoController extends LMSController {

    public function postSubir() {
        $recurso = Input::except('_token');
        //   dd(Input::all());
        $recurso['modulo'] = Session::get('modulo.estudiante');
        $recurso['usuario'] = Auth::user()->id;
        $recurso['created_at'] = date('Y-m-d H:i:s');

        // dd($recurso);

        $recurso = DB::table('recurso')->insertGetId($recurso);

        if (Input::hasFile('archivo')) {
            $file = Input::file('archivo')->getClientOriginalName();
            $new_name = General::replaceAccents($recurso . "_" . $file);
            recurso::find($recurso)->update(array('archivo' => $new_name));
            Input::file('archivo')->move($this->ruta['recurso'], $new_name);
        }

        Session::flash("valid", "Recurso subido correctamente");

        return Redirect::to('curso/modulo/' . Session::get('modulo.estudiante'));
    }

    #funcion que elimina un recurso

    public function getEliminar($id) {

        $recurso = recurso::find($id);

        if ($recurso && $recurso->usuario == Auth::user()->id) {
            $this->eliminar_archivo($this->ruta['recurso'] . '/' . $recurso->archivo);
            Session::flash('valid', "Recurso eliminado correctamente");
            $recurso->delete();
        }

        return Redirect::to('curso/modulo/' . Session::get('modulo.estudiante'));
    }

    public function getDescargar($id) {
        $recurso = recurso::find(LMSController::decoder($id));

        if ($recurso) {

            $extension = explode('.', $recurso->archivo);
            $extension = $extension[count($extension) - 1];
            $file_name = $recurso->nombre . '.' . $extension;

            return Response::download($this->ruta['recurso'] . '/' . $recurso->archivo, $file_name);
        } else {
            return Redirect::to('curso');
        }
    }

}
