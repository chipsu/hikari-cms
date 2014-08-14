<?php

namespace hikari\cms\model;

class Model extends \hikari\component\Component {

    static function find($criteria, array $options = []) {
        $result = null;
        $result = ['dummy' => 'result'];
        if(!empty($options['hydrator'])) {
            $result = new static($result);
        }
        return $result;
    }

    static function findAll($criteria, array $options = []) {
        $result = [];
        $result[] = ['dummy' => 'result'];
        if(!empty($options['hydrator'])) {
            return new HydratorIterator([
                'result' => $result,
                'hydrator' => get_called_class(),
                'options' => $options,
            ]);
        }
        return $result;
    }


}

class HydratorIterator extends \hikari\component\Component implements \Iterator {
    public $result;
    public $hydrator;
    public $options;

    function current() {
        $result = current($this->result);
        if(!$result instanceof $this->hydrator) {
            $result = new $this->hydrator($result);
            $this->result[$this->key()] = $result;
        }
        return $result;
    }
    
    function key() {
        return key($this->result);
    }
    
    function next() {
        next($this->result);
    }

    function rewind() {
        rewind($this->result);
    }

    function valid() {
        return is_array($this->result);
    }
}

/*
User
Group
Product

Data:
    Created
    Updated

MetaData(Data):
    ...

Price(MetaData):
    currency: SEK
    value: 34543

Content(Data):
    String description
    Node node
    Node[] otherNodes

Product(Content):
    String articleNumber: "1324-3"
    Price[] prices

Node
    name
    uuid
    parent
    layout
    Content[] content:
        Product product

    # OR
    Content content
    Node[] nodes # 
#####

Page : Content container, contains zero or more Posts, has child Pages
Content : Post (Text Content), Product, User, Group

*/