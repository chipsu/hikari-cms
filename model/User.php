<?php

namespace hikari\cms\model;

class User extends Content {
    function attributesMap() {
        return [
            'email',
        ];
    }
}
