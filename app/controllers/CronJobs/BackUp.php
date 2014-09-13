<?php

require '/public/libs/phpseclib/Net/SSH2.php';

class BackUp {

    protected $folder = "/backups/bd/";
    protected $prefix = "-cpp-bk-";
    protected $nbackups = 10;
    protected $extension = '.sql.zip';
    protected $ssh;

    function __construct() {
        echo "xd";
        exit;
        $this->ssh = new Net_SSH2('localhost');
        if (!$this->ssh->login('root', 'qwe123admin')) {
            exit('Login Failed in ssh');
        }
    }

    public function generate() {
        $this->ssh->exec("mysqldump -u root -pqwe123admin lms | zip > {$this->get_full_file_path()}");
        $this->clean();
    }

    private function list_files() {
        $files = $this->ssh->exec("ls {$this->get_full_folder_path()}");
        $files = str_replace("\r\n", "\n", trim($files));
        return explode("\n", $files);
    }

    private function clean() {
        if ($this->get_number_of_backups() >= $this->nbackups) {
            $cmd = "rm {$this->create_path_to_file($this->get_file_name_id($this->get_last_id_file('min')))}";
            $this->ssh->exec($cmd);
        }
    }

    private function get_last_id_file($type) {
        $files = $this->list_files();
        $value = $type == 'min' ? 50000 : 0;
        foreach ($files as $file) {
            echo "$file <br>";
            $f = explode("-", trim($file));
            $value = ($type == 'min') ? min($value, (int) $f[0]) : max($value, (int) $f[0]);
        }

        return $value;
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
        $id = $this->get_last_id_file('max') + 1; 
        return $id . $this->prefix . date('Y-m-d') . $this->extension;
    }

    private function create_path_to_file($file) {
        return $this->get_full_folder_path() . $file;
    }

}
