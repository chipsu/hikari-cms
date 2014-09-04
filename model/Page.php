<?php

namespace hikari\cms\model;

class Page extends Model {
    static function attributesMap() {
        return array_merge(parent::attributesMap(), [
        	'title' => 'String',
            'parent' => ['Reference'],
        ]);
    }
}
 