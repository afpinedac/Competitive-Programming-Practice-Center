<?php

class AdminController extends LMSController {

  public function getIndex() {
    return View::make('admin.admin');
  }

  //función que restaura la contraseña de una persona
  public function getRestaurarPassword($email) {
    $user = Usuario::where('email', $email)->first();
    if ($user) { // si el usuario si se encuentra en la bd
      //cambiamos la pass a 123
      DB::table('usuario')->where('id', $user->id)
              ->update(['password' => Hash::make('123')]);

      echo "La contraseña de { {$user->nombres} {$user->apellidos} } con email { {$user->email} } fue cambiada correctamente a  { 123 }";
    } else {
      echo "no existe un usuario con ese email";
    }
  }

}
