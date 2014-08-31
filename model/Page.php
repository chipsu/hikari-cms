<?php

namespace hikari\cms\model;

class Page extends Model {
    static function attributes() {
        return array_merge(parent::attributes(), [
        	'title' => 'String',
            'parent' => [],
        ]);
    }
}
