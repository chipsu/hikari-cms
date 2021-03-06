<?php

namespace hikari\cms\model;

class Model extends ModelBase {

    /// move to core model return $result;


    /// end


    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'created' => 'Date',
            'updated' => 'Date',
        ]);
    }

    function beforeSave(array $options) {
        $now = new Date('NOW');
        if(!$this->created) {
            $this->created = $now;
        }
        $this->updated = $now;
        return parent::beforeSave($options);
    }

}
