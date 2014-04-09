<?php

use Woodling\Woodling;

Woodling::seed('AccountRole', function ($blueprint) {
  $blueprint->sequence('name', function ($i) {
    return 'Name_'. $i;
  });
  $blueprint->sequence('display_name', function ($i) {
    return 'Role_'. $i;
  });
  $blueprint->global = 0;
  $blueprint->deletable = 1;
  $blueprint->builtin = 0;
  $blueprint->status = 1;
});
