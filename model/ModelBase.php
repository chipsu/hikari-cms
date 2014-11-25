<?php

namespace hikari\cms\model;

class ModelBase extends \hikari\component\Component implements ModelInterface, AttributeInterface {
    public $attributes;
    public $errors;
    public $exists;
    static $db;
    static $client;

    ///
    function value() {
        return $this->attributes;
    }

    function serialize() {
        return static::serializeAttributes($this->attributes);
    }

    function __toString() {
        return get_class($this);
    }

    ///

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

    static function one($query = [], array $options = []) {
        $query = static::query($query);
        $query = static::serializeAttributes($query);
        $result = static::table()->findOne($query);
        if($result && !empty($options['hydrator'])) {
            $result = new static(['attributes' => $result]);
        }
        return $result;
    }

    static function find($query = [], array $options = []) {
        $query = static::query($query);
        $query = static::serializeAttributes($query);
        $result = static::table()->find($query);
        if(!empty($options['hydrator'])) {
            return new Iterator([
                'result' => $result,
                'hydrator' => get_called_class(),
                'options' => $options,
            ]);
        }
       return $result;
    }

    static function query($query, $createAttributes = true) {
        $query = static::normalizeQuery($query);
        return $createAttributes ? static::createAttributes($query, false) : $query;
    }

    static function normalizeQuery($query) {
        if(!is_array($query)) {
            $query = ['_id' => $query];
        }
        return $query;
    }

    static function create(array $attributes = [], array $options = []) {
        $class = static::dynamicClass($attributes);
        return new $class(array_merge($options, ['attributes' => $attributes]));
    }

    static function filter() {
        // TODO: scopes?
    }

    static function attributeMap() {
        return [
            '_id' => ['Id', 'pack' => true],
        ];
    }

    protected static function createAttributes(array $attributes = [], $empty = true) {
        foreach(static::attributeMap() as $key => $options) {
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
        if(!$value instanceof AttributeInterface) {
            if($value === null) {
                if(!empty($options['null'])) {
                    return null;
                }
                if(!empty($options['default'])) {
                    $value = $options['default'];
                }
            }
            if(method_exists($class, 'dynamicClass')) {
                $class = $class::dynamicClass($value);
            }
            $value = new $class($value, $options);
        }
        // FIXME!
        if($value instanceof Attribute) {
            $value->options['model'] = get_called_class();
            $value->options['class'] = $class;
        }
        return $value;
    }

    static function className() {
        return get_called_class();
    }

    protected static function dynamicClass(array $attributes) {
        return get_called_class();
    }

    protected static function serializeAttributes(array $attributes) {
        $result = [];
        foreach($attributes as $key => $value) {
            $result[$key] = $value instanceof AttributeInterface ? $value->serialize() : $value;
        }
        return $result;
    }

    function __construct(array $properties = []) {
        parent::__construct($properties);
        $this->initialize();
    }

    function initialize() {
        $this->exists = isset($this->attributes['_id']);
        $this->attributes = static::createAttributes($this->attributes === null ? [] : $this->attributes);
        parent::initialize();
    }

    function id() {
        return $this->get('_id');
    }

    function exists() {
        return $this->exists;
    }

    function packId() {
        $id = $this->id();
        if($id->options('pack')) {
            return $id;
        }
        return Id::pack($this->id());
    }

    function encryptId() {
        return base64_encode(EncryptedId::encrypt($this->id()));
    }

    function attributes() {
        return $this->attributes;
    }

    function labels() {
        return [];
    }

    function has($key) {
        return is_array($this->attributes) && array_key_exists($key, $this->attributes);
    }

    function get($key, $default = null) {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : $default;
    }

    function set($key, $value) {
        if(!$value instanceof AttributeInterface) {
            $map = static::attributeMap();
            if(!isset($map[$key])) {
                \hikari\exception\Argument::raise('The property %s does not exist on this object (%s)', $key, get_class($this));
            }
            $value = static::createAttribute($key, $value, $map[$key]);
        }
        $this->attributes[$key] = $value;
    }

    function save(array $options = []) {
        $noevents = !empty($options['noevents']);
        if($noevents || $this->beforeSave($options)) {
            $attributes = $this->serialize();

            static::table()->save($attributes);

            $this->exists = true;

            if(!$noevents) {
                $this->afterSave($options, $attributes);
            }
        }
    }

    function delete(array $options = []) {
        $noevents = !empty($options['noevents']);
        if($noevents || $this->beforeDelete($options)) {
            static::table()->remove([
                '_id' => $this->id()->serialize(),
            ]);
            $this->afterDelete($options);
        }
    }

    function validate(array $options = []) {
        $noevents = !empty($options['noevents']);
        $this->errors = [];
        if($noevents || $this->beforeValidate($options)) {
            $validator = new validation\Validator(['model' => $this]);
            $this->errors = $validator->run();
            $this->afterValidate($options);
        }
        return empty($this->errors);
    }

    protected function beforeSave(array $options) {
        return true;
    }

    protected function afterSave(array $options, array $attributes) {
    }

    protected function beforeDelete(array $options) {
        return true;
    }

    protected function afterDelete(array $options) {
    }

    protected function beforeValidate(array $options) {
        return true;
    }

    protected function afterValidate(array $options) {
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
