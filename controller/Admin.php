<?php

namespace hikari\cms\controller;

/**
 * How admin mode works:
 * - User visits /admin and is promted to login
 * - A toolbar is added to the layout if user is an Admin
 * - Admin can toggle between default and site views (wysiwyg and admin mode)
 * - All routes are the same regardless if the user is an admin or not, just different presentation.
 */
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
        $menu = [
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
        return [
            'title' => 'Admin',
            'menu' => $menu,
        ];
    }
}
