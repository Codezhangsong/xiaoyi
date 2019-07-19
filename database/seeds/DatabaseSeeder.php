<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\Model\Students::class, 100)->create();
//        factory(App\Model\Parents::class, 100)->create();
        factory(App\Model\Course::class, 100)->create();
//        factory(\App\Model\Activity::class,100)->create();
//        factory(\App\Model\ActivityClass::class,1)->create();
//        factory(\App\Model\MessageType::class,3)->create();
//        factory(\App\Model\Message::class,100)->create();
//        factory(\App\Model\Tags::class,3)->create();
//        factory(\App\Model\Level::class,3)->create();
//        factory(\App\Model\Channel::class,1)->create();
//        factory(\App\Model\Orgs::class,100)->create();
//        factory(\App\Model\ActivityRecord::class,100)->create();
    }
}
