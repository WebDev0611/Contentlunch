<?php

use Woodling\Woodling;

Woodling::seed('Permission', function ($blueprint) {
  $blueprint->sequence('name', function ($i) {
    return 'permission_'. $i;
  });
  $blueprint->sequence('display_name', function ($i) {
    return 'Permission '. $i;
  });
});
