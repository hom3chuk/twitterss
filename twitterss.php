<?php

require_once 'src/Poster.php';
require 'vendor/autoload.php';

use \hom3chuk\twitterss\Poster;

$twitterss = new Poster();

$twitterss->post();