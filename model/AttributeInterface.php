<?php

namespace hikari\cms\model;

interface AttributeInterface {
    function value();
    function serialize(array $options);
    function __toString();
}
