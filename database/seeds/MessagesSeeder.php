<?php

use App\Models\Messages;
use Illuminate\Database\Seeder;
use App\User;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Messages::class, 165)->create()->each(function ($message) {
            $chance = rand(0, 5);
            $parent_id = ($chance == 1) ? rand(1, $message->id - 1) : 0;
            $message->parent_id = $parent_id;
            $message->save();
        });
    }
}
