<?php

$data = $pages->find('coffee/beans')->children()->listed()->limit(5);
$json = [];

$json['data']  = [];

foreach($data as $roast) {

  $json['data'][] = array(
    'id' => str_replace('/', '+', (string)$roast->id()),
    'title' => (string)$roast->title(),
  );

}

echo json_encode($json);

