<?php
$output="";
$tag="sun";
if(isset($_GET['tag'])) { 
    $tag=$_GET['tag'] ;
}

$client = $modx->getService('rest.modRestCurlClient');
$result = $client->request('http://api.flickr.com/services/feeds/photos_public.gne?', '/', 'GET',

$params = array(
  'tagmode'  => 'any', 
  "tags"     =>$tag , 
  "format"   =>"xml"
));


$xml = simplexml_load_string($result);

    foreach ($xml->entry as $link) {

$img=$link->link[1]['href'];

if($img=="https://creativecommons.org/licenses/by-sa/2.0/deed.en" or $img=="https://creativecommons.org/licenses/by-nc-sa/2.0/deed.en" or $img=="https://creativecommons.org/licenses/by-nc/2.0/deed.en" or $img=="https://creativecommons.org/licenses/by-nc-nd/2.0/deed.en" or $img=="https://creativecommons.org/licenses/by/2.0/deed.en") {
    $img=$link->link[2]['href'];
}

        $modx->setPlaceholder('link', $img);
         $output .= $modx->getChunk('tpl');
         
           }
    
return $output;