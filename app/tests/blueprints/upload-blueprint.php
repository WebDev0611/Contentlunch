<?php

use Woodling\Woodling;

Woodling::seed('Upload', function ($blueprint) {
  $blueprint->sequence('filename', function ($i) {
    return 'somefile-'. $i .'-.jpg';
  });
  $blueprint->path = '/public/uploads/';
  $blueprint->size = rand(10000, 20000);
  $blueprint->extension = 'jpg';
  $blueprint->mimetype = 'image/jpeg';
  $blueprint->parent_id = 0;
});