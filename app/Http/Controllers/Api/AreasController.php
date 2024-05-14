<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function delete(Area $area): void
    {
        $area->delete();
    }

    public function create(Request $request): Area
    {
        $request->validate([
            'name' => ['string', 'required'],
            'alt_name' => ['string', 'required'],
        ]);
        return Area::create($request->all());
    }
}
