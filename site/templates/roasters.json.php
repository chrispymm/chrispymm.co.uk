<?php

$data = $pages->find('coffee/roasters')->children()->listed()->sortBy('title', 'asc');
$json = [];

$json['data']  = [];

foreach($data as $roaster) {

  $json['data'][] = array(
    'id' => (string)$roaster->id(),
    'title' => (string)$roaster->title(),
  );

}

echo json_encode($json);

