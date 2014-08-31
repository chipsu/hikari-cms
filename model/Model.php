<?php

namespace hikari\cms\model;

class Attribute {
    public $length;
}

class Id extends Attribute {

    function to() {

    }
}

class Date extends Attribute {

}

class String {

}

class Integer {

}
/*
 $post->created = 'NOW';
 ---
 model::get($key)
    if not $this->attributes[$key] instanceof Attribute
        $this->attributes[$key] = new static::$attributes[$key]($value)

 model::save()
    $data = []
    foreach $this->attributes
        if changed
            $data[$key] = $attr->serialize() // to mongo.. or $db->serialize($attr)
    $db->save($data)


 foreach(Page::all() as $page) {
    foreach($page->contents as $content) {
      echo $content->text;
    }
 }

*/

class ModelBase extends \hikari\component\Component {
    public $attributes;

    static function one($criteria, array $options = []) {
        $result = null;
        $result = ['dummy' => 'result'];
        if(!empty($options['hydrator'])) {
            $result = new static($result);
        }
        return $result;
    }

    static function all($criteria, array $options = []) {
        $result = [];
        $result[] = ['dummy' => 'result'];
        if(!empty($options['hydrator'])) {
            return new HydratorIterator([
                'result' => $result,
                'hydrator' => get_called_class(),
                'options' => $options,
            ]);
        }
       
    }

    static function attributes() {
        return [];
    }

    function __construct(array $properties = []) {
        parent::__construct();
        $this->initialize();
    }

    function initialize() {
        $this->attributes = static::attributes();
        foreach($this->attributes as $key => $value) {
            $this->attributes[$key] = null;
        }
        parent::initialize();
    }

    function id() {
        return $this->get('id');
    }

    function get($key, $default = null) {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : $default;
    }

    function set($key, $value) {
        $this->attributes[$key] = $value;
    }

    function save(array $options = []) {
        $noevents = !empty($options['noevents']);
        if($noevents || $this->beforeSave()) {

            if(!$noevents) {
                $this->afterSave();
            }
        }
    }

    function beforeSave() {
        return true;
    }

    function afterSave() {
    }

    function __set($key, $value) {
        $this->attributes[$key] = $value;
    }

    function __get($key) {
        if(array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        \hikari\exception\Argument::raise('The property %s does not exist on this object', $key);
    }

    function __unset($key) {
        unset($this->attributes[$key]);
    }
}

class Model extends ModelBase {

    /// move to core model return $result;


    /// end


    static function attributes() {
        return array_merge(parent::attributes(), [
            'created' => 'Date',
            'updated' => 'Date',
        ]);
    }

    function beforeSave() {
        $now = new Date;
        if(!$this->created) {
            $this->created = $now;
        }
        $this->updated = $now;
        return parent::beforeSave();
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
    UserData

MetaData(Data):
    ...

Price(MetaData):
    currency: SEK
    value: 34543
    pricelist: ID (category or tag on Data?)

Content(Data):
    String description
    Page page (??? primary)
    Page[] pages (secondary)
    Tag[] tags (for filtering and organisation)
    parent? (product groups, packages ...)

Product(Content):
    String articleNumber: "1324-3"
    Price[] prices

Post(Content):
    ...

Page
    name
    uuid
    PageID parent
    PageID[] lineage
    layout
    Content[] content: (uuid?)
        Product product
#####

Page : Content container, contains zero or more Posts, has child Pages
Content : Post (Text Content), Product, User, Group

*/