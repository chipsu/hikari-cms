<?php

namespace hikari\cms\controller;

trait CrudTrait {
    use ModelTrait;

    function create() {
        $class = static::modelClassName();
        $model = new $class;
        $model->save();
        var_dump($model);
    }

    function read() {
        $class = static::modelClassName();
        $model = $class::one($this->request->get('id'), ['hydrator' => true]);
        if(!$model) {
        	\hikari\exception\Http::raise(404);
        }
        var_dump($model);
        die;
        return ['model' => $model];
    }

    function update() {
        $class = static::modelClassName();
        $model = $class::one($this->request->get('id'), ['hydrator' => true]);
        $model->attributes($this->request->data);
        if($model->validate()) {
            $model->save();
        }
        var_dump($model);
    }

    function dispose() {
        $class = static::modelClassName();
        $model = $class::one($this->request->get('id'), ['hydrator' => true]);
        if(!$model) {
        	\hikari\exception\Http::raise(404);
        }
        $model->delete();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
