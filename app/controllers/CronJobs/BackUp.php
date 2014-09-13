<?php

require '../public/libs/phpseclib/Net/SSH2.php';

class BackUp {

    protected $folder = "/backups/bd/";
    protected $prefix = "-cpp-bk-";
    protected $nbackups = 7;
    protected $extension = '.sql.zip';
    protected $so = 'windows'; #1 -> windows , 0 -> linux
    protected $ssh;

    function __construct() {

        $this->ssh = new Net_SSH2('168.176.125.196');
        if (!$this->ssh->login('root', 'qwe123admin')) {
            exit('Login Failed in ssh');
        }

        $this->list_files();
        exit;
    }

    public function generate() {
        $this->ssh->exec("mysqldump -u root -pqwe123admin lms | zip > {$this->get_full_file_path()}");
    }

    private function list_files() {
         $files =  $this->ssh->exec("ls {$this->get_full_folder_path()}");
        //$files = $this->ssh->exec("ls");
        echo "<pre>";
        var_dump($files);
        echo "</pre>";
    }

    private function remove($file) {
        
    }

    private function get_last_id_file() {
        return $this->id;
    }

    private function get_number_of_backups() {
        
    }

   

    private function get_full_file_path() {
        return $this->get_full_folder_path() . $this->get_file_name();
        
    }

    private function get_full_folder_path() {
        return public_path() . $this->folder;
    }

    private function get_file_name() {
        $id = $this->get_last_id_file() + 1;
        return $id . $this->prefix . date('Y-m-d') . $this->extension;
    }


}
