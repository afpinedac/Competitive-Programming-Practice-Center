<?php

class CronjobController extends BaseController {

    public function getBackUp() {

        $backup = new BackUp();
        $backup->generate();
    }

}
