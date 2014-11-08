<?php

namespace hikari\cms\controller;

use \hikari\core\Server;

trait CrudTrait {
    use ModelTrait;

    // if numeric array: batch create
    function create() {
        $class = $this->modelClassName();
        $model = $class::create($this->request->post('data'));
        $model->save();
        header('Location: ' . Server::referer());
    }

    function read() {
        $class = $this->modelClassName();
        $query = $this->requestQuery();
        #if(!empty($query['_id'])) {
        #    $result = $class::one($query, ['hydrator' => true]);
        #    if(!$result) {
        #        \hikari\exception\Http::raise(404);
        #    }
        #} else {
        #    $result = $class::find($query, ['hydrator' => true]);
        #}
        #    $result = $class::one($query, ['hydrator' => true]);
        $result = $class::find($query, ['hydrator' => true]);
        if(!$result && !empty($query['_id'])) {
            \hikari\exception\Http::raise(404);
        }
        return ['title' => 'read', 'result' => $result];
    }

    // if numeric array: batch update
    function update() {
        $class = $this->modelClassName();
        $model = $class::one($this->request->get('id'), ['hydrator' => true]);
        $model->attributes($this->request->data);
        if($model->validate()) {
            $model->save();
        }
        var_dump($model);
    }

    // if numeric array: batch delete
    function dispose() {
        $class = $this->modelClassName();
        $model = $class::one($this->request->get('id'), ['hydrator' => true]);
        if(!$model) {
        	\hikari\exception\Http::raise(404);
        }
        $model->delete();
        header('Location: ' . Server::referer());
    }

    protected function requestQuery() {
        $query = array(
            '_id' => $this->request->get('id'),
            //'data.type' => $this->request->get('type'),
        );
        return array_filter($query, function($item) { return $item !== null; });
    }
}
