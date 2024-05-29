<?php

Kirby::plugin('chrispymm/custom-methods', [
  'siteMethods' => [
    'time' => function() {
      return time();
    }
  ]
]);
