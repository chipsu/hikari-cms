<?php

namespace hikari\cms\controller;

class Admin extends \hikari\controller\Controller {

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
        ];
    }

    protected function beforeRender() {
        // temp fix
        if($this->action->id != 'index') {
            $this->viewFile = 'post/' . $this->action->id;
        }
        $this->view->data['menu'] = [
            [ 'title' => 'Dashboard', 'icon' => 'fa-icon', 'route' => ['admin', []], ],
            [ 'title' => 'Pages', 'icon' => 'fa-icon', 'route' => ['admin', ['class' => 'page', 'action' => 'read']], ],
            [ 'title' => 'Posts', 'icon' => 'fa-icon', 'route' => ['admin', ['class' => 'post', 'action' => 'read']], ],
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
        ];
        return parent::beforeRender();
    }
}
