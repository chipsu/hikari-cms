<?php

namespace hikari\cms\model;

// TODO: ShadowTable (for drafts & other stuff)
trait HistoryTrait {
    
    static function historyTable() {
        $tableName = static::historyTableName();
        return static::db()->{$tableName};
    }

    static function historyTableName() {
        return static::tableName() . '_history';
    }

    function storeHistory(array $options) {
        if($data = static::one($this->id())) {
            $item = [
                'data' => $data,
            ];
            static::historyTable()->insert($item);
        }
        return true;
    }

    function beforeSave(array $options) {
        return parent::beforeSave($options) && $this->storeHistory($options);
    }

    function beforeDelete(array $options) {
        return parent::beforeDelete($options) && $this->storeHistory($options);
    }
}
