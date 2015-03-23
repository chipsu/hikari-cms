<?php

namespace hikari\cms\model;

class Content extends Model {

    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'description' => ['String', 'maxlength' => 65535],
            'tags' => ['String', 'type' => 'Array'],
            //'page' => ['Reference', 'to' => 'Page'],
            //'pages' => ['Reference', 'to' => 'Page'],
            'parent' => ['Reference', 'to' => 'Content'],
            //
            'type' => ['String', 'maxlength' => 255],
            'layout' => ['String', 'maxlength' => 255],
        ]);
    }
}
