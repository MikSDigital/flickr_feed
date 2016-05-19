<?php
$output="";
$tag="sun"; // default tag
if(isset($_GET['tag'])) { // check if URL has tag parameter, needs to be sanitized later...
    $tag=$_GET['tag'] ;
}

$client = $modx->getService('rest.modRestCurlClient');  // use native MODX CurlClient to send request to flickr API
$result = $client->request('http://api.flickr.com/services/feeds/photos_public.gne?', '/', 'GET',
// request parameters
$params = array(
  'tagmode'  => 'any', 
  "tags"     =>$tag , 
  "format"   =>"xml"
));


$xml = simplexml_load_string($result); // load XML feed to parse

    foreach ($xml->entry as $link) {

$img=$link->link[1]['href']; // fetch image link

// !!! flickr XML feed has few different type of links, some of them not image related
// this is not good solution to filter links just to speed up development, future improvement - to use xpath or array of bad links to filter
if($img=="https://creativecommons.org/licenses/by-sa/2.0/deed.en" or $img=="https://creativecommons.org/licenses/by-nc-sa/2.0/deed.en" or $img=="https://creativecommons.org/licenses/by-nc/2.0/deed.en" or $img=="https://creativecommons.org/licenses/by-nc-nd/2.0/deed.en" or $img=="https://creativecommons.org/licenses/by/2.0/deed.en") {
    $img=$link->link[2]['href'];  // if bad link - get next
}

        $modx->setPlaceholder('link', $img);
         $output .= $modx->getChunk('tpl');  // templating output
         
           }
    
return $output;
