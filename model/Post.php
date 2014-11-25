<?php

namespace hikari\cms\model;

class Post extends Content {
    use HistoryTrait {
        HistoryTrait::beforeSave as HistoryTrait_beforeSave;
    }

    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'class' => ['String', 'maxlength' => 255, 'default' => static::className()],
            'name' => ['String', 'minlength' => 1, 'maxlength' => 50],
            'title' => ['String', 'maxlength' => 100],
            'content' => ['Content', 'null' => true],
        ]);
    }

    static function tableName() {
        return str_replace('\\', '_', __CLASS__);
    }

    static function normalizeQuery($query) {
        $query = parent::normalizeQuery($query);
        if(get_called_class() != __CLASS__) {
            $query['class'] = get_called_class();
        }
        return $query;
    }

    // TODO: Alias?
    protected static function dynamicClass(array $attributes) {
        if(isset($attributes['class'])) {
            return $attributes['class'];
        }
        return parent::dynamicClass($attributes);
    }

    function beforeSave(array $options) {
        return $this->HistoryTrait_beforeSave($options);
    }
}
