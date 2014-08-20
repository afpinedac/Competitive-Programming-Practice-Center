<?php

class Alerta extends Eloquent {

    protected $table = 'alerta';
    protected $guarded = array();
    public $timestamps = false;

    #tipo = 1, like
    #tipo = 2, comentario
    #tipo = 3, RespuestaForo
    #tipo = 4, Foro creado

    public static function crear($to, $from, $link, $tipo, $mensaje) {

        $c = alerta::conteo($to, $link);
        $conteo = 1;
        if ($c > 0) { #si ya existe una notificacion para ese usuario con ese mismo link
            #las eliminamos
            $alert = DB::table('alerta')
                    ->where('to', $to)
                    ->where('enlace', $link)
                    ->first();

            $c = $alert->conteo;
            DB::table('alerta')
                    ->where('to', $to)
                    ->where('enlace', $link)
                    ->delete();

            $g = usuario::find($from);

            switch ($tipo) {
                case '1':
                    $mensaje = "A " . $g->nombres . " y $c persona" . ($c == 1 ? "" : "s") . " más les gusta tu publicación";
                    break;
                case '2':
                    $mensaje = $g->nombres . " y $c persona" . ($c == 1 ? "" : "s") . " más han comentado tu publicación";
                    break;

                case '3':
                    $mensaje = $g->nombres . " y $c persona" . ($c == 1 ? "" : "s") . " han respondido en un tema del foro en el que has participado";
                    break;
                case '4':
                    $mensaje = $g->nombres . " y $c persona" . ($c == 1 ? "" : "s") . " han respondido en un tema del foro que creaste";
                    break;
            }
            $conteo = $c + 1;
        }
        alerta::create(array(
            'from' => $from,
            'to' => $to,
            'enlace' => $link,
            'mensaje' => $mensaje,
            'conteo' => $conteo,
            'curso' => Session::get('curso.estudiante')
                )
        );
    }

    public static function conteo($usuario, $link) {
        return alerta::where('to', $usuario)
                        ->where('enlace', $link)
                        ->count();
    }

}
