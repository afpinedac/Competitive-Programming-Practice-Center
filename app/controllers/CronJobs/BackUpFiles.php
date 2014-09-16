<?php

class BackUpFiles extends BackUp {

    
    protected $source_folder = '.....';
    protected $destination_folder = '/backup/files/';
    
    
    

    function __construct() {
        parent::__construct();
        $this->nbackups = 10;
    }
    
    public function generate(){
        $this->ssh->exec("mysqldump -u {$this->db_user} -p{$this->db_password} {$this->database} | zip > {$this->fullpath}");
    }

    public function clean() {
        
    }

}
