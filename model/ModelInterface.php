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
    static function attributesMap();
    static function className();
}
