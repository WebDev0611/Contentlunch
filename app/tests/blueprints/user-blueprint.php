<?php

use Woodling\Woodling;

Woodling::seed('User', function ($blueprint) {
  $blueprint->sequence('username', function ($i) {
    return 'username_'. $i;
  });
  $blueprint->sequence('email', function ($i) {
    return 'email_'. $i .'@mail.net';
  });
  $blueprint->password = $blueprint->password_confirmation = 'password';
  $blueprint->confirmation_code = 12345;
  $blueprint->first_name = 'First_'. rand(1, 100);
  $blueprint->last_name = 'Last_'. rand(1, 100);
  $blueprint->confirmed = 1;
  $blueprint->address = '123 sw '. rand(1, 100);
  $blueprint->address_2 = 'Suite '. rand(1, 100);
  $blueprint->city = 'City_'. rand(1, 100);
  $blueprint->state = 'WA';
  $blueprint->phone = rand(1111111111, 9999999999);
  $blueprint->title = 'Title_'. rand(1, 100);
  $blueprint->status = 1;
  $blueprint->country = 'USA';
});
