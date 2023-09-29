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
        $house = new House();
        return view("admin.houses.create", compact('services', 'house'));
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
            'night_price' => 'required|numeric|max:9999999',
            'total_bath' => 'required|numeric|max:255',
            'total_rooms' => 'required|numeric|max:255',
            'total_beds' => 'required|numeric|max:255',
            'mq' => 'numeric|nullable|max:32000',
            'photo' => 'image|nullable',
            'is_published' => 'boolean|nullable',
            'home_address' => 'required|string',
            'service' => 'required|exists:services,id',
        ], [
            'name.required' => 'Il campo nome è obbligatorio',
            'name.max' => 'Il campo nome può avere un massimo di 255 caratteri',
            'type.required' => 'Il tipo di struttura è obbligatoria',
            'type.in' => 'Il tipo di struttura deve essere tra quelli indicati',
            'description.required' => 'La descrizione è obbligatoria',
            'night_price.numeric' => 'Il prezzo deve essere un numero',
            'night_price.required' => 'Il prezzo è obbligatorio',
            'night_price.max' => 'Il prezzo non può essere superiore 9999999',
            'total_bath.max' => 'Il totale dei bagni non può essere maggiore di 255',
            'total_bath.numeric' => 'Il totale dei bagni deve essere un numero',
            'total_bath.required' => 'Il totale dei bagni è obbligatorio',
            'total_rooms.max' => 'Il totale delle camere non può essere superiore di 255',
            'total_rooms.numeric' => 'Il totale delle camere deve essere un numero',
            'total_rooms.required' => 'Il totale delle camere è obbligatorio',
            "total_beds.max" => 'Il totale dei posti letto non può essere superiore di 255',
            "total_beds.numeric" => 'Il totale dei posti letto deve essere un numero',
            "total_beds.required" => 'Il totale dei posti letto è obbligatorio',
            "mq.max" => 'La metratura della casa non deve essere superiore a 32000mq',
            'mq.numeric' => 'La metratura della casa deve essere un numero',
            'is_published.boolean' => 'Il valore di pubblica è errato',
            'home_address.required' => "L'indirizzo della casa è obbligatorio",
            'service.required' => 'La casa deve avere almeno un servizio',
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
        // Control if the log user is same of the house user
        $user = Auth::id();
        if ($house->user_id != $user) {
            return to_route('user.houses.index');
        };

        $lastSponsorEnd = $house->sponsors()->latest('sponsor_end')->first();
        $sponsorEndDate = null;
        if ($lastSponsorEnd) {
            $sponsorEnd = $lastSponsorEnd->pivot->sponsor_end;
            $sponsorEndDate = Carbon::parse($sponsorEnd)->format('d/m/Y');
        }
        // $sponsorEnd = $lastSponsorEnd->pivot->sponsor_end;
        // $sponsorEndDate = Carbon::parse($sponsorEnd)->format('d/m/Y');
        return view('admin.houses.show', compact('house', 'sponsorEndDate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(House $house)
    {
        // Control if the log user is same of the house user
        $user = Auth::id();
        if ($house->user_id != $user) {
            return to_route('user.houses.index');
        };

        // Take all services
        $services = Service::all();

        // Take only the id of the house services
        $servicesArray = $house->services;
        $servicesIdArray = [];
        foreach ($servicesArray as $service) {
            $servicesIdArray[] = $service->id;
        };

        return view('admin.houses.edit', compact('house', 'services', 'servicesIdArray'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, House $house)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['string', 'required', Rule::in(['Villa', 'Villa a schiera', 'Appartamento', 'Hotel'])],
            'description' => 'required|string',
            'night_price' => 'required|numeric|max:9999999',
            'total_bath' => 'required|numeric|max:255',
            'total_rooms' => 'required|numeric|max:255',
            'total_beds' => 'required|numeric|max:255',
            'mq' => 'numeric|nullable|max:32000',
            'photo' => 'image|nullable',
            'is_published' => 'boolean|nullable',
            'home_address' => 'required|string',
            'service' => 'required|exists:services,id',
        ], [
            'name.required' => 'Il campo nome è obbligatorio',
            'name.max' => 'Il campo nome può avere un massimo di 255 caratteri',
            'type.required' => 'Il tipo di struttura è obbligatoria',
            'type.in' => 'Il tipo di struttura deve essere tra quelli indicati',
            'description.required' => 'La descrizione è obbligatoria',
            'night_price.numeric' => 'Il prezzo deve essere un numero',
            'night_price.required' => 'Il prezzo è obbligatorio',
            'night_price.max' => 'Il prezzo non può essere superiore 9999999',
            'total_bath.max' => 'Il totale dei bagni non può essere maggiore di 255',
            'total_bath.numeric' => 'Il totale dei bagni deve essere un numero',
            'total_bath.required' => 'Il totale dei bagni è obbligatorio',
            'total_rooms.max' => 'Il totale delle camere non può essere superiore di 255',
            'total_rooms.numeric' => 'Il totale delle camere deve essere un numero',
            'total_rooms.required' => 'Il totale delle camere è obbligatorio',
            "total_beds.max" => 'Il totale dei posti letto non può essere superiore di 255',
            "total_beds.numeric" => 'Il totale dei posti letto deve essere un numero',
            "total_beds.required" => 'Il totale dei posti letto è obbligatorio',
            "mq.max" => 'La metratura della casa non deve essere superiore a 32000mq',
            'mq.numeric' => 'La metratura della casa deve essere un numero',
            'is_published.boolean' => 'Il valore di pubblica è errato',
            'home_address.required' => "L'indirizzo della casa è obbligatorio",
            'service.required' => 'La casa deve avere almeno un servizio',
            'service.exists' => 'Uno o più servizi selezionati non sono validi'
        ]);

        $data = $request->all();

        // Added image in project
        if (array_key_exists('photo', $data)) {
            if ($house->image) Storage::delete($house->image);
            $photo_path = Storage::putFile('house_img', $data['photo']);
            $data['photo'] = $photo_path;
        };

        // Add is published
        if (array_key_exists('is_published', $data)) {
            $house->is_published = true;
        } else {
            $house->is_published = false;
        };


        // Take old address in database
        $oldAddress = $house->address;


        // Add Address
        $address = new Address();
        $address->home_address = $data['home_address'];
        $address->latitude = $data['latitude'];
        $address->longitude = $data['longitude'];
        $address->save();
        $house->address_id = $address->id;

        // Update House
        $house->update($data);

        // Delete old address in database
        $oldAddress->delete();

        // Update Services
        if (!array_key_exists('service', $data) && count($house->services)) {
            $house->services()->detach();
        } elseif (array_key_exists('service', $data)) {
            $house->services()->sync($data['service']);
        }

        return to_route('user.houses.show', $house);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        $house->delete();
        return to_route("user.houses.index")->with('type', 'delete')->with('message', 'Casa cancellata con successo')->with('alert', 'success');
    }

    public function trash(House $house)
    {
        $houses = House::onlyTrashed()->get();

        return view('admin.houses.trash', compact('houses'));
    }

    public function restore(string $id)
    {
        $house = House::onlyTrashed()->findOrFail($id);

        $house->restore();

        return to_route('user.houses.trash')->with('type', 'restore')->with('message', 'Casa recuperata con successo')->with('alert', 'success');
    }

    public function drop(string $id)
    {
        $house = House::onlyTrashed()->findOrFail($id);
        $house->forceDelete();
        return to_route("user.houses.trash");
    }
}
