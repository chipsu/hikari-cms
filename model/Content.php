<?php

namespace hikari\cms\model;

class Content extends Model {

    static function attributesMap() {
        return array_merge(parent::attributesMap(), [
            'type' => ['String', 'maxlength' => 255, 'default' => static::tableName()],
            'description' => ['String', 'maxlength' => 65535],
            'tags' => ['String', 'type' => 'Array'],
            //'page' => ['Reference', 'to' => 'Page'],
            //'pages' => ['Reference', 'to' => 'Page'],
            'parent' => ['Reference', 'to' => 'Content'],
        ]);
    }
}
