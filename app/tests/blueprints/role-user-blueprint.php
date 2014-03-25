<?php

use Woodling\Woodling;

Woodling::seed('RoleUser', function ($blueprint) {
  // Should be overridden
  $blueprint->user_id = null;
  $blueprint->role_id = null;
});
