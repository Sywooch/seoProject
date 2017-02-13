<?php

namespace app\commands;

use yii\console\Controller;
use Yii;
use yii\helpers\Json;

/**
 * Class AvitoPhoneController
 * @package app\commands
 */
class AvitoPhoneController extends Controller
{
    private $cookies = '';
    private $lastUrl;

    public function actionGetPhone($url = null)
    {
        //initialise cookies
        $this->getUrl('http://www.avito.ru');
        if (empty($url)) {
            $url = 'https://www.avito.ru/nizhniy_novgorod/avtomobili/volkswagen_golf_2010_899761616';
        }
        $html = $this->getUrl($url);
        //echo $html;exit;
        //get avito id from url
        preg_match('/_(\d+)$/i', $url, $match);
        $id = $match[1];

        //get phoneKey from page
        preg_match('/avito\.item\.phone\s*=\s*(\'|")(.+?)(\'|")/sui', $html, $match2);
        $phoneKey = $match2[2];

        //generate pkey from id and phoneKey
        $code = $this->getCode($id, $phoneKey);

        //load image with phone
        $urlImage = "https://www.avito.ru/items/phone/{$id}?pkey={$code}";
        $filePath = $this->getFilePath();
        $this->saveImageToFile($urlImage, $filePath);

        //recognize phone from image
        $phone = $this->recognizeTextFromImage($filePath);
        echo 'phone:', $phone, PHP_EOL;
    }

    /**
     * Get Html from url
     * @param $url
     * @param bool $header
     * @return mixed|string
     */
    private  function getUrl($url, $header = true, $forPhone = false)
    {
        $cookiePath = $path = Yii::getAlias('@runtime/cookies/avito.txt');
        var_dump($cookiePath);

        (function_exists('curl_init')) ? '' : die('cURL Must be installed for geturl function to work. Ask your host to enable it or uncomment extension=php_curl.dll in php.ini');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; CrawlBot/1.0.0)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        if (!empty($this->lastUrl)) {
            curl_setopt($ch, CURLOPT_REFERER, $this->lastUrl);;
        }

        if ($forPhone) {
            $headers = array(
                'Host:www.avito.ru',
                'Content-type:charset=utf-8',
                'Connection:keep-alive'
            );
        } else {
            $headers = array(
                'Host:www.avito.ru',
                'Content-type:charset=utf-8',
                'Connection:keep-alive',
                'Accept:image/webp'
            );
            $this->lastUrl = $url;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($ch, CURLOPT_COOKIE, $this->cookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiePath);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_MAXREDIRS, 15);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $html = curl_exec($ch);
        if (!$header) {
            return $html;
        }
        $status = curl_getinfo($ch);
        $header = substr($html,0, curl_getinfo($ch,CURLINFO_HEADER_SIZE));
        curl_close($ch);

        if($status['http_code']!=200){
            if($status['http_code'] == 301 || $status['http_code'] == 302) {
                list($header) = explode("\r\n\r\n", $html, 2);
                $matches = array();
                preg_match("/(Location:|URI:)[^(\n)]*/", $header, $matches);
                $url = trim(str_replace($matches[1],"",$matches[0]));
                $url_parsed = parse_url($url);
                return (isset($url_parsed))? $this->getUrl($url):'';
            }
        }

        preg_match_all("/Set-Cookie: (.*?)=(.*?);/i", $header, $res);
        $this->cookies = '';
        foreach ($res[1] as $key => $value) {
            $this->cookies = $value.'='.$res[2][$key].'; ';
        };
        return $html;
    }

    /**
     * Generate code from id and phoneKey
     * @param $id
     * @param $phoneKey
     * @return string
     */
    private function getCode($id, $phoneKey)
    {
        preg_match_all('/([0-9a-f]+)/sui', $phoneKey, $matches);
        if ($id % 2 == 0) {
            $o = implode('', array_reverse($matches[0]));
        } else {
            $o = implode('', array_reverse($matches[0]));
        }
        $result = '';
        for ($i=0; $i < strlen($o); $i++) {
            if ($i % 3===0) {
                $result .= $o[$i];
            }
        }
        return $result;
    }

    /**
     * Save base64 string to png file
     * @param $url
     * @param $saveTo
     */
    private function saveImageToFile($url, $saveTo){
        $data = $this->getUrl($url, false, true);
        $jsonResult = Json::decode($data);
        $base64String = $jsonResult['image64'];
        $ifp = fopen($saveTo, "wb");

        $data = explode(',', $base64String);

        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);
    }

    /**
     * Generate file path
     * @return bool|string
     */
    private function getFilePath()
    {
        $dir = Yii::getAlias('@runtime/image');

        if (file_exists($dir) === false) {
            mkdir($dir, 0777, true);
        }

        return Yii::getAlias($dir . '/avito_' . time() . '.png');
    }

    /**
     * Recognize text from image
     * @param $filePath
     * @return string
     */
    private function recognizeTextFromImage($filePath)
    {
        $tesseract = new \TesseractOCR($filePath);
        $tesseract->lang('rus');
        return $tesseract->run();
    }
}