<?php

namespace hikari\cms\model;

class Order extends Content {

    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'total' => ['Integer', 'min' => 0],
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
