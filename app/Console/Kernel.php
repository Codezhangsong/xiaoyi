<?php

namespace App\Console;

use App\Console\Commands\Course\CourseUpdate;
use App\Console\Commands\Course\LessonAdd;
use App\Console\Commands\Org\StatCourse;
use App\Console\Commands\Stat\StatBaidu;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Stat\StatCourse::class,
        Commands\Stat\StatStudent::class,
        Commands\Stat\StatParent::class,
        Commands\Stat\StatActivity::class,
        Commands\Course\TeacherAdd::class,
        Commands\Course\CourseAdd::class,
        Commands\Course\CourseUpdate::class,
        Commands\Course\LessonAdd::class,
        Commands\Org\StatCourse::class,
        Commands\Org\StatActivity::class,
        Commands\Org\StatParent::class,
        Commands\Org\StatStudent::class,
        StatBaidu::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stat:parent')->dailyAt('13:00');
        $schedule->command('stat:student')->dailyAt('13:00');
        $schedule->command('stat:baidu')->dailyAt('13:00');
        $schedule->command('stat:activity')->dailyAt('13:00');
        $schedule->command('stat:course')->dailyAt('13:00');

        $schedule->command('org:parent')->dailyAt('13:00');
        $schedule->command('org:student')->dailyAt('13:00');
        $schedule->command('org:baidu')->dailyAt('13:00');
        $schedule->command('org:activity')->dailyAt('13:00');
        $schedule->command('org:course')->dailyAt('13:00');

        $schedule->command('teacher:add')->everyMinute();;
        $schedule->command('course:add')->everyMinute();;
        $schedule->command('lesson:add')->everyMinute();;
        $schedule->command('course:update')->everyMinute();;
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }


}
