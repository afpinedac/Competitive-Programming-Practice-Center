<?php

class CronJob {

    static $ssh_user = 'root';
    static $ssh_password = 'qwe123admin';
    static $server = '168.176.125.196';    

    static function exec($command) {
        
        $ssh = new Net_SSH2(static::$server);
        if (!$ssh->login(static::$ssh_user, static::$ssh_password)) {
            exit('Login Failed');
        }
        $val  = $ssh->exec($command);
        $ssh->disconnect();
        return $val;
       // $ssh->disconnect();
    }

}
