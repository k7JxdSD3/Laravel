<?php

namespace App\Http\Controllers\Admin;

use App\Model\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
	public function __construct(User $user)
	{
		$this->middleware('auth:admin');
		$this->user = $user;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$users = $this->user->select('id', 'name', 'created_at')->paginate(14);
		return view('admin.users.index', compact('users'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user)
	{
		$user = $this->user->with('addresses')->find($user->id);
		return view('admin.users.show', compact('user'));
	}
}
