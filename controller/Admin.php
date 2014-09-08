<?php

namespace hikari\cms\controller;

interface RestInterface {
    function get();
    function put();
    function post();
    function delete();
}

trait RestTrait {
    function get() {
        \hikari\exception\NotSupported::raise();
    }

    function put() {
        \hikari\exception\NotSupported::raise();
    }

    function post() {
        \hikari\exception\NotSupported::raise();
    }

    function delete() {
        \hikari\exception\NotSupported::raise();
    }

}

trait ModelTrait {
    static function modelClassName() {
        return str_replace('\\controller\\', '\\model\\', get_called_class());
    }
}

trait ModelRestTrait {
    use RestTrait, ModelTrait;

    function get() {
        $class = static::modelClassName();
        $model = $class::one($this->request->get('id'));
        var_dump($model);
    }
}

class Content extends \hikari\controller\Controller implements RestInterface {
    use ModelRestTrait, CrudTrait {
        ModelRestTrait::modelClassName insteadof CrudTrait;
    }
}

class Admin extends \hikari\controller\Controller implements RestInterface, CrudInterface {
    use RestTrait, CrudTrait;

    public $componentProperties = [
        'view' => [
            'layout' => 'admin',
        ],
    ];

    function index() {
        $Content = '\hikari\cms\controller\Content';
        var_dump($Content::modelClassName());
        $c = new Content;
        #$c->get();
        $c->create();
        die;

        # hmm
        # - create a list of all Rest controllers that are enabled
        # - ... stuff appears automagically 
        # - admin.coffee
        return [
            'title' => 'Admin', 'icon' => 'fa-icon', 'route' => ['index', []],
            'menu' => [
                [ 'title' => 'Dashboard', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                [ 'title' => 'Pages', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                [ 'title' => 'Posts', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                [
                    'title' => 'Webshop', 'icon' => 'fa-icon', 'route' => ['index', []],
                    'items' => [
                        [ 'title' => 'Products', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                        [ 'title' => 'Orders', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                    ],
                ],
                [
                    'title' => 'Accounts', 'icon' => 'fa-icon', 'route' => ['index', []],
                    'items' => [
                        [ 'title' => 'Groups', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                        [ 'title' => 'Users', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                    ],
                ],
                [ 'title' => 'Account', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                [ 'title' => 'System', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                [ 'title' => 'Notes', 'icon' => 'fa-icon', 'route' => ['index', []], ],
                [ 'title' => 'Help', 'icon' => 'fa-icon', 'route' => ['index', []], ],
            ],
        ];
    }
}
