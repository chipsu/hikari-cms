<?php

namespace hikari\cms\model;

interface AttributeInterface {
    function value();
    function serialize();
    function __toString();
}
