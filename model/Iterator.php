<?php

namespace hikari\cms\model;

use \hikari\core\Component;

class Iterator extends Component implements \Iterator {
    public $result;
    public $hydrator;
    public $options;
    private $_skip;
    private $_limit;
    private $_sort;

    public function getSkip() {
        if($this->_skip === null) {
            $this->_skip = 0;
        }
        return $this->_skip;
    }

    public function setSkip($value) {
        $this->result->skip($value);
        $this->_skip = $value;
    }

    public function getLimit() {
        if($this->_limit === null) {
            $this->_limit = false;
        }
        return $this->_limit;
    }

    public function setLimit($value) {
        $this->result->limit($value);
        $this->_limit = $value;
    }

    public function getSort() {
        if($this->_sort === null) {
            $this->_sort = false;
        }
        return $this->_sort;
    }

    public function setSort($value) {
        $this->result->sort($value);
        $this->_sort = $value;
    }

    function current() {
        $result = $this->result->current();
        if(!$result instanceof $this->hydrator) {
            $result = new $this->hydrator(['attributes' => $result]);
        }
        return $result;
    }

    function key() {
        return $this->result->key();
    }

    function next() {
        return $this->result->next();
    }

    function rewind() {
        return $this->result->rewind();
    }

    function valid() {
        return $this->result->valid();
    }
}
