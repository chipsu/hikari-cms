<?php

namespace hikari\cms\model;

class OrderRow extends Model {

    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'total' => ['Integer'],
            'quantity' => ['Integer', 'minValue' => 0],
            'price' => ['Price'],
            'product' => ['Product'],
    }

    function total() {
        return $this->get('total');
    }

    function updateTotal() {
        $product = $this->get('product');
        $price = $product->get('price');
        $total = $price->value() * $this->get('quantity');
        $this->set('price', $price);
        $this->set('total', $total);
        return $this;
    }
}
