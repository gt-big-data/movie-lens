<?php

class GoogleImages
{
    
    private function multi_curl($urls){
        // for curl handlers
        $curl_handlers = array();
        $images = array();
    
        //for storing contents
        $content = array();
        //setting curl handlers
        foreach ($urls as $url) 
        {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $curl_handlers[] = $curl;
        }
        //initiating multi handler
        $multi_curl_handler = curl_multi_init();
    
        // adding all the single handler to a multi handler
        foreach($curl_handlers as $key => $curl)
        {
            curl_multi_add_handle($multi_curl_handler,$curl);
        }
        
        // executing the multi handler
        do 
        {
            $multi_curl = curl_multi_exec($multi_curl_handler, $active);
        } 
        while ($multi_curl == CURLM_CALL_MULTI_PERFORM  || $active);
        
        foreach($curl_handlers as $curl)
        {
            //checking for errors
            if(curl_errno($curl) == CURLE_OK)
            {
                //if no error then getting content
                $content = curl_multi_getcontent($curl);
                $result = json_decode($content, true);
                foreach($result['responseData']['results'] as $img)
                {
                    $images[] = $img;
                }
            }
            else
            {
                $images[] = curl_error($curl);
            }
        }
        curl_multi_close($multi_curl_handler);
        return $images;
    }
    
    public function get($query) {
      //google gives 4 images per request, so you load the first 4 and then 
      //creating array with urls
        $urls = array();
        $urls[0] = 'http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=';
        $urls[0] .= urlencode($query).'&start=0';
        //performing multiple requests
        $images = $this->multi_curl($urls);
        //outputting results
        $scores = array(0,0,0,0);
        for($i = 0; $i <= 3; $i ++) {
            list($width, $height, $type, $attr) = getimagesize($images[$i]['tbUrl']);
            $scores[$i] = floor(10*pow(((0.7025)-($width/$height)), 2)-$width/15);
        }
        $best = array_keys($scores, min($scores))[0]; // minimize the scores to get the best image :)
        return $images[$best]['tbUrl'];
    }
    public function get4($query){
      //google gives 4 images per request, so you load the first 4 and then 
      //creating array with urls
        $urls = array();
        $urls[0] = 'http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=';
        $urls[0] .= urlencode($query).'&start=0';
        //performing multiple requests
        $images = $this->multi_curl($urls);
        return array($images[0]['tbUrl'],$images[1]['tbUrl'],$images[2]['tbUrl'],$images[3]['tbUrl']);
    }
}
?>