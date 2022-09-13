<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	protected $commands = [
		// 
	];

	protected function schedule(Schedule $schedule)
	{
		$schedule
			->command('logs:clear')
			->days(2)
			->withoutOverlapping(5);

		$schedule
			->command('optimize:clear')
			->days(2)
			->withoutOverlapping(5);

		$schedule
			->command('config:cache')
			->days(2)
			->withoutOverlapping(5);

		$schedule
			->command('queue:work --queue=resource-import,resource-export,mail-integrator,default')
			->everyMinute()
			->withoutOverlapping(5);
	}

	protected function commands()
	{
		$this->load(__DIR__ . '/Commands');
		require base_path('routes/console.php');
	}
}
