<?php

namespace hikari\cms\model;

class User extends Content {
    function attributeMap() {
        return [
            'email',
        ];
    }
}
