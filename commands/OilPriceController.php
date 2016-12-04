<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\commands;
/**
 * Description of OilPriceController
 *
 * @author Trubachev Denis
 */
class OilPriceController extends \yii\console\Controller
{
    public $url = 'http://api.eia.gov/series/';
    
    public $serialId = 'PET.RBRTE.D';
    
    public $apiKey = '1c1449e953d06c3a15ff9a408caca46f';


    public function actionTest()
    {
        $url = $this->generateUrl();
        $cont = file_get_contents($url);
        var_dump(json_decode($cont));
    }
    
    private function generateUrl()
    {
        
        return $this->url . '?' . http_build_query(['api_key' => $this->apiKey, 'series_id' => $this->serialId]);
    }
}
