dynamikaweb/yii2-youtube 
========================
[![Latest Stable Version](https://img.shields.io/github/v/release/dynamikaweb/yii2-youtube)](https://github.com/dynamikaweb/yii2-youtube/releases)
[![Total Downloads](https://poser.pugx.org/dynamikaweb/yii2-youtube/downloads)](https://packagist.org/packages/dynamikaweb/yii2-youtube)
[![License](https://img.shields.io/github/license/dynamikaweb/yii2-youtube)](https://github.com/dynamikaweb/yii2-youtube/blob/master/LICENSE)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5646ac4727764429ba7ad8af72a76e31)](https://www.codacy.com/gh/dynamikaweb/yii2-youtube/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dynamikaweb/yii2-youtube&amp;utm_campaign=Badge_Grade)
[![Build Test](https://scrutinizer-ci.com/g/dynamikaweb/yii2-youtube/badges/build.png?b=master)](https://scrutinizer-ci.com/g/dynamikaweb/yii2-youtube/)

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```SHELL
$ composer require dynamikaweb/yii2-youtube "*"
```

or add

```JSON
"dynamikaweb/yii2-youtube": "*"
```

to the `require` section of your `composer.json` file.


## Usage
Add it to your components 

### add to your components
```PHP
'components' => [
    ...
    'youtube' => [
        'class' => \sr1871\youtubeApi\components\YoutubeApi::className(),
        'api_config' => [
            'clientId' => 'your Oauth Client Id, you can get it from google console',
            'clientSecret' => 'your Oauth Client Secret, you can get it from google console',
            'scopes' => ['scopes that you going to use', 'as array'],
        ],
        'channel' = 'youtube channel, which will be consulted'
    ],
    ...
]
```

### generate your access token

An advantage of this component is that you only have to generate your access token once.

Create an action in any controller 

```PHP
public function actionYoutubeValidation() {
    if(Yii::$app->request->get('validate')){
        return $this->redirect(Yii::$app->youtube->validationGet(Yii::$app->urlManager->createAbsoluteUrl('/site/youtube-validation')));
    }

    if(Yii::$app->request->get('code')){
        Yii::$app->youtube->validationPost(Yii::$app->urlManager->createAbsoluteUrl('/site/youtube-validation'));
    } else {
        Yii::$app->session->setFlash('success', 'The access token was generated');
        return $this->redirect('index');
    }
}
```
To validate access to the action by passing the `validate` parameter come true.
This will access google to do the validation and it will return to the same action by passing the parameter `code` with the` Access Token`.

#### Example:
https://yoursite.com.br/site/youtube-validation?validate=1


### Example

```PHP
Yii::$app->youtube->setParts(['snippet', 'recordingDetails', 'id'])->listVideos(['id' => 'someId'])
```

You can pass in ```setParts()```, the parts that you want, if you don't want the default parts. For more information of every method and how do it, read the PhpDOC of component's methods
