<?php

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        $this->call('UserTableSeeder');
    }
}

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $user                = new User();
        $user->username      = "admin";
        $user->password      = Hash::make("admin");
        $user->admin         = 1;
        $user->delete        = 1;
        $user->cardsort      = 1;
        $user->fishshark     = 1;
        $user->mrant         = 1;
        $user->questionnaire = 1;
        $user->vocab         = 1;
        $user->notthis       = 1;
        $user->ecers         = 1;
        $user->save();
    }
}
