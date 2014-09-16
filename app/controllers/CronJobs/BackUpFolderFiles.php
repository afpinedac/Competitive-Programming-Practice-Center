<?php

class BackUpFolderFiles extends BackUp {

    protected $source_folder = '/_data_/';
    protected $destination_folder = '/backups/files/';

    function __construct() {
        parent::__construct('.tar.gz', '-cpp-bk-', 4);
        $public_path = public_path();
        $this->source_folder = $public_path . $this->source_folder;
        $this->destination_folder = $public_path . $this->destination_folder;
        $this->file_name = $this->create_file_name($this->destination_folder);
        echo "file generated : {$this->destination_folder}{$this->file_name}";
    }

    public function generate() {
        CronJob::exec("cp -R {$this->source_folder} {$this->destination_folder}");
        CronJob::exec("tar -czf {$this->destination_folder}{$this->file_name} $this->destination_folder/_data_/");
        CronJob::exec("rm -R {$this->destination_folder}_data_/");
    }

    public function clean() {
        if (static::get_number_of_backups($this->destination_folder) >= $this->nbackups) {
            $cmd = "rm {$this->destination_folder}" . static::get_file_name_by_id(static::get_last_id_file('min', $this->destination_folder), $this->destination_folder);
            CronJob::exec($cmd);
        }
    }

}
