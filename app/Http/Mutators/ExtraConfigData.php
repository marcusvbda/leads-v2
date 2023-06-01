<?php

namespace App\Http\Mutators;

use marcusvbda\vstack\Mutators\BaseMutator;

class ExtraConfigData extends BaseMutator
{
	protected $needsAuth = false;
	public function process($content)
	{
		$content["config"] = array_merge($content["config"], [
			"description" => config("app.description"),
		]);
		return $content;
	}
}
