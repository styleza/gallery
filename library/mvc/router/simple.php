<?php

class mvc_router_simple implements mvc_router {
    public function route($uri) {
        $baseUrl = resources::get('config')->get('baseUrl')."/";
        $dataArray = explode('/',str_replace($baseUrl,"",$uri));
        
        $routingArray = array();
        if(isset($dataArray[0])){
            $routingArray["controller"] = $dataArray[0];
        }
        if(isset($dataArray[1])){
            $routingArray["action"] =  $dataArray[1];
        }
        
        for($i = 2; $i < count($dataArray); $i += 2){
            $routingArray[$dataArray[$i]] = isset($dataArray[$i+1]) ? $dataArray[$i+1] : '';
        }

        return $routingArray;
    }
}
