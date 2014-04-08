<?php

use Woodling\Woodling;

Woodling::seed('Role', function ($blueprint) {
  $blueprint->sequence('name', function ($i) {
    return 'name_'. $i;
  });
  $blueprint->sequence('display_name', function ($i) {
    return 'Role_'. $i;
  });
});
