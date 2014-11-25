<?php

namespace hikari\cms\model;

class Page extends Post {

    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'layout' => ['String'],
        ]);
    }
}
