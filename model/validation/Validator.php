<?php

namespace hikari\cms\model\validation;

use hikari\exception\Argument as ArgumentException;
use hikari\cms\model\ModelInterface;

class Validator extends \hikari\core\Component {
    public $model;
    public $labels;
    public $errors;

    function run() {
        $this->errors = [];
        $this->labels = $this->model->labels();
        $map = $this->model->attributeMap();
        $attributes = $this->model->attributes();
        foreach($map as $attribute => $data) {
            $value = isset($attributes[$attribute]) ? $attributes[$attribute] : null;
            if($value instanceof ModelInterface) {
                if(!$value->validate()) {
                    $this->errors[$attribute] = $value->errors;
                }
                continue;
            }
            if(!is_array($data)) {
                continue;
            }
            $class = $data[0];
            foreach($data as $name => $args) {
                $method = 'validate' . ucfirst($name);
                if(method_exists($this, $method)) {
                    $args = $this->normalizeArgs($args);
                    $this->$method($class, $attribute, $value, $name, $args);
                }
            }
        }

        return $this->errors;
    }

    function error($class, $attribute, $value, $name, $args, $message) {
        if(isset($args['message'])) {
            $message = $args['message'];
        }
        $label = isset($this->labels[$attribute]) ? $this->labels[$attribute] : ucfirst($attribute);
        $this->errors[$attribute][$name] = str_replace([':label', ':require'], [$label, $args['require']], $message);
    }

    function normalizeArgs($args) {
        if(!is_array($args)) {
            $args = ['require' => $args];
        }
        return $args;
    }

    function validateMin($class, $attribute, $value, $name, $args) {
        if(!is_numeric($args['require'])) {
            ArgumentException::raise('%s require must be numeric', $name);
        }
        if($value < $args['require']) {
            $this->error($class, $attribute, $value, $name, $args, ':label must be greater or equal to :require');
        }
    }

    function validateMax($class, $attribute, $value, $name, $args) {
        if(!is_numeric($args['require'])) {
            ArgumentException::raise('%s require must be numeric', $name);
        }
        if($value > $args['require']) {
            $this->error($class, $attribute, $value, $name, $args, ':label must be less or equal to :require');
        }
    }

    function validateMinlength($class, $attribute, $value, $name, $args) {
        if(!is_numeric($args['require'])) {
            ArgumentException::raise('%s require must be numeric', $name);
        }
        if(strlen($value) < $args['require']) {
            $this->error($class, $attribute, $value, $name, $args, ':label must contain at least :require characters');
        }
    }

    function validateMaxlength($class, $attribute, $value, $name, $args) {
        if(!is_numeric($args['require'])) {
            ArgumentException::raise('%s require must be numeric', $name);
        }
        if(strlen($value) > $args['require']) {
            $this->error($class, $attribute, $value, $name, $args, ':label cannot contain more than :require characters');
        }
    }
}
