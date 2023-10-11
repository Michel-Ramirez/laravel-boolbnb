<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\House;
use App\Models\Service;
use App\Models\Sponsor;
use App\Models\User;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Braintree\Gateway;
use DateInterval;
use DateTime;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = Auth::user();
        // $houses = $user->houses;
        $houses = House::where('user_id', '=', $user->id)->paginate(5);
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
            return view('admin.houses.notAuth');
        };

        // Take last sponsor of the house
        $lastSponsorEnd = $house->sponsors()->latest('sponsor_end')->orderBy("house_id", "DESC")->first();

        $sponsorEndDate = null;
        // Create current date
        $currentDate = Carbon::now();
        // Check if the last sponsor date is > of the current date
        if ($lastSponsorEnd && $lastSponsorEnd->pivot->sponsor_end > $currentDate) {
            $sponsorEnd = $lastSponsorEnd->pivot->sponsor_end;
            $sponsorEndDate = Carbon::parse($sponsorEnd)->format('d/m/Y');
        }
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
            return view('admin.houses.notAuth');
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
            if ($house->photo) Storage::delete($house->photo);
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

        return to_route('user.houses.show', $house)->with('type', 'update')->with('message', 'Casa modificata con successo')->with('alert', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(House $house)
    {
        $house->delete();
        return to_route("user.houses.index")->with('type', 'delete')->with('message', 'Casa cancellata con successo')->with('alert', 'success');
    }

    // Trash

    public function trash(House $house)
    {
        $houses = House::onlyTrashed()->get();

        return view('admin.houses.trash', compact('houses'));
    }

    // Restore

    public function restore(string $id)
    {
        $house = House::onlyTrashed()->findOrFail($id);

        $house->restore();

        return to_route('user.houses.trash')->with('type', 'restore')->with('message', 'Casa recuperata con successo')->with('alert', 'success');
    }

    // Drop 

    public function drop(string $id)
    {
        $house = House::onlyTrashed()->findOrFail($id);
        if ($house->photo) Storage::delete($house->photo);
        $house->forceDelete();
        return to_route("user.houses.trash");
    }

    // Publish

    public function publish(Request $request, House $house)
    {
        $data = $request->all();
        if (array_key_exists('is_published', $data)) {
            $house->is_published = true;
            $house->save();
            $message = "Casa pubblicata con successo";
        } elseif (!array_key_exists('is_published', $data) && $house->is_published == true) {
            $house->is_published = false;
            $house->save();
            $message = "La casa è stata inserita nelle bozze ";
        }
        return to_route("user.houses.index")->with('type', 'edit')->with('message', $message)->with('alert', 'success');
    }


    // Sponsors

    public function sponsors(House $house)
    {
        // Control if the log user is same of the house user
        $user = Auth::id();

        // Se non è lo stesso lo mando in notAuth
        if ($house->user_id != $user) {
            return view('admin.houses.notAuth');
        };

        $sponsors = Sponsor::all();

        return view('admin.houses.sponsors', compact('house', "sponsors"));
    }



    // Sponsor

    public function sponsor(House $house, Sponsor $sponsor)
    {
        // Control if the log user is same of the house user
        $user = Auth::id();

        // Se non è lo stesso lo mando in notAuth
        if ($house->user_id != $user) {
            return view('admin.houses.notAuth');
        };

        // I check if the house belongs to the user and if he does not have a sponsorship
        $lastSponsorEnd = $house->sponsors()->latest('sponsor_end')->orderBy("house_id", "DESC")->first();

        // Create current date
        $currentDate = Carbon::now();
        // Check if the last sponsor date is > of the current date
        if ($lastSponsorEnd && $lastSponsorEnd->pivot->sponsor_end > $currentDate) {
            return view('admin.houses.notAuth');
        }

        return view('admin.houses.payment', compact('house', "sponsor"));
    }


    public function payment(Request $request, House $house, Sponsor $sponsor)
    {
        // Control if the log user is same of the house user
        $user = Auth::id();

        // Se non è lo stesso lo mando in notAuth
        if ($house->user_id != $user) {
            return view('admin.houses.notAuth');
        };


        // $payload = $request->input('payload', false);

        // Creo il Gateway
        $gateway = new Gateway([
            'environment' => 'sandbox',
            'merchantId' => "hdxcm33stggcsyyg",
            'publicKey' => "ps5csv88r6xh5rpq",
            'privateKey' => "3b5ccfc79ac341ea2063511741771bcc",
        ]);

        // Mandi i dati al Gateway
        $result = $gateway->transaction()->sale([
            'amount' => $sponsor->price,
            'paymentMethodNonce' => $request->payment_method_nonce,
        ]);

        // Se il la chiamata è andata a buon fine

        if ($result->success) {
            // prendo la data corrente 
            $start_sponsor = new DateTime();
            $end = new DateTime();
            // Aggiundo la durata della sponsorizzazione
            $end_sponsor = $end->add(new DateInterval("PT" . $sponsor->duration . "H"));

            // Creo una nuova sponsorizzazione

            $new_sponsorization = [
                [
                    "sponsor_id" => $sponsor->id,
                    "sponsor_start" => $start_sponsor,
                    "sponsor_end" => $end_sponsor
                ]
            ];

            // Faccio l'attach
            $house->sponsors()->attach($new_sponsorization);
            // Lo rimando alla show
            return to_route("user.houses.show", compact('house'))->with('type', 'payment')->with('message', "pagamento effettuato")->with('alert', 'success');
        } else {
            // return "La transazione è stata rifiutata. Motivo: " . $result->message;
            return to_route('user.houses.show', compact('house'))->with('type', 'payment')->with('message', "La transazione è stata rifiutata. Motivo:"  . $result->message)->with('alert', 'danger');
        }
    }
}
