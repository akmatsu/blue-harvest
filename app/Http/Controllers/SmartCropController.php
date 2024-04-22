<?php

namespace App\Http\Controllers;

use App\Helpers\SmartCrop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartCropController extends Controller
{
  public function index(Request $r): object
  {
    $input = $r->query(
      'input',
      'https://images.unsplash.com/photo-1713086158386-a3dccad87df9?q=80&w=2586&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'
    );
    $sc = new SmartCrop($input);
    $image = $sc->createAndStoreImage();
    return response()->json($image);
  }
}
