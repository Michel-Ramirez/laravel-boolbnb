<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\House;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();
        $houses = $user->houses;
        return view("admin.houses.index", compact("houses"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        return view("admin.houses.create", compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Take data
        $data = $request->all();

        // New House
        $house = new House();

        // Add User id in house
        $house->user_id = Auth::id();

        // Add Address
        $address = new Address();
        $address->home_address = $data['home_address'];
        $address->latitude = $data['latitude'];
        $address->longitude = $data['longitude'];
        $address->save();
        $house->address_id = $address->id;

        // Add Photo in house
        if (array_key_exists('photo', $data)) {
            $photo_path = Storage::putFile('house_img', $data['photo']);
            $data['photo'] = $photo_path;
        }

        // Fill House
        $house->fill($data);

        // Save house into db
        $house->save();

        // Add relation many to many with service
        if (array_key_exists('service', $data)) {
            $house->services()->attach($data['service']);
        }


        // return to_route("admin.houses.create");
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(House $house)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, House $house)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        //
    }
}
