<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Show the IT agent dashboard with ticket management actions.
     */
    public function index()
    {
        return view('agent.dashboard');
    }
}
?>
