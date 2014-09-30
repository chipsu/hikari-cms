<?php

namespace hikari\cms\controller;

use hikari\controller\Controller;
use hikari\cms\model\Post as PostModel;
use hikari\cms\model\Page as PageModel;

class Post extends Controller implements CrudInterface {
    use CrudTrait;

    // This should not be here, move back to Index and fetch start page.
    function index() {
        $post = PostModel::one([
            'name' => $this->request->request('page', 'index'),
            'content.class' => PageModel::className(),
        ], ['hydrator' => true]);
        $posts = PostModel::find(['content.class' => PageModel::className()], ['hydrator' => true]);
        return ['title' => 'hello!', 'post' => $post, 'posts' => $posts];
    }
}
