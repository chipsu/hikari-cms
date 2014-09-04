<?php

namespace hikari\cms\model;

class Content extends Model {

    static function attributesMap() {
        return array_merge(parent::attributesMap(), [
            'description' => ['String', 'maxlength' => 65536],
            'tags' => ['String', 'type' => 'Array'],
            'page' => ['Reference', 'to' => 'Page'],
            'pages' => ['Reference', 'to' => 'Page'],
            'parent' => ['Reference', 'to' => 'Content'],
        ]);
    }
}
