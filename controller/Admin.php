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
        # hmm
        # - create a list of all Rest controllers that are enabled
        # - ... stuff appears automagically 
        # - admin.coffee
        return [
            'title' => 'Admin',
            'menu' => [
                [ 'title' => 'Dashboard', 'icon' => 'fa-icon', 'route' => ['admin', []], ],
                [ 'title' => 'Pages', 'icon' => 'fa-icon', 'route' => ['admin', ['type' => 'page']], ],
                [ 'title' => 'Posts', 'icon' => 'fa-icon', 'route' => ['admin', ['type' => 'post']], ],
                [ 'title' => 'Media', 'icon' => 'fa-icon', 'route' => ['admin', ['type' => 'media']], ],
                [
                    'title' => 'Webshop', 'icon' => 'fa-icon', 'route' => ['admin', ['action' => 'webshop']],
                    'items' => [
                        [ 'title' => 'Products', 'icon' => 'fa-icon', 'route' => ['admin', ['class' => 'post', 'action' => 'list', 'type' => 'product']], ],
                        [ 'title' => 'Orders', 'icon' => 'fa-icon', 'route' => ['admin', ['class' => 'post', 'action' => 'list', 'type' => 'order']], ],
                    ],
                ],
                [
                    'title' => 'Accounts', 'icon' => 'fa-icon', 'route' => ['admin', ['action' => 'accounts']],
                    'items' => [
                        [ 'title' => 'Groups', 'icon' => 'fa-icon', 'route' => ['admin', ['action' => 'groups']], ],
                        [ 'title' => 'Users', 'icon' => 'fa-icon', 'route' => ['admin', ['action' => 'users']], ],
                    ],
                ],
                [ 'title' => 'Account', 'icon' => 'fa-icon', 'route' => ['admin', ['action' => 'account']], ],
                [ 'title' => 'System', 'icon' => 'fa-icon', 'route' => ['admin', ['action' => 'system']], ],
                [ 'title' => 'Notes', 'icon' => 'fa-icon', 'route' => ['admin', ['action' => 'notes']], ],
                [ 'title' => 'Help', 'icon' => 'fa-icon', 'route' => ['admin', ['action' => 'help']], ],
            ],
        ];
    }
}
