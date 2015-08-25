<?php

namespace hikari\cms\model;

class Date extends Attribute {

    function serialize(array $options) {
        $value = $this->value();
        if($value instanceof \DateTime) {
            $date = $value->getTimestamp();
        } else if(is_numeric($value)) {
            $date = $value;
        } else if(is_string($value)) {
            $date = strtotime($value);
        } else {
            //\hikari\exception\Argument::raise('Unsupported date type %s', gettype($this->value));
            return null;
        }
        return new \MongoDate($date);
    }
}
