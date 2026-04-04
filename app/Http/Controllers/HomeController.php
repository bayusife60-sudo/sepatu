<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        }
        elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        else {
            return redirect()->route('customer.dashboard');
        }
    }
}
