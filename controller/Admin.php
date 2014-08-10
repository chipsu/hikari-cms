<?php

namespace \hikari\cms\controller;

class Admin extends \hikari\controller\Controller implements RestInterface {
    use \hikari\controller\Rest;

    function index() {
        # hmm
        # - create a list of all Rest controllers that are enabled
        # - ... stuff appears automagically 
        # - admin.coffee
        return [
            'services' => [...],
        ];
    }
}
