<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('message_statuses')->insert([
            [
                'name' => 'На модерации'
            ],
            [
                'name' => 'Опубликованно'
            ],
            [
                'name' => 'Отклонено'
            ],
        ]);
    }
}
