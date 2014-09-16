<?php

abstract class BackUp extends CronJob {

    protected $extension;
    protected $prefix;
    protected $file_name;
    protected $nbackups;
    
    function __construct($extension, $prefix, $nbackups) {
        $this->extension = $extension;
        $this->prefix = $prefix;
        $this->nbackups = $nbackups;
    }

    
    public abstract function generate();

    public abstract function clean();


    #función que crea el nombre del backup

    protected function create_file_name($folder) {
        $id = static::get_last_id_file('max', $folder) + 1;        
        return $id . $this->prefix . date('Y-m-d') . $this->extension;
    }

    #función que retorna la lista de backups
    protected static function get_list_of_backups($folder) {    
        $files = CronJob::exec("ls {$folder}");
        $files = str_replace("\r\n", "\n", trim($files));
        return explode("\n", $files);
    }
#función que retorna el número de backups en la carpeta
    protected static function get_number_of_backups($folder) {
        return count(static::get_list_of_backups($folder));
    }

    #función que retorna el nombre de un archivo con un determinado id    

    protected static function get_file_name_by_id($id, $folder) {
        $files = static::get_list_of_backups($folder);
        foreach ($files as $file) {
            $f = explode("-", trim($file));
            if ($f[0] == $id)
                return $file;
        }
    }

    #función que obtienen el id del ultimo archivo en la carpeta
    protected static function get_last_id_file($type, $folder) {       
        $files = static::get_list_of_backups($folder);      
        $value = $type == 'min' ? 50000 : 0;
        foreach ($files as $file) {
            $f = explode("-", trim($file));
            $value = ($type == 'min') ? min($value, (int) $f[0]) : max($value, (int) $f[0]);
        }
        return $value;
    }

}
