<?php

namespace hikari\cms\model;

// TODO: array
class Reference extends Id {
    public $reference;

    function serialize() {
        if($this->reference) {
            $id = $this->reference->getId();
            if($id instanceof Id) {
                $this->value = $id->value;
            }
        }
        return $this->value ? new \MongoId($this->value) : null;
    }

    function fetch() {
        if($this->reference === null) {
            if($id = $this->serialize()) {
                $class = $this->options['model'];
                $this->reference = $class::one($id);
            } else {
                $this->reference = null;
            }
        }
        return $this->reference ? $this->reference : null;
    }

    function __get($key) {
        if($reference = $this->fetch()) {
            return $reference->$key;
        }
        return null;
    }

    function __set($key, $value) {
        if($reference = $this->fetch()) {
            $reference->$key = $value;
        }
    }

    function __call($method, array $args) {
        if($reference = $this->fetch()) {
            return call_user_func_array(array($reference, $method), $args);
        }
        return null;
    }
}
