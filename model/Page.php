<?php

namespace hikari\cms\model;

class Page extends Content {

    static function attributesMap() {
        return array_merge(parent::attributesMap(), [
            'layout' => ['String'],
        ]);
    }
}
