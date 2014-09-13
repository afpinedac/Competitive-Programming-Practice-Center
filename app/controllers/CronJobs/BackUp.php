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
       // $this->clean();
        //exit;
    }

    public function generate() {
        $this->ssh->exec("mysqldump -u root -pqwe123admin lms | zip > {$this->get_full_file_path()}");
        $this->clean();
    }

    private function list_files() {
        $files = $this->ssh->exec("ls {$this->get_full_folder_path()}");
        $files = str_replace("\r\n", "\n", $files);
        return explode("\n", $files);
    }

    private function clean() {
        if ($this->get_number_of_backups() >= 0) {            
            $cmd = "rm {$this->create_path_to_file($this->get_file_name_id($this->get_last_id_file()))}";
            echo $cmd;            
        }
       
    }

    private function get_last_id_file() {
        $files = $this->list_files();
        $min = 100000;
        foreach ($files as $file) {
            $f = explode("-", trim($file));
            $min = min($min, $f[0]);
        }
        return $min;
    }

    private function get_file_name_id($id) {
        $files = $this->list_files();
        foreach ($files as $file) {
            $f = explode("-", trim($file));
            if ($f[0] == $id)
                return $file;
        }
    }

    private function get_number_of_backups() {
        return count($this->list_files());
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

    private function create_path_to_file($file) {
        return $this->get_full_folder_path() . $file;
    }

}
