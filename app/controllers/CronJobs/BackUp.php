<?php

//clase que genera los backups de una 
class BackUp {

    protected $folder = '/backups/bd/';
    protected $prefix = '-cpp-bk';
    protected $nbackups = 10;
    protected $extension = '.sql.zip';
    protected $ssh;
    protected $filename;
    protected $database = 'lms';
    private $fullpath;
    private $user = 'root';
    private $password = 'qwe123admin';

    function __construct() {
        $this->folder = public_path() . $this->folder;
        #$this->folder = '/var/www/cpp2/public' . $this->folder;
        $this->ssh = new Net_SSH2('localhost');
        if (!$this->ssh->login('root', 'qwe123admin')) {
            exit('Login Failed in ssh');
        }
        $this->filename = $this->get_file_name();
        $this->fullpath = $this->folder . $this->filename;
      # echo "file generated: " . $this->filename;
    }

#funcion que genera el backup
    public function generate() {
        //echo "command : " . "mysqldump -u {$this->user} -p{$this->password} {$this->database} | zip > {$this->fullpath}";
        $this->ssh->exec("mysqldump -u {$this->user} -p{$this->password} {$this->database} | zip > {$this->fullpath}");
    }

    #funcion que limpia los backups viejos
    public function clean() {
        if ($this->get_number_of_backups() >= $this->nbackups) {
            $cmd = "rm {$this->folder}{$this->get_file_name_id($this->get_last_id_file('min'))}";
            $this->ssh->exec($cmd);
        }
    }

    #función que retorna todos los archivos en la carpeta de backups
    private function list_files() {
        $files = $this->ssh->exec("ls {$this->folder}");
        $files = str_replace("\r\n", "\n", trim($files));
        return explode("\n", $files);
    }

    #función que obtienen el id del ultimo archivo en la carpeta
    private function get_last_id_file($type) {
        $files = $this->list_files();
        $value = $type == 'min' ? 50000 : 0;
        foreach ($files as $file) {
            $f = explode("-", trim($file));
            $value = ($type == 'min') ? min($value, (int) $f[0]) : max($value, (int) $f[0]);
        }

        return $value;
    }

    #función que retorna el nombre de un archivo con un determinado id    
    private function get_file_name_id($id) {
        $files = $this->list_files();
        foreach ($files as $file) {
            $f = explode("-", trim($file));
            if ($f[0] == $id)
                return $file;
        }
    }

   #función que retorna el número de backups en la carpeta
    private function get_number_of_backups() {
        return count($this->list_files());
    }

    #función que crea el nombre del backup
    private function get_file_name() {
        $id = $this->get_last_id_file('max') + 1;
        return $id . $this->prefix . date('Y-m-d') . $this->extension;
    }

}
