<?php

namespace hikari\cms\model;

class Iterator extends \hikari\component\Component implements \Iterator {
    public $result;
    public $hydrator;
    public $options;

    function current() {
        $result = $this->result->current();
        if(!$result instanceof $this->hydrator) {
            $result = new $this->hydrator(['attributes' => $result]);
        }
        return $result;
    }
    
    function key() {
        return $this->result->key();
    }
    
    function next() {
        return $this->result->next();
    }

    function rewind() {
        return $this->result->rewind();
    }

    function valid() {
        return $this->result->valid();
    }
}
