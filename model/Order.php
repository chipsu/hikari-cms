<?php

namespace hikari\cms\model;

class Order extends Post {

    static function attributesMap() {
        return array_merge(parent::attributesMap(), [
            'total' => ['Integer', 'minValue' => 0],
            'rows' => ['OrderRow', 'array' => true],
        ]);
    }

    function total() {
        return $this->get('total');
    }

    function updateTotal() {
        $total = 0;
        foreach($this->get('rows') as $row) {
            $total += $row->updateTotal()->total();
        }
        $this->set('total', $total);
        return $this;
    }
}
