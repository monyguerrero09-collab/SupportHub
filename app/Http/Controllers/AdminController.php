<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with ticket management, user admin, knowledge base and stats.
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
?>
