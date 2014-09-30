<?php

namespace hikari\cms\controller;

trait ModelTrait {
    function modelClassName() {
        return str_replace('\\controller\\', '\\model\\', get_called_class());
    }
}
