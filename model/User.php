<?php

namespace hikari\cms\model;

class User extends Content {
    function attributes() {
        return [
            'email',
        ];
    }
}
