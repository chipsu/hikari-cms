<?php

namespace hikari\cms\controller;

use hikari\controller\Controller;
use hikari\cms\model\Post;
use hikari\cms\model\Page;

class Index extends Controller {

    function index() {
        $post = Post::one([
            'name' => $this->request->request('page', 'index'),
            'content.class' => Page::className(),
        ], ['hydrator' => true]);
        $posts = Post::find(['content.class' => Page::className()], ['hydrator' => true]);
        return ['title' => 'hello!', 'post' => $post, 'posts' => $posts];
    }

    function view($id) {
    	
    }

}
