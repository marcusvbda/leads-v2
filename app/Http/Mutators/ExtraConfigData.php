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
			"wpp_service" => [
				"uri" => config("wpp.service.uri"),
				"username" => config("wpp.service.username"),
				"password" => config("wpp.service.password"),
			]
		]);
		return $content;
	}
}
