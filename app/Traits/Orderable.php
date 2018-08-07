<?php


namespace App\Traits;

trait Orderable
{
	public function scopeLatestFirst($query)
	{
		return $query->orderBy('created_at', 'desc');
	}

	//Any other order methods using scopes, can now go here, and we import the trait in any model we need the functionality:

	
}