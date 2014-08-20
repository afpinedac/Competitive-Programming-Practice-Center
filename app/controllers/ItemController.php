<?php

class ItemController extends LMSController {

    public function getComprar($item = null) {


        $item = item::find($item);

        $usuario = usuario::find(Auth::user()->id);

        if ($item) { #si el item existe
            #si el item no ha sido comprado
            if (!$usuario->ha_comprado_item($item->id)) {

                if ($usuario->get_dinero_total() >= $item->precio) { #si el usuario tiene el dinero para comprar el item
                    #se debita el dinero
                    
                    $usuario->sumar_plata(-1 * $item->precio);

                    #se inserta en la lista de items del usuario
                    DB::table('item_x_usuario')
                            ->insert(array(
                                'usuario' => $usuario->id,
                                'item' => $item->id,
                                'usando' => true
                    ));

                    # TODO: se recalcula la imagen del avatar

                    Session::flash("valid", "El item '{$item->nombre}' ha sido comprado.");
                } else {
                    Session::flash("invalid", 'No tienes el dinero suficiente');
                }
            } else { # si el item ya lo compró el usuario
                Session::flash("invalid", 'El item seleccionado ya ha sido comprado');
            }
        } else {
            Session::flash("invalid", 'El item seleccionado es inválido');
        }



        return Redirect::to("curso/personalizar-avatar");
    }

    public function getUsar($item, $opcion) {
        $item = item::find($item);
        $usuario = usuario::find(Auth::user()->id);


        if ($item && ($opcion == 'no' || $opcion == 'si')) { #si el item existe y se puede realizar la acción
            if ($usuario->ha_comprado_item($item->id)) {
                $usar = false;
                if ($opcion == 'si') { #si va a usar el item
                    $usar = true;
                    Session::flash("valid", "Ahora usas '{$item->nombre}'");
                } else {
                    Session::flash("valid", "Dejaste de usar '{$item->nombre}'");
                }
                DB::table('item_x_usuario')
                        ->where('item', $item->id)
                        ->where('usuario', $usuario->id)
                        ->update(array('usando' => $usar));

                #TODO: recalcular el avatar
            } else {
                Session::flash("invalid", 'El usuario no ha comprado este ítem');
            }
        } else {
            Session::flash("invalid", 'El item o la acción son inválidos');
        }

        return Redirect::to("curso/personalizar-avatar");
    }

    #funcion que vende un item

    public function getVender($item) {

        $item = item::find($item);
        $usuario = usuario::find(AUth::user()->id);
        if ($item) {
            if ($usuario->ha_comprado_item($item->id)) {
                #se elimina el item al usuario
                DB::table('item_x_usuario')
                        ->where('item', $item->id)
                        ->where('usuario', $usuario->id)
                        ->delete();

                #se le da la plata al usuario (siempre ha mitad de precio)
                $venta = $item->precio / 2;
                $usuario->sumar_plata($item->precio / 2);

                Session::flash("valid", "Se ha vendido el ítem '{$item->nombre}' por \${$venta} pesos");
            } else {
                Session::flash("invalid", 'El usuario no ha comprado este ítem');
            }
        } else {
            Session::flash("invalid", 'El ítem es inválido');
        }
        
        return Redirect::to("curso/personalizar-avatar");
        
        
    }

}
