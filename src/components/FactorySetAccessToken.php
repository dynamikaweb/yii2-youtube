<?php

namespace dynamikaweb\youtube\components;


class FactorySetAccessToken 
{
    public static function create($clausure)
    {
        if ($clausure !== NULL) {
            return $clausure;
        }

        return function($client){ 
            file_put_contents('pathFile.txt', json_encode($client->getAccessToken()));
        };
    }
}