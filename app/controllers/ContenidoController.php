<?php

class ContenidoController extends LMSController {

    public function postCrear() {
        $contenido = Input::except('_token');
        $contenido['modulo'] = Session::get('modulo_profesor');

        $contenido = DB::table('contenido')->insertGetId($contenido);

        if (Input::hasFile('archivo')) {
            $file = Input::file('archivo')->getClientOriginalName();
            $idx = strpos($file, '.');
            $new_name = General::replaceAccents($contenido . "_" . substr($file, 0, $idx) . '.' . (substr($file, $idx + 1)));
            //renombramos el archivo a id_nombre.extension
            contenido::find($contenido)->update(array('archivo' => $new_name));
            //movemos el archivo
            Input::file('archivo')->move($this->ruta['contenido'], $new_name);
        }
        Session::flash("valid", 'Material subido correctamente');
        return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
    }

    public function getDescargar($material) {

        $material = LMSController::decoder($material);

        $material = contenido::find($material);

        if ($material) {

            $extension = explode('.', $material->archivo);
            $extension = $extension[count($extension) - 1];
            $file_name = $material->nombre . '.' . $extension;

            return Response::download($this->ruta['contenido'] . '/' . $material->archivo, General::replaceAccents($file_name));
        } else {
            return Redirect::to('curso');
        }
    }

    public function postEditar() {
        //verificamos que el profesor si puedea editar ese campo

        $error = false;

        $contenido = contenido::find(Input::get('contenido'));

        if ($contenido) {
            if (Auth::user()->id == curso::find(modulo::find($contenido->modulo)->curso)->profesor_id) {


                $n_contenido = array(
                    'nombre' => Input::get('nombre'),
                    'descripcion' => Input::get('descripcion'),
                    'enlace' => Input::get('enlace')
                );

                contenido::where('id', $contenido->id)->update($n_contenido);



                if (Input::hasFile('archivo')) {
                    $file = Input::file('archivo')->getClientOriginalName();
                    $idx = strpos($file, '.');
                    $new_name = $contenido->id . "_" . substr($file, 0, $idx) . '.' . (substr($file, $idx + 1));
                    //renombramos el archivo a id_nombre.extension
                    contenido::find($contenido->id)->update(array('archivo' => $new_name));
                    //movemos el archivo
                    Input::file('archivo')->move($this->ruta['contenido'], $new_name);
                }
            }
        } else {
            $error = true;
        }

        if ($error) {
            Session::flash("invalid", "No tiene permiso sobre este material");
        } else {
            Session::flash("valid", "Material editado correctamente");
        }


        return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
    }

    public function getEliminar($contenido) {
        #valida que el que va a eliminar es el profesor que lo creó

        $contenido = contenido::find($contenido);
        if ($contenido) {
            $modulo = modulo::find($contenido->modulo);
            $curso = curso::find($modulo->curso);
            if ($curso->profesor_id == Auth::user()->id) {
                $this->eliminar_archivo($this->ruta['contenido'] . '/' . $contenido->archivo);
                $contenido->delete();
                Session::flash("valid", "Material eliminado correctamente");
            } else {
                Session::flash("invalid", 'No tiene permiso para eliminar este material');
            }
        } else {
            Session::flash("invalid", 'No tiene permiso para eliminar este material');
        }



        return Redirect::to('curso/ver/' . Session::get('curso') . '/editar');
    }

    #funcion que retorna los datos de un material en formato json

    public function postInfojson() {

        if (Request::ajax()) {
            $material = Input::get('material');
            return Response::json(contenido::find($material));
        }
    }

    #funcion que comenta un material    

    public function postComentar() {

        if (Request::ajax()) {

            $comentario = Input::get('comentario');
            $usuario = Auth::user()->id;
            $contenido = Input::get('contenido');


            DB::table('comentario_contenido')
                    ->insert(array(
                        'comentario' => $comentario,
                        'usuario' => $usuario,
                        'contenido' => $contenido,
                        'fecha' => date('Y-m-d')
            ));
            echo "1";
        }
    }

    #funcion que retorna los comentarios de un material

    public function postComentarios() {

        if (Request::ajax()) {


            $contenido = contenido::find(Input::get('contenido'));


            $data['total'] = $contenido->numero_de_comentarios();
            $data['comentario'] = $contenido->get_comentarios();


            return Response::json($data);
        }
    }

    //funcion que valora un recurso de un profesor
    public function postValorar() {

        if (Request::ajax()) {

            //obtenemos los datos
            $data = Input::all();

            //    var_dump($data);


            if (!usuario::find(Auth::user()->id)->valoro_contenido($data['contenido'])) {   // el usuario no ha valorado el contenido
                //Insertamos en la base de datos
                DB::table('valoracion_contenido')->insert(
                        array(
                            'puntuacion' => $data['puntuacion'],
                            'usuario' => Auth::user()->id,
                            'fecha' => date('Y-m-d'),
                            'contenido' => $data['contenido']
                        )
                );
            } else { //actualizamos la valoracion
                DB::table('valoracion_contenido')
                        ->where('usuario', Auth::user()->id)
                        ->where('contenido', $data['contenido'])
                        ->update(array('puntuacion' => $data['puntuacion']));
            }


            $contenido = $data['contenido'];
            unset($data);




            $contenido = contenido::find($contenido);

            $data['promedio'] = $contenido->get_promedio_valoracion();
            $data['valoraciones'] = $contenido->numero_de_valoraciones();


            return Response::json($data);
        }
    }

    //funcion que elimina un comentario

    public function postEliminarcomentario() {

        if (Request::ajax()) {
            $comentario = Input::get('comentario');

            if (DB::table('comentario_contenido')->where('id', $comentario)->first()->usuario == Auth::user()->id) {
                DB::table('comentario_contenido')->where('id', $comentario)->delete();
            }

            echo "1";
        }
    }

    #funcion para eliminar un archivo

    public function postEliminararchivo() {

        if (Request::ajax()) {
            $material = contenido::find(Input::get('material'));
            unlink($this->ruta['contenido'] . $material->archivo);
            contenido::where('id', $material->id)->update(array('archivo' => ''));
            echo "1";
        }
    }

}
