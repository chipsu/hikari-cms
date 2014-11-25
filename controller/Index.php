<?php

namespace hikari\cms\controller;

class Index extends Post {

    static function modelClassName() {
        return Page::modelClassName();
    }

    protected function beforeRender() {
        // temp fix: we need paths for views
        #if($this->action->id == 'index') {
        #    $this->viewFile = 'post/' . $this->action->id;
        #}
        return parent::beforeRender();
    }
}
