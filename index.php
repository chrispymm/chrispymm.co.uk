<?php
/*
 * Required by beebmx/kirby-scheduler */
const KIRBY_HELPER_E = false;

require 'kirby/bootstrap.php';

echo (new Kirby)->render();
