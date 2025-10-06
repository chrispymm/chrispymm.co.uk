<?php

$data = $pages->find('library')->children()->listed()->filterBy('completion_date', '==', '')->sortBy('sortIndex', 'desc');
$json = [];

$json['data']  = [];

foreach($data as $book) {

  $json['data'][] = array(
    'id' => str_replace('/', '+', (string)$book->id()),
    'title' => (string)$book->title(),
  );

}

echo json_encode($json);
