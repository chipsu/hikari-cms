<?php

namespace hikari\cms\controller;

interface CrudInterface {
    function create();
    function read();
    function update();
    function dispose();
    function modelClassName();
}
