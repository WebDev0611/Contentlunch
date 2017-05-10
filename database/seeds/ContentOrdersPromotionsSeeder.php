<?php

use App\Promo;
use Illuminate\Database\Seeder;

class ContentOrdersPromotionsSeeder extends Seeder {

    private function eligibleEmails(){
        // Insert eligible email addresses into this array
        return [
            'admin@test.com',
            'editor@test.com',
            'ante.rejo@toptal.com',
        ];
    }

    public function run()
    {
        $promos = Promo::all();

        foreach ($this->eligibleEmails() as $emailAdress) {
            if(!$promos->contains('email', $emailAdress)) {
                DB::table('content_orders_promotions')->insert([
                    'email' => $emailAdress
                ]);
            }
        }
    }
}