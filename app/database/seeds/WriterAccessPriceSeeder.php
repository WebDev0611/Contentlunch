<?php

class WriterAccessPriceSeeder extends Seeder
{

    public function run()
    {
        $writerAccessPrices = [

            // Blog Post
            ['asset_type_id' => 2, 'writer_level' => 6, 'wordcount' => 500, 'fee' => 90],
            ['asset_type_id' => 2, 'writer_level' => 6, 'wordcount' => 750, 'fee' =>  130],
            ['asset_type_id' => 2, 'writer_level' => 6, 'wordcount' => 1000, 'fee' =>  170],
            ['asset_type_id' => 2, 'writer_level' => 6, 'wordcount' => 1200, 'fee' =>  195],

            ['asset_type_id' => 2, 'writer_level' => 5, 'wordcount' => 500, 'fee' => 80],
            ['asset_type_id' => 2, 'writer_level' => 5, 'wordcount' => 750, 'fee' =>  120],
            ['asset_type_id' => 2, 'writer_level' => 5, 'wordcount' => 1000, 'fee' =>  160],
            ['asset_type_id' => 2, 'writer_level' => 5, 'wordcount' => 1200, 'fee' =>  180],

            ['asset_type_id' => 2, 'writer_level' => 4, 'wordcount' => 500, 'fee' => 70],
            ['asset_type_id' => 2, 'writer_level' => 4, 'wordcount' => 750, 'fee' =>  110],
            ['asset_type_id' => 2, 'writer_level' => 4, 'wordcount' => 1000, 'fee' =>  150],
            ['asset_type_id' => 2, 'writer_level' => 4, 'wordcount' => 1200, 'fee' =>  170],


            // eBook
            ['asset_type_id' => 3, 'writer_level' => 6, 'wordcount' => 2000, 'fee' => 500],
            ['asset_type_id' => 3, 'writer_level' => 6, 'wordcount' => 3000, 'fee' => 700],
            ['asset_type_id' => 3, 'writer_level' => 6, 'wordcount' => 4000, 'fee' => 900],
            ['asset_type_id' => 3, 'writer_level' => 6, 'wordcount' => 5000, 'fee' => 1100],

            ['asset_type_id' => 3, 'writer_level' => 5, 'wordcount' => 2000, 'fee' => 400],
            ['asset_type_id' => 3, 'writer_level' => 5, 'wordcount' => 3000, 'fee' => 600],
            ['asset_type_id' => 3, 'writer_level' => 5, 'wordcount' => 4000, 'fee' => 800],
            ['asset_type_id' => 3, 'writer_level' => 5, 'wordcount' => 5000, 'fee' => 1000],

            ['asset_type_id' => 3, 'writer_level' => 4, 'wordcount' => 2000, 'fee' => 350],
            ['asset_type_id' => 3, 'writer_level' => 4, 'wordcount' => 3000, 'fee' => 550],
            ['asset_type_id' => 3, 'writer_level' => 4, 'wordcount' => 4000, 'fee' => 750],
            ['asset_type_id' => 3, 'writer_level' => 4, 'wordcount' => 5000, 'fee' => 950],


            // White paper
            ['asset_type_id' => 1, 'writer_level' => 6, 'wordcount' => 2000, 'fee' => 500],
            ['asset_type_id' => 1, 'writer_level' => 6, 'wordcount' => 3000, 'fee' => 700],
            ['asset_type_id' => 1, 'writer_level' => 6, 'wordcount' => 4000, 'fee' => 900],
            ['asset_type_id' => 1, 'writer_level' => 6, 'wordcount' => 5000, 'fee' => 1100],

            ['asset_type_id' => 1, 'writer_level' => 5, 'wordcount' => 2000, 'fee' => 400],
            ['asset_type_id' => 1, 'writer_level' => 5, 'wordcount' => 3000, 'fee' => 600],
            ['asset_type_id' => 1, 'writer_level' => 5, 'wordcount' => 4000, 'fee' => 800],
            ['asset_type_id' => 1, 'writer_level' => 5, 'wordcount' => 5000, 'fee' => 1000],

            ['asset_type_id' => 1, 'writer_level' => 4, 'wordcount' => 2000, 'fee' => 350],
            ['asset_type_id' => 1, 'writer_level' => 4, 'wordcount' => 3000, 'fee' => 550],
            ['asset_type_id' => 1, 'writer_level' => 4, 'wordcount' => 4000, 'fee' => 750],
            ['asset_type_id' => 1, 'writer_level' => 4, 'wordcount' => 5000, 'fee' => 950],

            // Case Study
            ['asset_type_id' => 30, 'writer_level' => 6, 'wordcount' => 500, 'fee' => 250],
            ['asset_type_id' => 30, 'writer_level' => 6, 'wordcount' => 750, 'fee' => 350],
            ['asset_type_id' => 30, 'writer_level' => 6, 'wordcount' => 1000, 'fee' => 450],
            ['asset_type_id' => 30, 'writer_level' => 6, 'wordcount' => 1200, 'fee' => 550],

            ['asset_type_id' => 30, 'writer_level' => 5, 'wordcount' => 500, 'fee' => 200],
            ['asset_type_id' => 30, 'writer_level' => 5, 'wordcount' => 750, 'fee' => 300],
            ['asset_type_id' => 30, 'writer_level' => 5, 'wordcount' => 1000, 'fee' => 400],
            ['asset_type_id' => 30, 'writer_level' => 5, 'wordcount' => 1200, 'fee' => 500],

            ['asset_type_id' => 30, 'writer_level' => 4, 'wordcount' => 500, 'fee' => 150],
            ['asset_type_id' => 30, 'writer_level' => 4, 'wordcount' => 750, 'fee' => 250],
            ['asset_type_id' => 30, 'writer_level' => 4, 'wordcount' => 1000, 'fee' => 350],
            ['asset_type_id' => 30, 'writer_level' => 4, 'wordcount' => 1200, 'fee' => 450],


            // Email
            ['asset_type_id' => 26, 'writer_level' => 6, 'wordcount' => 200, 'fee' => 55],
            ['asset_type_id' => 26, 'writer_level' => 6, 'wordcount' => 400, 'fee' => 90],
            ['asset_type_id' => 26, 'writer_level' => 6, 'wordcount' => 600, 'fee' => 130],
            ['asset_type_id' => 26, 'writer_level' => 6, 'wordcount' => 800, 'fee' => 150],

            ['asset_type_id' => 26, 'writer_level' => 5, 'wordcount' => 200, 'fee' => 45],
            ['asset_type_id' => 26, 'writer_level' => 5, 'wordcount' => 400, 'fee' => 80],
            ['asset_type_id' => 26, 'writer_level' => 5, 'wordcount' => 600, 'fee' => 120],
            ['asset_type_id' => 26, 'writer_level' => 5, 'wordcount' => 800, 'fee' => 140],

            ['asset_type_id' => 26, 'writer_level' => 4, 'wordcount' => 200, 'fee' => 35],
            ['asset_type_id' => 26, 'writer_level' => 4, 'wordcount' => 400, 'fee' => 70],
            ['asset_type_id' => 26, 'writer_level' => 4, 'wordcount' => 600, 'fee' => 100],
            ['asset_type_id' => 26, 'writer_level' => 4, 'wordcount' => 800, 'fee' => 130],


            // Website Page
            ['asset_type_id' => 8, 'writer_level' => 6, 'wordcount' => 200, 'fee' => 70],
            ['asset_type_id' => 8, 'writer_level' => 6, 'wordcount' => 300, 'fee' => 80],
            ['asset_type_id' => 8, 'writer_level' => 6, 'wordcount' => 400, 'fee' => 90],
            ['asset_type_id' => 8, 'writer_level' => 6, 'wordcount' => 500, 'fee' => 100],
            ['asset_type_id' => 8, 'writer_level' => 5, 'wordcount' => 200, 'fee' => 60],
            ['asset_type_id' => 8, 'writer_level' => 5, 'wordcount' => 300, 'fee' => 70],
            ['asset_type_id' => 8, 'writer_level' => 5, 'wordcount' => 400, 'fee' => 80],
            ['asset_type_id' => 8, 'writer_level' => 5, 'wordcount' => 500, 'fee' => 90],
            ['asset_type_id' => 8, 'writer_level' => 4, 'wordcount' => 200, 'fee' => 55],
            ['asset_type_id' => 8, 'writer_level' => 4, 'wordcount' => 300, 'fee' => 65],
            ['asset_type_id' => 8, 'writer_level' => 4, 'wordcount' => 400, 'fee' => 75],
            ['asset_type_id' => 8, 'writer_level' => 4, 'wordcount' => 500, 'fee' => 85],

            // Press Release
            ['asset_type_id' => 31, 'writer_level' => 6, 'wordcount' => 400, 'fee' => 90],
            ['asset_type_id' => 31, 'writer_level' => 6, 'wordcount' => 600, 'fee' => 110],
            ['asset_type_id' => 31, 'writer_level' => 6, 'wordcount' => 800, 'fee' => 135],

            ['asset_type_id' => 31, 'writer_level' => 5, 'wordcount' => 400, 'fee' => 80],
            ['asset_type_id' => 31, 'writer_level' => 5, 'wordcount' => 600, 'fee' => 100],
            ['asset_type_id' => 31, 'writer_level' => 5, 'wordcount' => 800, 'fee' => 125],

            ['asset_type_id' => 31, 'writer_level' => 4, 'wordcount' => 400, 'fee' => 70],
            ['asset_type_id' => 31, 'writer_level' => 4, 'wordcount' => 600, 'fee' => 90],
            ['asset_type_id' => 31, 'writer_level' => 4, 'wordcount' => 800, 'fee' => 110],

            // Newsletter
            ['asset_type_id' => 32, 'writer_level' => 6, 'wordcount' => 1000, 'fee' => 250],
            ['asset_type_id' => 32, 'writer_level' => 6, 'wordcount' => 1500, 'fee' => 350],
            ['asset_type_id' => 32, 'writer_level' => 6, 'wordcount' => 2000, 'fee' => 450],
            ['asset_type_id' => 32, 'writer_level' => 6, 'wordcount' => 2500, 'fee' => 550],

            ['asset_type_id' => 32, 'writer_level' => 5, 'wordcount' => 1000, 'fee' => 200],
            ['asset_type_id' => 32, 'writer_level' => 5, 'wordcount' => 1500, 'fee' => 300],
            ['asset_type_id' => 32, 'writer_level' => 5, 'wordcount' => 2000, 'fee' => 400],
            ['asset_type_id' => 32, 'writer_level' => 5, 'wordcount' => 2500, 'fee' => 500],

            ['asset_type_id' => 32, 'writer_level' => 4, 'wordcount' => 1000, 'fee' => 175],
            ['asset_type_id' => 32, 'writer_level' => 4, 'wordcount' => 1500, 'fee' => 275],
            ['asset_type_id' => 32, 'writer_level' => 4, 'wordcount' => 2000, 'fee' => 375],
            ['asset_type_id' => 32, 'writer_level' => 4, 'wordcount' => 2500, 'fee' => 475],

            // Product Description
            ['asset_type_id' => 9, 'writer_level' => 6, 'wordcount' => 50, 'fee' => 40],
            ['asset_type_id' => 9, 'writer_level' => 6, 'wordcount' => 150, 'fee' => 60],
            ['asset_type_id' => 9, 'writer_level' => 6, 'wordcount' => 300, 'fee' => 90],
            ['asset_type_id' => 9, 'writer_level' => 6, 'wordcount' => 500, 'fee' => 110],

            ['asset_type_id' => 9, 'writer_level' => 5, 'wordcount' => 50, 'fee' => 35],
            ['asset_type_id' => 9, 'writer_level' => 5, 'wordcount' => 150, 'fee' => 50],
            ['asset_type_id' => 9, 'writer_level' => 5, 'wordcount' => 300, 'fee' => 80],
            ['asset_type_id' => 9, 'writer_level' => 5, 'wordcount' => 500, 'fee' => 100],

            ['asset_type_id' => 9, 'writer_level' => 4, 'wordcount' => 50, 'fee' => 30],
            ['asset_type_id' => 9, 'writer_level' => 4, 'wordcount' => 150, 'fee' => 45],
            ['asset_type_id' => 9, 'writer_level' => 4, 'wordcount' => 300, 'fee' => 75],
            ['asset_type_id' => 9, 'writer_level' => 4, 'wordcount' => 500, 'fee' => 90],

            // Script (Video)
            ['asset_type_id' => 10, 'writer_level' => 6, 'wordcount' => 2000, 'fee' => 400],
            ['asset_type_id' => 10, 'writer_level' => 6, 'wordcount' => 3000, 'fee' => 500],
            ['asset_type_id' => 10, 'writer_level' => 6, 'wordcount' => 4000, 'fee' => 600],
            ['asset_type_id' => 10, 'writer_level' => 6, 'wordcount' => 5000, 'fee' => 700],

            ['asset_type_id' => 10, 'writer_level' => 5, 'wordcount' => 2000, 'fee' => 350],
            ['asset_type_id' => 10, 'writer_level' => 5, 'wordcount' => 3000, 'fee' => 450],
            ['asset_type_id' => 10, 'writer_level' => 5, 'wordcount' => 4000, 'fee' => 550],
            ['asset_type_id' => 10, 'writer_level' => 5, 'wordcount' => 5000, 'fee' => 650],

            ['asset_type_id' => 10, 'writer_level' => 4, 'wordcount' => 2000, 'fee' => 300],
            ['asset_type_id' => 10, 'writer_level' => 4, 'wordcount' => 3000, 'fee' => 400],
            ['asset_type_id' => 10, 'writer_level' => 4, 'wordcount' => 4000, 'fee' => 500],
            ['asset_type_id' => 10, 'writer_level' => 4, 'wordcount' => 5000, 'fee' => 600],

        ];

    foreach ($writerAccessPrices as $row) {
        $writerAccessPrice = new WriterAccessPrice;
        $writerAccessPrice->asset_type_id = $row['asset_type_id'];
        $writerAccessPrice->writer_level = $row['writer_level'];
        $writerAccessPrice->wordcount = $row['wordcount'];
        $writerAccessPrice->fee = $row['fee'];
        $writerAccessPrice->updateUniques();
    }

    $this->command->info('Added Writer Access Prices');
  }
}
