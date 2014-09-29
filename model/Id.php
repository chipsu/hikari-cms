<?php

namespace hikari\cms\model;

class Id extends Attribute {

    static function base_convert($string, $frombase, $tobase) {
        if(function_exists('gmp_strval')) {
            return gmp_strval(gmp_init((string)$string, $frombase), $tobase);
        }

        $string = trim($string);

        if(intval($frombase) != 10) {
            $len = strlen($string);
            $q = 0;
            for ($i=0; $i<$len; $i++) {
                $r = base_convert($string[$i], $frombase, 10);
                $q = bcadd(bcmul($q, $frombase), $r);
            }
        } else {
            $q = $string;
        }

        if(intval($tobase) != 10) {
            $s = '';
            while (bccomp($q, '0', 0) > 0) {
                $r = intval(bcmod($q, $tobase));
                $s = base_convert($r, 10, $tobase) . $s;
                $q = bcdiv($q, $tobase, 0);
            }
        } else {
            $s = $q;
        }

        return $s;
    }

    static function pack($id) {
        $result = static::base_convert($id, 16, 36);
        return strlen($result) < 24 ? $result : $id;
    }

    static function unpack($id) {
        return static::base_convert($id, 36, 16);
    }

    function value() {
        if($this->option('pack')) {
            return static::pack($this->value);
        }
        return $this->value;
    }

    function serialize() {
        $value = $this->value;
        if($value === null && $this->option('null')) {
            return null;
        }
        return $value ? new \MongoId(strlen($value) != 24 ? static::unpack($value) : $value) : new \MongoId;
    }
}
