<?php

use Woodling\Woodling;

Woodling::seed('Role', function ($blueprint) {
  $blueprint->sequence('name', function ($i) {
    return 'Name_'. $i;
  });
  $blueprint->sequence('display_name', function ($i) {
    return 'Role_'. $i;
  });
  $blueprint->global = 1;
  $blueprint->deletable = 0;
  $blueprint->builtin = 1;
  $blueprint->status = 1;
});
