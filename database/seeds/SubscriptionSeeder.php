<?php

use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Subscription::create(
            [
                'name' => 'Basic',
                'price' => 0,
                'duration' => 'Unlimited',
                'max_users' => 5,
                'max_customers' => 5,
                'max_vendors' => 5,
                'image'=>'basic.png',
            ]
        );
    }
}
