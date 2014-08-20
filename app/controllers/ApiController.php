<?php

class ApiController extends LMSController {

    public function getIn($ejercicio) {

        $person = Auth::user()->id;
        if (in_array($person, array(1, 5, 35))) {

            $ejercicio = ejercicio::find($ejercicio);

            if ($ejercicio)
                return "<pre>" . $ejercicio->in . "</pre>";
        }
    }

    public function getOut($ejercicio) {

        $person = Auth::user()->id;
        if (in_array($person, array(1, 5, 35))) {

            $ejercicio = ejercicio::find($ejercicio);

            if ($ejercicio)
                return "<pre>" . $ejercicio->out . "</pre>";
        }
    }

    public function getProcess() {
        $command = array(
            'get' => 'ps -Af | grep ain',
            'kill' => 'sudo -u root -pqwe123admin kill -9 '
        );
        $process = shell_exec($command['get']);

        $process = explode("\n", $process);
        foreach ($process as $line) {           
            //echo "<br> $line";
            $sub = substr($line,strlen($line) - 9, strlen($line));            
            //echo "<br> sub: $sub";
            if ($sub == "java Main") {
                $data = preg_split('/[\s]+/', $line);
               // var_dump($data);
                echo "kill : " .  $data[1] . "<br>";
                $response = shell_exec($command['kill'].$data[1]);
                var_dump($response);
            }
           // break;
        }
    }

}
