<?php

namespace hikari\cms\model;

class Content extends Model {

    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'class' => ['String', 'maxlength' => 255, 'default' => static::className()],
            'description' => ['String', 'maxlength' => 65535],
            'tags' => ['String', 'type' => 'Array'],
            //'page' => ['Reference', 'to' => 'Page'],
            //'pages' => ['Reference', 'to' => 'Page'],
            'parent' => ['Reference', 'to' => 'Content'],
        ]);
    }

    // TODO: Alias?
    protected static function dynamicClass(array $attributes) {
        if(isset($attributes['class'])) {
            return $attributes['class'];
        }
        return parent::dynamicClass($attributes);
    }
}
