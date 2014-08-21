<?php

namespace hikari\cms\controller;

interface RestInterface {
    function poo();
}

trait RestTrait {
    function poo() {
    }
}

class Admin extends \hikari\controller\Controller implements RestInterface {
    use RestTrait;

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
