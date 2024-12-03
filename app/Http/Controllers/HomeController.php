<?php

namespace App\Http\Controllers;

use App\Models\Job;  // Assuming you have a Job model


class HomeController extends Controller
{
    public function index()
    {
        
        $jobs = Job::paginate(10);


        return view('welcome', compact('jobs'));
    }
}
