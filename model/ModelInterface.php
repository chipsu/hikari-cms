<?php

namespace hikari\cms\model;

interface ModelInterface {
    static function client();
    static function db();
    static function table();
    static function tableName();
    static function one($query = [], array $options = []);
    static function find($query = [], array $options = []);
    static function query($query, $createAttributes = true);
    //static function normalizeQuery($query);
    static function create(array $attributes = [], array $options = []);
    //static function filter();
    static function attributeMap();
    static function className();
    function getId();
    function exists();
    function attributes();
    function labels();
    function has($key);
    function get($key, $default = null);
    function set($key, $value);
    function save(array $options = []);
    function delete(array $options = []);
    function validate(array $options = []);
}
