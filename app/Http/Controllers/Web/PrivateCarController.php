<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PrivateCar;
use Illuminate\Http\Request;

class PrivateCarController extends Controller
{
    /**
     * Display a listing of private cars.
     */
    public function index()
    {
        $cars = PrivateCar::where('is_active', true)
            ->with(['media'])
            ->paginate(10);

        return view('web.private_cars.index', compact('cars'));
    }

    /**
     * Display the specified private car.
     */
    public function show(PrivateCar $car)
    {
        $car->load('media');
        return view('web.private_cars.show', compact('car'));
    }
}
