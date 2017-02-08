<?php

namespace app\commands;

use yii\console\Controller;

/**
 * Class SvgController
 * @package app\commands
 */
class SvgController extends Controller
{
    public function actionTest2()
    {
        $svg = "<?xml version=\"1.0\" standalone=\"no\"?>\r\n<svg id=\"SvgjsSvg1006\" width=\"100%\" height=\"100%\" xmlns=\"http://www.w3.org/2000/svg\" version=\"1.1\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:svgjs=\"http://svgjs.com/svgjs\"><defs id=\"SvgjsDefs1007\"></defs><image id=\"SvgjsImage1008\" xlink:href=\"http://raster-spb.ru/files/catalog_cups/4662_large.jpg\" width=\"855\" height=\"800\" transform=\"matrix(1,0,0,1,-14.5,-63)\"></image><image id=\"SvgjsImage1009\" xlink:href=\"https://firebasestorage.googleapis.com/v0/b/test-20081.appspot.com/o/images%2Forder%2Fhqdefault.jpg?alt=media&amp;token=cbcb0d96-9af1-4235-89a1-8cc372e69d92\" width=\"480\" height=\"360\" transform=\"matrix(1,0,0,1,43,141)\"></image></svg>";
        preg_match_all('/href=\\"([\\w:\\/\\-\\.\\?\\%=\\&\\;]+)/', $svg, $match);
        $urls = $match[1];

        $replaces = [];
        foreach ($urls as $url) {
            $header = get_headers($url, 1);
            $type = $header['Content-Type'];
            $data = file_get_contents($url);
            $base64 = 'data:' . $type . ';base64,' . base64_encode($data);
            $replaces[$url] = $base64;
        }
        $result = str_replace(array_keys($replaces), array_values($replaces), $svg);
        $path = Yii::$app->basePath . '/../frontend/web/qwe.svg';
        file_put_contents($path, $result);
    }
}
