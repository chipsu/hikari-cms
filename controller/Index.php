<?php

namespace hikari\cms\controller;

use hikari\controller\Controller;

class Index extends Controller {

    function index() {
    	/*$post = \hikari\cms\model\Post::create();
        $post->name = 'index';
    	$post->title = 'Testing';
        $post->content = \hikari\cms\model\Page::create();
    	$post->save();*/
        $post = \hikari\cms\model\Post::one([
            'name' => $this->request->request('page', 'index'),
            'content.class' => \hikari\cms\model\Page::className(),
        ], ['hydrator' => true]);
        $posts = \hikari\cms\model\Post::find(['content.class' => \hikari\cms\model\Page::className()], ['hydrator' => true]);
        return ['title' => 'hello!', 'page' => $post, 'pages' => $posts];
    }

    function view($id) {
    	
    }

}
