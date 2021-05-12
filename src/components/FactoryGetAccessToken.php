<?php

namespace dynamikaweb\youtube\components;


class FactoryGetAccessToken 
{
    public static function create($clausure)
    {
        if ($clausure !== NULL) {
            return $clausure;
        }

        return function(){ 
            return file_get_contents('pathFile.txt');
        };
    }
}