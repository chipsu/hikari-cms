<?php

namespace hikari\cms\model;

class Attribute implements AttributeInterface {
    public $value;
    public $options;

    function __construct($value = null, array $options = []) {
        $this->value = $value;
        $this->options = $options;
    }

    function value() {
        return $this->value;
    }

    function serialize(array $options) {
        return $this->value();
    }

    function option($key, $default = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    function __toString() {
        try {
            return (string)$this->value(['stringify' => true]);
        } catch(\Exception $ex) {
            var_dump($ex);
            die;
        }
    }
}
