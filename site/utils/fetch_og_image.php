<?

function fetch_og_image(string $url, string $filename) {
    if(empty($url)) return;

    $tmpPath = kirby()->root() . '/tmp/';
    $imgUrl = get_og_image_url($url);

    if($imgUrl !== '') {
        $response = Remote::get($imgUrl);
        if ($response->code() === 200) {
            // Write the file to a temp path
            F::write( $tmpPath . $filename, $response->content());
            return $tmpPath.$filename;
        }  
    }  
}

function get_og_image_url(string $url) {
    // Need a user agent otherwise fopen with remote url will get a 403
    ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
    $html = file_get_contents($url);

    $dom = new DOMDocument('1.0', 'UTF-8');
    // Need to set the error handling to prevent exceptions
    $internalErrors = libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_use_internal_errors($internalErrors);

    $imgUrl ='';
    foreach ($dom->getElementsByTagName('meta') as $node) {
        $prop = $node->getAttribute('property');
        if($prop != '') {
            if($prop == 'og:image:secure_url') {
                $imgUrl = $node->getAttribute('content');
                break;
            }
            if($prop == 'og:image') {
                $imgUrl = $node->getAttribute('content');
                break;
            }
            if($prop == 'og:image:url') {
                $imgUrl = $node->getAttribute('content');
                break;
            }
        }
    }

    if($imgUrl !== '' && !str_starts_with($imgUrl, 'http')) {
        // Ensure we have a fully qualified url with host
        $imgUrl = "https:" . $imgUrl;
    }

    return $imgUrl;
}
