<?php

namespace dynamikaweb\youtube\components;

use Yii;
use yii\helpers\ArrayHelper;

class YoutubeApi extends \yii\base\Component
{
    public $api_config;
    private $_api_instance;

    public $channel;

    public function getApi()
    {
        if($this->_api_instance === null){
            $this->_api_instance = new \sr1871\youtubeApi\components\YoutubeApi(
                ArrayHelper::merge([
                    'setAccessTokenFunction' => FactorySetAccessToken::create(ArrayHelper::getValue($this->api_config, 'setAccessTokenFunction', null)),
                    'getAccessTokenFunction' => FactoryGetAccessToken::create(ArrayHelper::getValue($this->api_config, 'getAccessTokenFunction', null))
                ],$this->api_config)
            );

        }

        return $this->_api_instance;
    }

    public function getFindYoutubeLive()
    {
        try {
            $youtube = $this->api;
            $channel = $youtube->getChannels([
                'part' => 'contentDetails',
                'id' => $this->channel,
            ]);

            $playlistId = isset($channel->items[0]->contentDetails->relatedPlaylists->uploads)
                ?$channel->items[0]->contentDetails->relatedPlaylists->uploads: null;

            $playlistItems = $youtube->getPlaylistItems($playlistId, [
                'maxResults' => 5,
            ]);

            $videosIds = [];
            foreach($playlistItems as $item){
                array_push($videosIds, $item->snippet->resourceId->videoId);
            }

            $listVideos = $youtube->listVideos(['id' => $videosIds]);

            foreach($listVideos->items as $video){
                if($video->snippet->liveBroadcastContent == 'live'){
                    return $video->id;           
                }
            }
            return null;

        } catch (\Exception $e) {
            return null;
        }
    }

}