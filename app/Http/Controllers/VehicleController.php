<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicle = Vehicle::orderBy('created_at', 'desc')->get();

        $data = [
            'page_title'     => 'Master Vehicle',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
            'vehicle'   => $vehicle,
        ];
        return view('admin.vehicle.main')->with($data);
    }

    public function show(Vehicle $vehicle){
        return response()->json($vehicle, 200);
    }

    public function create()
    {
        $data = [
            'page_title'     => 'Master Vehicle',
            'base_url'       => env('APP_URL'),
            'app_name'       => env('APP_NAME'),
            'app_name_short' => env('APP_NAME_ABBR'),
        ];
        return view('admin.vehicle.add')->with($data);
    }

    public function store(VehicleRequest $request)
    {
        $data = collect($request->validated())->except(['photo'])->all();

        $data['photo'] = 'img/vehicle/default.png';
        if ($request->has('photo')) {
            $fileName = time() . '.' . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('img/vehicle/'), $fileName);
            $filePath = 'img/vehicle/' . $fileName;
            $data['photo'] = $filePath;
        }

        Vehicle::create($data);
        return redirect()->route('admin.vehicle.index')->with('success', 'Create successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        $page_title     = "Edit Vehicle";
        $base_url       = env('APP_URL');
        $app_name       = env('APP_NAME');
        $app_name_short = env('APP_NAME_ABBR');

        return view('admin.vehicle.edit', compact('vehicle', 'page_title', 'base_url', 'app_name', 'app_name_short'));
    }

    public function update(Vehicle $vehicle, VehicleRequest $request)
    {
        $data = collect($request->validated())->except(['photo'])->all();

        if ($request->has('photo')) {
            $fileName = time() . '.' . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('img/vehicle/'), $fileName);
            $filePath = 'img/vehicle/' . $fileName;
            $data['photo'] = $filePath;
        }

        $vehicle->update($data);
        return redirect()->route('admin.vehicle.index')->with('success', 'Update successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return response()->json([
            'message' => 'Record deleted successfully!'
        ], 200);
    }
}
