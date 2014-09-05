<?php

namespace hikari\cms\controller;

use hikari\controller\Controller;

class Index extends Controller {

    function index() {
    	$page = new \hikari\cms\model\Page;
    	$page->title = 'Testing';
    	$page->save();
        $page = \hikari\cms\model\Page::one([
            'name' => $this->request->request('page', 'index'),
        ], ['hydrator' => true]);
        $pages = \hikari\cms\model\Page::find([], ['hydrator' => true]);
        return ['title' => 'hello!', 'page' => $page, 'pages' => $pages];
    }

    function view($id) {
    	
    }

}
