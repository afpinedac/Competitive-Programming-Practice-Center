<?php

class ApiController extends LMSController {

    public function getIn($ejercicio) {

        $person = Auth::user()->id;
        if (in_array($person, array(1, 5))) {

            $ejercicio = ejercicio::find($ejercicio);

            if ($ejercicio)
                return "<pre>" . $ejercicio->in . "</pre>";
        }
    }

    public function getOut($ejercicio) {

        $person = Auth::user()->id;
        if (in_array($person, array(1, 5))) {

            $ejercicio = ejercicio::find($ejercicio);

            if ($ejercicio)
                return "<pre>" . $ejercicio->out . "</pre>";
        }
    }

    

}
