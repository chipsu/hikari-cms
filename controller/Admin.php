<?php

namespace hikari\cms\controller;

class Admin extends \hikari\controller\Controller implements RestInterface {
    use \hikari\controller\Rest;

    function index() {
        # hmm
        # - create a list of all Rest controllers that are enabled
        # - ... stuff appears automagically 
        # - admin.coffee
        return [
            'menu' => [
                [ 'title' => 'Dashboard', ],
                [],
                [ 'title' => 'Pages', ],
                [ 'title' => 'Posts', ],
                [
                    'title' => 'Webshop',
                    'items' => [
                        [ 'title' => 'Products', ],
                        [ 'title' => 'Orders', ],
                    ],
                ]
                [
                    'title' => 'Accounts',
                    'items' => [
                        [ 'title' => 'Groups', ],
                        [ 'title' => 'Users', ],
                    ],
                ]
                [ 'title' => 'Account', ],
                [ 'title' => 'System', ],
                [ 'title' => 'Help', ],
            ],
        ];
    }
}
