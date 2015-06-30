<?php

namespace hikari\cms\controller;

class Index extends Post {

    static function modelClassName() {
        return Page::modelClassName();
    }

    public function beforeRender($event) {
        // temp fix: we need paths for views
        #if($this->action->id == 'index') {
        #    $this->viewFile = 'post/' . $this->action->id;
        #}
        return parent::beforeRender($event);
    }
}
