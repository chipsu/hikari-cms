<?php

namespace hikari\cms\controller;

trait CrudTrait {
    use ModelTrait;

    // if numeric array: batch create
    function create() {
        $class = static::modelClassName();
        $model = new $class;
        $model->save();
        var_dump($model);
    }

    function read() {
        $class = static::modelClassName();
        $query = $this->requestQuery();
        if(!empty($query['_id'])) {
            $result = $class::one($query, ['hydrator' => true]);
            if(!$result) {
            	\hikari\exception\Http::raise(404);
            }
        } else {
            $result = $class::find($query, ['hydrator' => true]);
        }
        return ['title' => 'read', 'result' => $result];
    }

    // if numeric array: batch update
    function update() {
        $class = static::modelClassName();
        $model = $class::one($this->request->get('id'), ['hydrator' => true]);
        $model->attributes($this->request->data);
        if($model->validate()) {
            $model->save();
        }
        var_dump($model);
    }

    // if numeric array: batch delete
    function dispose() {
        $class = static::modelClassName();
        $model = $class::one($this->request->get('id'), ['hydrator' => true]);
        if(!$model) {
        	\hikari\exception\Http::raise(404);
        }
        $model->delete();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    protected function requestQuery() {
        $query = array(
            '_id' => $this->request->get('id'),
            //'data.type' => $this->request->get('type'),
        );
        return array_filter($query, function($item) { return $item !== null; });
    }
}
