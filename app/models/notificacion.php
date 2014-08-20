<?php

class Notificacion extends Eloquent {

    protected $table = 'notificacion';
    protected $guarded = array();

    public function numero_de_me_gusta() {
        //  var_dump($notificacion);
        return DB::table('me_gusta')
                        ->where('notificacion', $this->id)
                        ->count();
    }

    public function gusta($usuario) {
        return DB::table('me_gusta')
                        ->where('notificacion', $this->id)
                        ->where('usuario', $usuario)
                        ->count() == 1;
    }

    public function get_propietario() {
        return $this->usuario;
    }

    public function esta_compartida($rs = 'f') {

        if ($rs == 'f') {
            return $this->compartida_facebook == 1;
        } else if ($rs == 't') {
            return $this->compartida_twitter == 1;
        }
    }

    public function get_comentarios() {
        return DB::table('notificacion')
                        ->where('tipo', 5) #tipo de los comentarios
                        ->where('codigo', $this->id)                        
                        ->orderBy('id')
                        ->get();
    }
    
    public function get_a_quien_le_gusta(){
       return DB::table('usuario')
                ->join('me_gusta','me_gusta.usuario','=','usuario.id')
                ->where('me_gusta.notificacion',$this->id)
                ->get();
    }

}

?>
