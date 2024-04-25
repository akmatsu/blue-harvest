<?php

namespace App\Http\Controllers;

use App\Helpers\SmartCrop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;

class SmartCropController extends Controller
{
  public function index(Request $r): object
  {
    $r->validate(['id' => 'required|integer']);
    $id = $r->query('id');
    $path = Image::findOrFail($id)->path;
    $options = $this->getOptions();
    $sc = new SmartCrop($path);
    return $sc->options($options)->createAndStoreImage();
  }

  protected function getOptions(): object
  {
    $return = (object) [
      // Defaults
      'width' => (int) request('width', 0),
      'height' => (int) request('height', 0),
      'crop' => (string) request('crop', 'none'),
      'extension' => (string) request('extension', 'webp'),
      'quality' => (string) request('quality', '75'),
      'filter' => (string) request('filter', null),
    ];

    return $return;
  }
}
