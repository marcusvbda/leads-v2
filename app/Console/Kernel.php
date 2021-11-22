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
		if (!$this->osProcessIsRunning('queue:work')) {
			$schedule
				->command('queue:work --queue=resource-import,resource-export,alert-broadcasts,event-broadcasts')
				->everyMinute();
		}
	}

	protected function commands()
	{
		// $this->load(__DIR__ . '/Commands');
		require base_path('routes/console.php');
	}

	protected function osProcessIsRunning($needle)
	{
		// get process status. the "-ww"-option is important to get the full output!
		exec('ps aux -ww', $process_status);

		// search $needle in process status
		$result = array_filter($process_status, function ($var) use ($needle) {
			return strpos($var, $needle);
		});

		// if the result is not empty, the needle exists in running processes
		if (!empty($result)) {
			return true;
		}
		return false;
	}
}
