<?php


//Attaches the route prefix based user role.

function prefix_route($expression)
{
	$user = Auth::user();
	if ($user->role == 'distributor') {
		return "dist." . $expression . "";
	} elseif ($user->role == 'sales_rep') {
		return "sales_rep." . $expression . "";
	} elseif ($user->role == 'sales_man') {
		return "sales_man." . $expression . "";
	} elseif ($user->role == 'sub_stockist') {
		return "sub_stockist." . $expression . "";
	} elseif ($user->role == 'super_stockist') {
		return "super_stockist." . $expression . "";
	} elseif ($user->role == 'wholesaler') {
		return "wholesaler." . $expression . "";
	} else {
		return "admin." . $expression . "";
	}
}
