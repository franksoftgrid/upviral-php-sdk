<?php

namespace Upviral\Entity;

class Campaign {
    
    private $id;
    public $name;
    public $status;

    function __construct($record = false) {
        if($record) {
            $this->id = $record->id;
            $this->name = $record->name;
            $this->status = @$record->status;
        }
    }

    function getID() {
        return $this->id;
    }
}