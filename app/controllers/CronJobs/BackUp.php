<?php

class BackUp {

    protected $path = "/backups/bd/";
    protected $prefix = "-cpp-bk-";
    protected $nbackups = 7;
    protected $extension = '.sql.zip';
    protected $so = 'windows'; #1 -> windows , 0 -> linux
    protected $id;
    protected $commands = array(
        'linux' => array(
            'create-backup' => "mysqldump -u root -pqwe123admin lms | zip > /var/www/cpp2/bk/lms_$(date '+%Y-%m-%d_%H-%M-%S').sql.zip;"
        ),
        'windows' => array(
            'create-backup' => '...'
        )
    );

    function __construct($id) {
        $this->id = $id;
    }

    public function test2() {

        require '../public/libs/phpseclib/Net/SSH2.php';


        $ssh = new Net_SSH2('localhost');
        if (!$ssh->login('root', 'qwe123admin')) {
            exit('Login Failed');
        }
        
        $data = $ssh->exec("mysqldump -u root -pqwe123admin lms | zip > {$this->get_full_path()}");
        
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        
        
    }

    public function test() {
        $this->test2();
        exit;


        echo "mysqldump -u root -pqwe123admin lms | zip > {$this->get_full_path()}";

        if ($this->id == 1) {
            echo "se ejecuto 1 <br>";
            $var = shell_exec("mysqldump -u root -pqwe123admin lms | zip > {$this->get_full_path()}");

            echo "<pre>";
            var_dump($var);
            echo "</pre>";
        } else {
            echo "se ejecuto 2 <br>";
            ##problema ejecutando mysqldump con shell_exec
            $var = shell_exec("sudo mysqldump -u root -pqwe123admin lms | zip > {$this->get_full_path()}");

            echo "<pre>";
            var_dump($var);
            echo "</pre>";
        }
    }

    public function generate() {
        $filename = $this->get_file_name();
        $this->create_backup($filename);
    }

    private function create_backup($filename) {
        shell_exec($this->get_command('create-backup'));
    }

    private function list_files() {
        
    }

    private function remove($file) {
        
    }

    private function get_last_id_file() {
        return $this->id;
    }

    private function get_number_of_backups() {
        
    }

    private function save() {
        
    }

    private function get_full_path() {
        return public_path() . $this->path . $this->get_file_name();
        //   return  $this->get_file_name();
    }

    private function get_file_name() {
        $id = $this->get_last_id_file() + 1;
        return $id . $this->prefix . date('Y-m-d') . $this->extension;
    }

    private function get_command($command) {
        return $this->commands[$this->so][$command];
    }

}
