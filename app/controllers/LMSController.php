<?php

class LMSController extends BaseController {

    protected static $MAX_PUNTAJE_EJERCICIO = 26;
    protected $top_ranking = 10;
    public static $MINUTES_TO_OFFLINE = 5;
    public static $SOCKET_HOST = "localhost";
    public static $SOCKET_PORT = "3029";
    public static $avatares = array(
        'hombre' => '{"background":"5 (53)","body":"61","clothes":"71","head":"06","eyes":"16","nose":"212","mouth":"39","hair":"42","headColor":"ffcc9a","clothesColor":"384496","eyesColor":"7B4B2A","hairColor":"7b4b2a"}',
        'mujer' => '{"background":"5 (45)","body":"62","clothes":"72","head":"020","eyes":"117","nose":"22","mouth":"311","hair":"420","headColor":"ffcc9a","clothesColor":"262626","eyesColor":"7B4B2A","hairColor":"cd641b"}'
    );
    protected $rules = array(
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
    protected $prefix = array(
        'entrada' => '_file_in_',
        'salida' => '_file_out_',
        'formulacion' => '_file_form_',
        'imagen' => '_img_'
    );
    protected $ruta = array(
        'formulacion' => '_data_/formulaciones/',
        'contenido' => '_data_/materiales/contenido/',
        'recurso' => '_data_/materiales/recursos/',
        'envio' => '_data_/envio/', #el envio es de texto
        'in' => '_data_/in/', #el envio es de texto
        'out' => '_data_/out/', #el envio es de texto
        'img_cursos' => 'img/cursos/', #el envio es de texto
        'avatares' => 'avatares/', #el envio es de texto
    );
    protected $extension = array(
        'in' => '.txt',
        'out' => '.txt',
        'formulacion' => '.pdf'
    );
    protected $veredicto = array(
        'accepted' => 'Aceptado',
        'wrong answer' => 'Respuesta Incorrecta',
        'compilation error' => 'Error de Compilación',
        'time limit' => 'Tiempo límite excedido',
        'runtime error' => 'Error de ejecución'
    );

    protected function c($data, $tipo = 'entrada') {
        return md5($this->prefix[$tipo] . $data);
    }

    public $redes_sociales = array(
        'facebook' => array(
            'app_id' => '653403004704988',
            'app_secret' => '9a94d000434a35f41df7a1f740bc5cfc',
            'app_url' => 'http://localhost/lms/public/notificacion/cf/14',
            'scope' => 'email,publish_actions,publish_stream'
        ),
        'tw' => array(
            'consumer_key' => 'A6txHsxayVO38NiQwcHVow',
            'consumer_secret' => 'N2XIhyPQUvqYQodjSPrY1sZ8NCkKu8Fg0X7cWAucd4',
            'callback_url' => '-----',
            'MAX_LENGTH_PUBLICATION' => 140,
        )
    );

    function __construct() {

        if (Auth::check()) {
            $user = usuario::find(Auth::user()->id);
            if ($user) {
                $user->update_ultima_interaccion_en_curso(Session::get('curso.estudiante'));
            }
        }
    }

    public static function eliminar_carpeta($dir, $borrarme) {
        if (!$dh = @opendir($dir))
            return;
        while (false !== ($obj = readdir($dh))) {
            if ($obj == '.' || $obj == '..')
                continue;
            if (!@unlink($dir . '/' . $obj))
                static::eliminar_carpeta($dir . '/' . $obj, true);
        }
        closedir($dh);
        if ($borrarme) {
            @rmdir($dir);
        }
    }

    #retorna el string de un archivo

    public static function get_text_file($path) {
        $file = fopen(public_path() . '/' . $path, 'r');
        $s = "";
        while ($data = fgets($file, 255)) {
            $s .= $data;
        }
        return $s;
    }

    #escribe en un archivo

    public static function create_text_file($path, $str) {

        $file = fopen(public_path() . '/' . $path, 'w');
        fwrite($file, $str);
        fclose($file);
    }

    #funciton que elimina fisicamente un archivo

    protected function eliminar_archivo($file) {
        @unlink($file);
    }

    //formatea una fecha a la forma 1d 3h 5m
    // time es en segundos
    public static function formatear_tiempo($time, $unidad = 'm') {

        if ($unidad == 's') {
            $time = $time / 60;
        }

        $d = (int) ($time / (60 * 24));

        $h = (int) (($time - ($d * 60 * 24)) / 60);

        $m = (int) ($time - ($d * 60 * 24) - $h * 60);



        if ($d > 0)
            return "{$d}d  {$h}h  {$m}m";
        else if ($h > 0) {
            return "{$h}h  {$m}m";
        } else {
            return "{$m}m";
        }
    }

    public static function encoder($id) {
        $id = "_{$id}_";
        return base64_encode($id);
    }

    public static function decoder($hash) {
        $str = base64_decode($hash);
        return substr($str, 1, strlen($str) - 2);
    }
    
    


}
