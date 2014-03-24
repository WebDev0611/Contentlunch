<?php

class SubscriptionSeeder extends Seeder {

  public function run()
  {
    // Allows to set explicit ID values in sqlserver
    // Catch exception for non sqlserver
    try {
      DB::statement("SET IDENTITY_INSERT subscriptions ON");
    } catch (Exception $e) {
    }
    $sub = new Subscription;
    $sub->id = 1;
    $sub->licenses = 5;
    $sub->monthly_price = 300;
    $sub->annual_discount = 10;
    $sub->training = 1;
    $sub->features = 'None';
    $sub->save();

    $sub = new Subscription;
    $sub->id = 2;
    $sub->licenses = 10;
    $sub->monthly_price = 500;
    $sub->annual_discount = 10;
    $sub->training = 1;
    $sub->features = 'API, Premium Support';
    $sub->save();

    $sub = new Subscription;
    $sub->id = 3;
    $sub->licenses = 20;
    $sub->monthly_price = 700;
    $sub->annual_discount = 10;
    $sub->training = 1;
    $sub->features = 'API, Premium Support, Custom Reporting, Advanced Security';
    $sub->save();
  }

}
