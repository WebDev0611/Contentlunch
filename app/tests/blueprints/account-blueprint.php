<?php

use Woodling\Woodling;

Woodling::seed('Account', function ($blueprint) {
  $blueprint->sequence('title', function ($i) {
    return 'title_'. $i;
  });
  $blueprint->active = 1;
  $blueprint->address = '123 sw 32nd st.';
  $blueprint->address_2 = 'Suite 111';
  $blueprint->name = 'name';
  $blueprint->city = 'Spokane';
  $blueprint->state = 'WA';
  $blueprint->phone = '1231231234';
  $blueprint->country = 'US';
  $blueprint->zipcode = '99218';
  $blueprint->email = 'foobar@mail.net';
  $blueprint->auto_renew = 1;
  $expiration = date('Y-m-d H:i:s', strtotime('+30 days'));
  $blueprint->expiration_date = $expiration;
  $blueprint->payment_type = 'CC';
  $blueprint->token = 12345;
  $blueprint->yearly_payment = 0;
});
