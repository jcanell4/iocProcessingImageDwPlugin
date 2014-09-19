<?php

if(!defined('DOKU_INC')) die();

abstract class abstractGenerateHtml {
    private $changes;
    private $file;
    private $adminPlugin;

    protected function setFile($file) {
        $this->file=$file;
    }
    
    public function setChanges($changes) {
        $this->changes=$changes;
    }
    
    public function initChanges() {
        return array();
    }
    
    public function getHtml() {
        $fileString = io_readFile($this->file);
        foreach ($this->changes as $key => $value) {
            $fileString = preg_replace('/'.$key.'/',$value, $fileString);
        }
        return $fileString;
    }
    
    
}

?>
