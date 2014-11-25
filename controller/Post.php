<?php

namespace hikari\cms\controller;

use hikari\controller\Controller;
use hikari\cms\model\Post as PostModel;
use hikari\cms\model\Page as PageModel;

class Post extends Controller implements CrudInterface {
    use CrudTrait;

    // This should not be here, move back to Index and fetch start page.
    function index() {
        $query = [];
        if(get_called_class() != __CLASS__) {
            $query['class'] = $this->modelClassName();
        }
        $post = PostModel::one(array_merge([
            'name' => $this->request->request('name', 'index'),
        ], $query), ['hydrator' => true]);
        $posts = PostModel::find($query, ['hydrator' => true]);
        return ['title' => 'hello!', 'post' => $post, 'posts' => $posts];
    }
}
