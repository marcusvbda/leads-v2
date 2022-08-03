<?php

use Illuminate\Foundation\Inspiring;

Artisan::command('inspire', function () {
	$this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');


Artisan::command('logs:clear', function () {
	array_map('unlink', array_filter((array) glob(storage_path('logs/*.log'))));
	$this->comment('Logs have been cleared!');
})->describe('Clear log files');


Artisan::command('jobs:clear', function () {
	DB::table("failed_jobs")->truncate();
	DB::table("jobs")->truncate();
	$this->comment('Jobs have been cleared!');
})->describe('Clear jobs');
