<?php

namespace hikari\cms\model;

class Attribute {
    public $value;
    public $options;

    function __construct($value = null, array $options = []) {
        $this->value = $value;
        $this->options = $options;
    }

    function serialize() {
        return $this->value;
    }

    function __toString() {
        return (string)$this->value;
    }
}

class Id extends Attribute {


    function serialize() {
        if($this->value === null && !empty($this->options['null'])) {
            return null;
        }
        return $this->value ? new \MongoId($this->value) : new \MongoId;
    }
}

class Date extends Attribute {

    function serialize() {
        if($this->value instanceof \DateTime) {
            $date = $this->value->getTimestamp();
        } else if(is_numeric($this->value)) {
            $date = $this->value;
        } else if(is_string($this->value)) {
            $date = strtotime($this->value);
        } else {
            //\hikari\exception\Argument::raise('Unsupported date type %s', gettype($this->value));
            return null;
        }
        return new \MongoDate($date);
    }
}

class String extends Attribute {

}

class Integer extends Attribute {
}

// TODO: array
class Reference extends Id {
    public $reference;

    function serialize() {
        if($this->reference) {
            $id = $this->reference->id();
            if($id instanceof Id) {
                $this->value = $id->value;
            }
        }
        return $this->value ? new \MongoId($this->value) : null;
    }

    function fetch() {
        if($this->reference === null) {
            if($id = $this->serialize()) {
                $class = $this->options['model'];
                $this->reference = $class::one($id);
            } else {
                $this->reference = null;
            }
        }
        return $this->reference ? $this->reference : null;
    }

    function __get($key) {
        if($reference = $this->fetch()) {
            return $reference->$key;
        }
        return null;
    }

    function __set($key, $value) {
        if($reference = $this->fetch()) {
            $reference->$key = $value;
        }
    }

    function __call($method, array $args) {
        if($reference = $this->fetch()) {
            return call_user_func_array(array($reference, $method), $args);
        }
        return null;
    }
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
    static $db;
    static $client;

    static function client() {
        if(static::$client === null) {
            static::$client = new \MongoClient();
        }
        return static::$client;
    }

    static function db() {
        if(static::$db === null) {
            static::$db = static::client()->cms2;
        }
        return static::$db;
    }

    static function table() {
        $tableName = static::tableName();
        return static::db()->{$tableName};
    }

    static function tableName() {
        return str_replace('\\', '_', get_called_class());
    }

    static function one($query, array $options = []) {
        $query = static::query($query);
        $query = static::serializeAttributes($query);
        $result = static::table()->findOne($query);
        if($result && !empty($options['hydrator'])) {
            $result = new static(['attributes' => $result]);
        }
        return $result;
    }

    static function find($query, array $options = []) {
        $query = static::query($query);
        $query = static::serializeAttributes($query);
        $result = static::table()->find($query);
        if(!empty($options['hydrator'])) {
            return new HydratorIterator([
                'result' => $result,
                'hydrator' => get_called_class(),
                'options' => $options,
            ]);
        }
       return $result;
    }

    static function query($query) {
        if(!is_array($query)) {
            $query = ['_id' => $query];
        }
        return static::createAttributes($query, false);
    }

    static function create(array $attributes = [], array $options = []) {
        $class = get_called_class();
        return new $class(array_merge($options, ['attributes' => $attributes]));
    }

    static function filter() {
        // TODO: scopes?
    }

    static function attributesMap() {
        return [
            '_id' => ['Id'],
        ];
    }

    protected static function createAttributes(array $attributes = [], $empty = true) {
        foreach(static::attributesMap() as $key => $options) {
            if(!array_key_exists($key, $attributes)) {
                if(!$empty) {
                    continue;
                }
                $attributes[$key] = null;
            }
            $attributes[$key] = static::createAttribute($key, $attributes[$key], $options);
        }
        return $attributes;
    }

    protected static function createAttribute($key, $value, $options = []) {
        if(!is_array($options)) {
            $options = [$options];
        }
        $class = $options[0];
        if($class[0] != '\\') {
            $class = __NAMESPACE__ . '\\' . $class;
        }
        if(!$value instanceof Attribute) {
            $value = new $class($value, $options);
        }
        $value->options['model'] = get_called_class();
        $value->options['class'] = $class;
        return $value;
    }

    protected static function serializeAttributes(array $attributes) {
        $result = [];
        foreach($attributes as $key => $value) {
            $result[$key] = $value instanceof Attribute ? $value->serialize() : $value;
        }
        return $result;
    }

    function __construct(array $properties = []) {
        parent::__construct($properties);
        $this->initialize();
    }

    function initialize() {
        $this->attributes = static::createAttributes($this->attributes === null ? [] : $this->attributes);
        parent::initialize();
    }

    function id() {
        return $this->get('_id');
    }

    function get($key, $default = null) {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : $default;
    }

    function set($key, $value) {
        if(!$value instanceof Attribute) {
            $map = static::attributesMap();
            if(!isset($map[$key])) {
                \hikari\exception\Argument::raise('The property %s does not exist on this object', $key);
            }
            $value = static::createAttribute($key, $value, $map[$key]);
        }
        $this->attributes[$key] = $value;
    }

    function save(array $options = []) {
        $noevents = !empty($options['noevents']);
        if($noevents || $this->beforeSave()) {
            $attributes = static::serializeAttributes($this->attributes);

            static::table()->insert($attributes);

            if(!$noevents) {
                $this->afterSave();
            }
        }
    }

    function delete() {
        static::table()->remove([
            '_id' => $this->id()->serialize(),
        ]);
    }

    function beforeSave() {
        return true;
    }

    function afterSave() {
    }

    function __set($key, $value) {
        $this->set($key, $value);
    }

    function __get($key) {
        if(array_key_exists($key, $this->attributes)) {
            return $this->get($key);
        }
        \hikari\exception\Argument::raise('The property %s does not exist on this object', $key);
    }

    function __unset($key) {
        $this->set($key, null);
    }
}

class Model extends ModelBase {

    /// move to core model return $result;


    /// end


    static function attributesMap() {
        return array_merge(parent::attributesMap(), [
            'created' => 'Date',
            'updated' => 'Date',
        ]);
    }

    function beforeSave() {
        $now = new Date('NOW');
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