<?php

namespace dynamikaweb\youtube;

class Regex
{
    const URL_PARSING = '%^(.*?)(^|\/|v=)([\w-]{11})(.*)$%x';
    const URL_VALIDATE = '%^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=))([\w-]{11})$%x';
}