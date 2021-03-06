<?php

namespace dynamikaweb\youtube\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;

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
            $playlistId = $this->playlistId;

            if(!isset($playlistId)) return null;
            
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

    public function getPlaylistId()
    {
        $channel = $this->api->getChannels([
            'part' => 'contentDetails',
            'id' => $this->channel,
        ]);

        return isset($channel->items[0]->contentDetails->relatedPlaylists->uploads)
            ?$channel->items[0]->contentDetails->relatedPlaylists->uploads: null;

    }

    public function getDataProviderVideos($options = [])
    {
        $youtube = $this->api;
        $playlistId = $this->playlistId;

        if(!isset($playlistId)) return null;
        
        $videos = [];
        $nextPage = null;

        do {
            $playlistItems = $youtube->getPlaylistItems($playlistId,[
                'maxResults' => 50,
                'pageToken' => $nextPage,
            ]);

            foreach($playlistItems->items as $item){
                array_push($videos, [
                    'id' => $item->snippet->resourceId->videoId,
                    'titulo' => $item->snippet->title,
                    'data' => $item->snippet->publishedAt,
                    'thumb_sm' => $item->snippet->thumbnails->default->url,
                    'thumb_md' => $item->snippet->thumbnails->medium->url,
                    'thumb_lg' => $item->snippet->thumbnails->high->url,
                ]);
            }

            $nextPage = isset($playlistItems->nextPageToken)?$playlistItems->nextPageToken:null;

        } while ($nextPage != null);

        return new \yii\data\ArrayDataProvider([
            'allModels' => $videos,
            'pagination' => [
                'pageSize' => ArrayHelper::getValue($options, 'pageSize', 20),
            ],
        ]);
    }
}