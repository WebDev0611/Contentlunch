<?php

use Woodling\Woodling;

Woodling::seed('Module', function ($blueprint) {
  $blueprint->sequence('name', function ($i) {
    return 'name_'. $i;
  });
  $blueprint->sequence('title', function ($i) {
    return 'title_'. $i;
  });
});
