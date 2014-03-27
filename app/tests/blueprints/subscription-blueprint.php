<?php

use Woodling\Woodling;

Woodling::seed('Subscription', function ($blueprint) {
  $blueprint->sequence('id', function ($i) {
    return $i;
  });
  $blueprint->sequence('level', function ($i) {
    return $i;
  });
  $blueprint->licenses = rand(0, 10);
  $blueprint->monthly_price = rand(100, 300);
  $blueprint->annual_discount = 10;
  $blueprint->training = 1;
  $blueprint->features = 'API';
});
