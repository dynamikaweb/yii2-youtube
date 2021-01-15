<?php

namespace dynamikaweb\youtube;

class Validator extends \yii\validators\RegularExpressionValidator
{
    public $pattern = Regex::URL_VALIDATE;   
}