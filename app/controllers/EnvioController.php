<?php

class EnvioController extends LMSController{

    #TODO::terminar
    #retorna todos los envios que se hacen
    public function postAll(){
        
        
        if(Request::ajax()){
            
            $time = date("Y-m-d H:i:s", Input::get('time'));          
            
            
            
            
            $envios = DB::table('test')
                    ->where('created_at','>' , $time)
                    ->get();
            
            return Response::json($envios);            
            //return Response::json(array($time));            
            
            
        }
        
    }
    
}
