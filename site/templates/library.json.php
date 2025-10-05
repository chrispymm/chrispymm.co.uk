<?php

$data = $pages->find('library')->children()->listed()->filterBy('completion_date', '==', '')->sortBy('sortIndex', 'desc');
$json = [];

$json['data']  = [];
// $json['pages'] = $data->pagination()->pages();
// $json['page']  = $data->pagination()->page();

foreach($data as $book) {

  $json['data'][] = array(
    str_replace( '/', '+', (string)$book->id()) => (string)$book->title(),
  );

}

echo json_encode($json);
