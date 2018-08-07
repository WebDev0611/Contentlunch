<?php

use App\Promo;

class ContentOrdersPromotionsSeeder extends BaseSeeder {

    private function emails(){
        return [
            'admin@test.com',
            'editor@test.com',
            'ante.rejo@toptal.com',
        ];
    }

    public function run()
    {
        $promos = Promo::all();

        foreach ($this->emails() as $emailAdress) {
            if(!$promos->contains('email', $emailAdress)) {
                DB::table('content_orders_promotions')->insert([
                    'email' => $emailAdress
                ]);
            }
        }
    }
}