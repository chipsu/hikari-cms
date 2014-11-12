<?php

namespace hikari\cms\model;

class Media extends Content {

    static function attributeMap() {
        return array_merge(parent::attributeMap(), [
            'mimetype' => ['String', 'maxlength' => 100],
            'width' => ['Integer'],
            'height' => ['Integer'],
            'filesize' => ['Integer'],
            'filename' => ['String', 'maxlength' => 100],
            'filehash' => ['String', 'maxlength' => 100],
        ]);
    }
}
