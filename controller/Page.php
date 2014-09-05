<?php

namespace hikari\cms\controller;

use hikari\controller\Controller;

class Page extends Controller {

    function read($id) {
        $page = \hikari\cms\model\Page::one([
            '_id' => $id,
        ], ['hydrator' => true]);
        if(!$page) {
            die('404: ' . $id);
        }
        var_dump($page->attributes);
    }

    function remove() {
        $page = \hikari\cms\model\Page::one([
            '_id' => $this->request->request('id'),
        ], ['hydrator' => true]);
        $page->delete();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
