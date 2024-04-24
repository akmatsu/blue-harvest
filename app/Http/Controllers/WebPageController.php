<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class WebPageController extends Controller
{
  public function index()
  {
    return Inertia::render('Dashboard');
  }
}
