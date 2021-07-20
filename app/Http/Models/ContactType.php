<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class ContactType extends DefaultModel
{
	protected $table = "contact_types";
	// public $cascadeDeletes = [];
	// public $restrictDeletes = [];
	// public $relations = []; //add relations that you want to load in resource field (ajax)
	// public static function hasTenant() //default true
	// {
	//     return true;
	// }
}