<?php

namespace Upviral\Entity;

class CustomField {
    
    public $name;

    function __construct($record = false) {
        if($record) {
            $this->name = $record;
        }
    }
}