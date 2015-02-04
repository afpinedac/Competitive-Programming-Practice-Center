<?php

//clase que genera los backups de una 
class BackUpDataBase extends BackUp {

    protected $folder = '/backups/bd/';    
    protected $database = 'lms';
    private $fullpath;
    private $db_user = 'root';
    private $db_password = 'qwe123admin';

    function __construct() {
        parent::__construct('.sql.gz', '-cpp-bk-', 10);
        $this->folder = public_path() . $this->folder;
        //$this->folder = '/var/www/cpp2/public' . $this->folder;       
        $this->file_name = $this->create_file_name($this->folder);
        $this->fullpath = $this->folder . $this->file_name;
         echo "file generated: " . $this->file_name;
    }

#funcion que genera el backup

    public function generate() {
        //echo "command : " . "mysqldump -u {$this->user} -p{$this->password} {$this->database} | zip > {$this->fullpath}";
        CronJob::exec("mysqldump -u {$this->db_user} -p{$this->db_password} {$this->database} | zip > {$this->fullpath}");
    }

    #funcion que limpia los backups viejos

    public function clean() {
        if (static::get_number_of_backups($this->folder) >= $this->nbackups) {
            $cmd = "rm {$this->folder}".static::get_file_name_by_id(static::get_last_id_file('min', $this->folder), $this->folder);
            CronJob::exec($cmd);
        }
    }

}
