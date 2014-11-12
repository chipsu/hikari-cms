<?php

namespace hikari\cms\model;

class Page extends Content {

    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'layout' => ['String'],
        ]);
    }
}
