<?php

namespace hikari\cms\model;

class Post extends Model {
    use HistoryTrait {
        HistoryTrait::beforeSave as HistoryTrait_beforeSave;
    }

    static function attributesMap() {
        return array_merge(parent::attributesMap(), [
            'name' => ['String', 'maxlength' => 255],
            'title' => ['String', 'maxlength' => 255],
            'content' => ['Content', 'null' => true],
        ]);
    }

    /*static function tableName() {
        var_dump(str_replace('\\', '_', __CLASS__));die;
        return str_replace('\\', '_', __CLASS__);
    }

    static function normalizeQuery($query) {
        $query = parent::normalizeQuery($query);
        if(get_called_class() != __CLASS__) {
            $query['content']['class'] = get_called_class();
        }
        return $query;
    }

    static function postType() {
        return get_called_class();
    }*/

    function beforeSave(array $options) {
        return $this->HistoryTrait_beforeSave($options);
    }
}
