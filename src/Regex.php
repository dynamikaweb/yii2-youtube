<?php

namespace dynamikaweb\youtube;

class Regex
{
    const URL_PARING = '(.*?)(^|\/|v=)([a-z0-9_-]{11})(.*)';
    const URL_VALIDATE = '%^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=))([\w-]{11})$%x';
}