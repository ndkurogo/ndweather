<?php 
class NDWeatherModule extends KGOModule
{

    protected function initializeForPage_index(KGOUIPage $page) {
        
        $args = array(  'baseURL' => 'http://xml.weather.yahoo.com/forecastrss/46556_f.xml',
                    );
        $retriever = KGODataRetriever::factory("KGOURLDataRetriever", $args);
        $data = $retriever->getData();
        //$this->assign('weatherData', $data);

        $parser = KGODataParser::factory("KGOSimpleXMLDataParser", null);
        $parsed = $parser->parseData($data);
        $item = $parsed['channel']['item'];
        var_dump($item);
        //$item->assign('forecasts', $item['yweather:forecast']);
        $today = $item['yweather:condition']['@attributes'];
        $todayTitle = sprintf("%d Degrees and %s", $today['temp'], $today['text']);
        //$this->assign('today', $todayTitle);

        //this->assign('radarImg', '<img src=\"http://radar.weather.gov/ridge/RadarImg/N0R/IWX_N0R_0.gif\"></img>');
        

        //create the forecasts array for use in the navList

        $forecasts = array();

        for($i = 0; $i < 5; $i++) {
            $forecast = $item['yweather:forecast'][$i]['@attributes'];
            //var_dump($forecast);
            $title = sprintf("%s: HI: %d, LO: %d", $forecast['day'], $forecast['high'], $forecast['low']);
            $sub = $forecast['text'];
            $img = sprintf("http://s.imwx.com/v.20100719.135915/img/wxicon/72/%d.png", $forecast['code']);
            $term = array('subtitle'=>$sub, 'day'=>$forecast['day'], 'high'=>$forecast['high'], 'low'=>$forecast['low'], 'img'=>$img, 'imgWidth'=>72, 'imgHeight'=>72);
            $forecasts[$i] = $term;
        }

        //$this->assign('forecasts', $forecasts);
        print_r($forecasts);
        //var_dump($forecasts);
        
    }
}