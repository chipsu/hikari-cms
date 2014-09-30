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
        if($id = $this->request->get('id')) {
            $result = $class::one($this->request->get('id'));
            var_dump($result);
        } else {
            $result = $class::find();
            var_dump($result);
        }
    }
}

class Content extends \hikari\controller\Controller implements RestInterface {
    use ModelRestTrait, CrudTrait {
        ModelRestTrait::modelClassName insteadof CrudTrait;
    }
}

// do we need this?
// admin interface is really just a different set of views (ie detail listview instead of grid for most things)
// Dashboard => Controller
// Root controllers checks if is_admin and adds the admin overlay and/or layout
class Admin extends \hikari\controller\Controller implements RestInterface, CrudInterface {
    use RestTrait, CrudTrait;

    public $componentProperties = [
        'view' => [
            'layout' => 'admin',
        ],
    ];

    function modelClassName() {
        return 'hikari\\cms\\model\\' . ucfirst($this->request->get('class'));
    }

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
