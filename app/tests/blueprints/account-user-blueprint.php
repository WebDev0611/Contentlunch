<?php

use Woodling\Woodling;

Woodling::seed('AccountUser', function ($blueprint) {
  // Should be set in overrides
  $blueprint->user_id = null;
  $blueprint->account_id = null;
});
