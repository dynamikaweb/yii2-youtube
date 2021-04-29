<?php

namespace dynamikaweb\youtube;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Youtube extends \yii\base\Widget
{
    public $regex_pattern = Regex::URL_PARSING;

    public $regex_match = 3;

    public $options = [];

    public $url;

    /**
     * @inheritdoc
     */
    public function init()
    {
        preg_match($this->regex_pattern, $this->url, $this->url);
        $this->url = ArrayHelper::getValue($this->url, $this->regex_match, null);
        $this->options = ArrayHelper::merge([
            'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
            'frameborder' => '0',
            'height' => '315',
            'width' => '560'
        ], 
            $this->options, [
            'src' => "https://www.youtube.com/embed/{$this->url}"
        ]);

        if ($this->url === NULL) {
            throw new InvalidConfigException('$url is not valid youtube link.');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::tag('iframe', null, $this->options);
    }
}