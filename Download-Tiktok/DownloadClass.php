<?php

class Helper
{
  public static function finalUrl($url)
  {
      $ch      = curl_init();
      $options = [
          CURLOPT_URL            => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HEADER         => false,
          CURLOPT_HTTPHEADER     => [
              'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
              'Accept-Encoding: gzip, deflate, br',
              'Accept-Language: en-US,en;q=0.9',
              'Connection: keep-alive',
          ],
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_USERAGENT      => 'okhttp',
          CURLOPT_ENCODING       => "utf-8",
          CURLOPT_AUTOREFERER    => false,
          CURLOPT_CONNECTTIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_TIMEOUT        => 30,
          CURLOPT_MAXREDIRS      => 10,
      ];
      curl_setopt_array($ch, $options);
      if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
          curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
      }
      $data  = curl_exec($ch);
      $final = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
      curl_close($ch);
      return $final;
  }

  public static function normalize($string)
  {
      $string = preg_replace("/([^a-z0-9])/", "-", strtolower($string));
      $string = preg_replace("/(\s+)/", "-", strtolower($string));
      $string = preg_replace("/([-]+){2,}/", "-", strtolower($string));
      return $string;
  }

  public static function parseData($items = [])
  {
      $final = [];
      foreach ($items as $item) {
          $final[] = (object) [
              "id"                => @$item->itemInfos->id,
              "desc"              => @$item->itemInfos->text,
              "createTime"        => @$item->itemInfos->createTime,
              "video"             => (object) [
                  "id"            =>"awesome",
                  "height"       => @$item->itemInfos->video->videoMeta->height,
                  "width"        => @$item->itemInfos->video->videoMeta->width,
                  "duration"     => @$item->itemInfos->video->videoMeta->duration,
                  "ratio"        => @$item->itemInfos->video->videoMeta->height,
                  "cover"        => @$item->itemInfos->covers[0],
                  "originCover"  => @$item->itemInfos->coversOrigin[0],
                  "dynamicCover" => @$item->itemInfos->coversDynamic[0],
                  "playAddr"     => @$item->itemInfos->video->urls[0],
                  "downloadAddr" => @$item->itemInfos->video->urls[0],
              ],
              "author"            => (object) [
                  "id"           => @$item->authorInfos->userId,
                  "uniqueId"     => @$item->authorInfos->uniqueId,
                  "nickname"     => @$item->authorInfos->nickName,
                  "avatarThumb"  => @$item->authorInfos->covers[0],
                  "avatarMedium" => @$item->authorInfos->coversMedium[0],
                  "avatarLarger" => @$item->authorInfos->coversLarger[0],
                  "signature"    => @$item->authorInfos->signature,
                  "verified"     => @$item->authorInfos->verified,
                  "secUid"       => @$item->authorInfos->secUid,
              ],
              "music"             => (object) [
                  "id"          => @$item->musicInfos->musicId,
                  "title"       => @$item->musicInfos->musicName,
                  "playUrl"     => @$item->musicInfos->playUrl[0],
                  "coverThumb"  => @$item->musicInfos->covers[0],
                  "coverMedium" => @$item->musicInfos->coversMedium[0],
                  "coverLarge"  => @$item->musicInfos->coversLarger[0],
                  "authorName"  => @$item->musicInfos->authorName,
                  "original"    => @$item->musicInfos->original,
              ],
              "stats"             => (object) [
                  "diggCount"    => @$item->itemInfos->diggCount,
                  "shareCount"   => @$item->itemInfos->shareCount,
                  "commentCount" => @$item->itemInfos->commentCount,
                  "playCount"    => @$item->itemInfos->playCount,
              ],
              "originalItem"      => @$item->itemInfos->isOriginal,
              "officalItem"       => @$item->itemInfos->isOfficial,
              "secret"            => @$item->itemInfos->secret,
              "forFriend"         => @$item->itemInfos->forFriend,
              "digged"            => @$item->itemInfos->liked,
              "itemCommentStatus" => @$item->itemInfos->commentStatus,
              "showNotPass"       => @$item->itemInfos->showNotPass,
              "vl1"               => false,

          ];
      }
      return $final;
  }

  public static function string_between($string, $start, $end)
  {
      $string = ' ' . $string;
      $ini    = strpos($string, $start);
      if (0 == $ini) {
          return '';
      }

      $ini += strlen($start);
      $len = strpos($string, $end, $ini) - $ini;
      return substr($string, $ini, $len);
  }
  public static function makeId()
  {
      $characters = '0123456789';
      $randomString = '';
      $n = 16;
      for ($i = 0; $i < $n; $i++) {
          $index = rand(0, strlen($characters) - 1);
          $randomString .= $characters[$index];
      }

      return "68" . $randomString;
  }
}

class DownloadClass {
  const API_BASE = "https://www.tiktok.com/node/";
  private $_config = [];
  
  private $cacheEngine;

  private $cacheEnabled = false;
  
  private $defaults = [
      "user-agent"     => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36',
      "proxy-host"     => false,
      "proxy-port"     => false,
      "proxy-username" => false,
      "proxy-password" => false,
      "cache-timeout"  => 3600,
      "nwm_endpoint"   => false,
      "api_key"   => false
  ];
  
  protected $buffer_size = 1000000;
  
  public function __construct($config = array(), $cacheEngine = false)
  {
    
      $this->_config = array_merge(['cookie_file' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'tiktok.txt'], $this->defaults, $config);

      if ($cacheEngine) {
          $this->cacheEnabled = true;
          $this->cacheEngine        = $cacheEngine;
      }
  }
  
  private function generateName()
  {
    $characters = '0123456789AaBbCcDd';
    $randomString = '';
    $n = 16;
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
    
    return "FbazTiktok_" . $randomString;
    
  }
  
  private function remote_call($url = "", $isJson = true, $headers = ['Referer: https://www.tiktok.com/foryou?lang=en'])
  {
      $ch      = curl_init();
      $options = [
          CURLOPT_URL            => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HEADER         => false,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_USERAGENT      => $this->_config['user-agent'],
          CURLOPT_ENCODING       => "utf-8",
          CURLOPT_AUTOREFERER    => true,
          CURLOPT_CONNECTTIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_TIMEOUT        => 30,
          CURLOPT_MAXREDIRS      => 10,
          CURLOPT_HTTPHEADER     => array_merge([], $headers),
          CURLOPT_COOKIEJAR      => $this->_config['cookie_file'],
          CURLOPT_COOKIEFILE => $this->_config['cookie_file'],
      ];

      curl_setopt_array($ch, $options);
      if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
          curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
      }
      if ($this->_config['proxy-host'] && $this->_config['proxy-port']) {
          curl_setopt($ch, CURLOPT_PROXY, $this->_config['proxy-host'] . ":" . $this->_config['proxy-port']);
          if ($this->_config['proxy-username'] && $this->_config['proxy-password']) {
              curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->_config['proxy-username'] . ":" . $this->_config['proxy-password']);
          }
      }
      $data = curl_exec($ch);
      curl_close($ch);
      if ($isJson) {
          $data = json_decode($data);
      }
      return $data;
  }
  
  private function file_size($url)
  {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, true);
      curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Referer: https://www.tiktok.com/foryou?lang=en',
      ]);
      curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user-agent']);
      curl_setopt($ch, CURLOPT_REFERER, "https://www.tiktok.com/");

      curl_setopt($ch, CURLOPT_COOKIEFILE, $this->config['cookie_file']);
      curl_setopt($ch, CURLOPT_COOKIEJAR, $this->config['cookie_file']);
      $data = curl_exec($ch);
      $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
      curl_close($ch);
      return (int) $size;
  }

  public function Downloadurl($url, $file_name = "tiktok-video", $ext = "mp4")
  {
      $file_size = $this->file_size($url);
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . $file_name . '.' . $ext . '"');
      header("Content-Transfer-Encoding: binary");
      header('Expires: 0');
      header('Pragma: public');

      if ($file_size > 100) {
          header('Content-Length: ' . $file_size);
      }
      header('Connection: Close');
      ob_clean();
      flush();
      if (function_exists('apache_setenv')) {
          @apache_setenv('no-gzip', 1);
      }
      @ini_set('zlib.output_compression', false);
      @ini_set('implicit_flush', true);
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user-agent']);
      curl_setopt($ch, CURLOPT_REFERER, "https://www.tiktok.com/");
      curl_setopt($ch, CURLOPT_COOKIEFILE, $this->config['cookie_file']);
      curl_setopt($ch, CURLOPT_COOKIEJAR, $this->config['cookie_file']);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      echo $output;
      exit;
  }

  private function getVideoByUrl($url = "")
  {
    try {
      $cacheKey = Helper::normalize($url);
      if ($this->cacheEnabled) {
          if ($this->cacheEngine->get($cacheKey)) {
              return $this->cacheEngine->get($cacheKey);
          }
      }
      if (!preg_match("/https?:\/\/([^\.]+)?\.tiktok\.com/", $url)) {
          throw new \Exception("Invalid VIDEO URL");
      }
      $result      = $this->remote_call($url, false);
      $result = Helper::string_between($result, '{"props":{"initialProps":{', "</script>");
      if (!empty($result)) {
          $jsonData = json_decode('{"props":{"initialProps":{' . $result);
          if (isset($jsonData->props->pageProps->itemInfo->itemStruct)) {
              $result = (object) [
                  'statusCode' => 0,
                  'info'       => (object) [
                      'type'   => 'video',
                      'detail' => $url,
                  ],
                  "items"      => [$jsonData->props->pageProps->itemInfo->itemStruct],
                  "hasMore"    => false,
                  "minCursor"  => '0',
                  "maxCursor"  => ' 0',
              ];
              if ($this->cacheEnabled) {
                  $this->cacheEngine->set($cacheKey, $result, $this->_config['cache-timeout']);
              }
              return [
                "err" => "false",
                "result" => $result,
              ];
          }
      }
      return $this->failure();
    } catch (Exception $e) {
      return [
        "err" => "true",
        "message" => $e->getMessage(),
      ];
    }
  }
  
  private function failure()
  {

      @unlink($this->_config['cookie_file']);
      return false;
  }
  
  public function Data($url) 
  {
    $result = $this->getVideoByUrl($url);
    $err = $result["err"];
    
    if( $err == "true" ) {
      return $result;
      
    } elseif ( $err == "false") {
      $result = $result["result"];
      $title = $result->items[0]->desc;
      $uniqueId = $result->items[0]->author->uniqueId;
      $nickname = $result->items[0]->author->nickname;
      $imgUrl = $result->items[0]->author->avatarLarger;
      $titleFile = str_replace(" ", "-", $title);
      $filename = $this->generateName() . "_" . $titleFile;
      $playAddr = $result->items[0]->video->playAddr;
      $idVideo =  $result->items[0]->id;
      $embedVid = '<blockquote class="tiktok-embed" cite="https://www.tiktok.com/@' . $uniqueId . '/video/' . $idVideo . '" data-video-id="' . $idVideo . '" style="max-width: 605px;min-width: 325px;"><section></section></blockquote><script async src="https://www.tiktok.com/embed.js"></script>';
      
      return [
        "idVideo"  => $idVideo,
        "title"    => $title,
        "nickId"   => $uniqueId,
        "nickname" => $nickname,
        "filename" => $filename,
        "playAddr" => $playAddr,
        "imageUrl" => $imgUrl,
        "embedVid" => $embedVid,
        "err"      => "false",
      ];
    }
    
  }
  
}