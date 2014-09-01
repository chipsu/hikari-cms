<?php

namespace hikari\cms\model;

class Content extends Model {

    static function attributes() {
        return array_merge(parent::attributes(), [
            'description' => ['String', 'maxlength' => 65536],
            'tags' => ['String', 'type' => 'Array'],
            'page_id' => ['Id'],
            'page' => ['Reference', 'from' => 'page_id', 'to' => 'Page'],
            'page_ids' => ['Id', 'type' => 'Array'],
            'pages' => ['Reference', 'from' => 'page_ids', 'to' => 'Page'],
            'parent_id' => ['Id'],
            'parent' => ['Reference', 'from' => 'parent_id', 'to' => 'Content'],
        ]);
    }
}
