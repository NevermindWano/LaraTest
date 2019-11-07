<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FakerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 50)->create()->each(function ($user) {

//            dd($user);
            $user->assignRole('user');
            $user->birthday = $this->getRandomDate();
            $user->girl = rand(0, 1);
            $user->save();

            $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
            Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);

        });
    }

    private function getRandomDate()
    {
        $backwardDays = rand(-364, 0);
        $backwardYear = rand(-70, -18);
        $backwardMonth = rand(-12, 0);

        return Carbon::now()->addYear($backwardYear)->addMonth($backwardMonth)->addDay($backwardDays);

    }
}
