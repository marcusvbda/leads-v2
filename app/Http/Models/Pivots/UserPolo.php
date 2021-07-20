<?php

namespace App\Http\Models\Pivots;

use marcusvbda\vstack\Models\{
	User,
	Polo
};

use Illuminate\Database\Eloquent\Model;

class UserPolo extends Model
{
	public $guarded = ["user_id", "polo_id"];

	public  function user()
	{
		return $this->belongsTo(User::class);
	}

	public  function polo()
	{
		return $this->belongsTo(Polo::class);
	}
}