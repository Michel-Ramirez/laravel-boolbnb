<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\House;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['string', 'required', Rule::in(['Villa', 'Villa a schiera', 'Appartamento', 'Hotel'])],
            'description' => 'required|string',
            'night_price' => 'required|numeric',
            'total_bath' => 'required|numeric',
            'total_rooms' => 'required|numeric',
            'total_beds' => 'required|numeric',
            'mq' => 'numeric|nullable',
            'photo' => 'image|nullable',
            'is_published' => 'boolean|nullable',
            'home_address' => 'required|string',
            'service' => 'exists:services,id',
        ], [
            'name.required' => 'Il campo nome è obbligatorio',
            'name.max' => 'Il campo nome può avere un massimo di 255 caratteri',
            'type.required' => 'Il tipo di struttura è obbligatoria',
            'type.in' => 'Il tipo di struttura deve essere tra quelli indicati',
            'description.required' => 'La descrizione è obbligatoria',
            'night_price.numeric' => 'Il prezzo deve essere un numero',
            'night_price.required' => 'Il prezzo è obbligatorio',
            'total_bath.numeric' => 'Il totale dei bagni deve essere un numero',
            'total_bath.required' => 'Il totale dei bagni è obbligatorio',
            'total_rooms.numeric' => 'Il totale delle camere deve essere un numero',
            'total_rooms.required' => 'Il totale delle camere è obbligatorio',
            'mq.numeric' => 'La metratura della casa deve essere un numero',
            'is_published.boolean' => 'Il valore di pubblica è errato',
            'home_address.required' => "L'indirizzo della casa è obbligatorio",
            'service.exists' => 'Uno o più servizi selezionati non sono validi'
        ]);


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

        // Add is published
        if (array_key_exists('is_published', $data)) {
            $house->is_published = true;
        }

        // Fill House
        $house->fill($data);

        // Save house into db
        $house->save();

        // Add relation many to many with service
        if (array_key_exists('service', $data)) {
            $house->services()->attach($data['service']);
        }


        return to_route("user.houses.index")->with('type', 'create')->with('message', 'Casa inserita con successo')->with('alert', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(House $house)
    {
        $lastSponsorEnd = $house->sponsors()->latest('sponsor_end')->first();
        $sponsorEnd = $lastSponsorEnd->pivot->sponsor_end;
        $sponsorEndDate = Carbon::parse($sponsorEnd)->format('d/m/Y');
        return view('admin.houses.show', compact('house', 'sponsorEndDate'));
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
