<?php

namespace hikari\cms\model;

class Integer extends Attribute {

    function value() {
        return $this->value !== null ? (int)$this->value : null;
    }
}
