<?php
/**
 * Tutsplanet
 *
 * This class narrates the functions to support download a video from YouTube
 * @class YouTubeDownloader
 * @author Tutsplanet
 *
 */

Class YouTubeDownloader {

    /**
     * Get the YouTube code from a video URL
     * @param $url
     * @return mixed
     */
    public function GetVideoId($url) {
        parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
        return $vars['v'];
    }

    /**
     * Process the video url and return details of the video
     * @param $vid
     * @return array|void
     */

    public function VideoData($vid) {
        parse_str(file_get_contents("https://youtube.com/get_video_info?video_id=".$vid),$info);


        $playabilityJson = json_decode($info['player_response']);
        $formats = $playabilityJson->streamingData->formats;
        $adaptiveFormats = $playabilityJson->streamingData->adaptiveFormats;

        //Checking playable or not
        $IsPlayable = $playabilityJson->playabilityStatus->status;

        $result = array();

        if(!empty($info) && $info['status'] == 'ok' && strtolower($IsPlayable) == 'ok') {
            $i=0;
			$videoInfo = json_decode($info['player_response']);
			$title = $videoInfo->videoDetails->title;
            $thumbnail = $videoInfo->videoDetails->thumbnail->thumbnails{3}->url;
            foreach($adaptiveFormats as $stream) {

                $streamUrl = $stream->url;
                $type = explode(";", $stream->mimeType);

                $qualityLabel='';
                if(!empty($stream->qualityLabel)) {
                    $qualityLabel = $stream->qualityLabel;
                }

                $videoOptions[$i]['link'] = $streamUrl;
                $videoOptions[$i]['type'] = $type[0];
                $videoOptions[$i]['quality'] = $qualityLabel;
                $i++;
            }
            $j=0;
            foreach($formats as $stream) {

                $streamUrl = $stream->url;
                $type = explode(";", $stream->mimeType);

                $qualityLabel='';
                if(!empty($stream->qualityLabel)) {
                    $qualityLabel = $stream->qualityLabel;
                }

                $videoOptionsOrg[$j]['link'] = $streamUrl;
                $videoOptionsOrg[$j]['type'] = $type[0];
                $videoOptionsOrg[$j]['quality'] = $qualityLabel;
                $j++;
            }
            $result['videos'] = array(
                'thumbnail'=>$thumbnail,
				'title'=>$title,
                'formats'=>$videoOptionsOrg
            );
            
            
            return $result;
        }
        else {
            return;
        }
    }

}
