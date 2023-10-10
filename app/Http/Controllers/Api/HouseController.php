<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\House;
use App\Models\Service;
use App\Models\View;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $houses = House::has('sponsors', '>', 0)->with("views", "services", "address")->orderBy('created_at', "DESC")->where("is_published", true)
            ->whereHas('sponsors', function ($query) {
                $query->where('sponsor_end', '>', now());
            })
            ->with(['sponsors' => function ($query) {
                $query->where('sponsor_end', '>', now());
            }])
            ->paginate(6);

        // Giro su tutte le case
        foreach ($houses as $house) {
            if ($house->photo) {
                $house["photo"] = url("storage/" . $house->photo);
            }
        }
        return response()->json($houses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $house = House::with("address", "user", "services")->find($id);

        // Giro su tutte le case
        if ($house->photo) {
            $house["photo"] = url("storage/" . $house->photo);
        }
        return response()->json($house);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Api for Searchbar
     */
    public function search(Request $request)
    {
        // Chiamo tutte le case che sono visibili
        $houses = House::with("address")->where("is_published", "1")->get();
        // Prendo il raggio
        $num = (int)$request->distance;
        // Creo un array
        $housesList = [];
        foreach ($houses as $house) {
            // Creo una casa da inserire nel body
            $houseList =
                [
                    "address" => [
                        "freeformAddress" => $house->name,
                        "idHouse" => $house->id,
                    ],
                    "position" => [
                        "lat" => $house->address->latitude,
                        "lon" => $house->address->longitude,
                    ]
                ];
            // Pusho dentro l'array
            $housesList[] = $houseList;
        };
        //   Creo il body della chiamata
        $data =
            [
                "geometryList" => [
                    [
                        "position" => "$request->lat , $request->long",
                        "radius" => $num,
                        "type" => "CIRCLE",
                    ]
                ],
                "poiList" => $housesList,
            ];
        // Tolgo la verifica
        $client = Http::withOptions([
            'verify' => false,
        ]);
        // Chiamo l'API
        $response = $client->post(
            "https://api.tomtom.com/search/2/geometryFilter.json?key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe",
            $data
        );
        // Se la chiamata va a buon fine
        if ($response->successful()) {
            // Prendo la risposta dell API
            $responseData = $response->json();
            $housesList = [];
            // Preno lar e long dalla richiesta
            $lat = $request->lat;
            $lng = $request->long;
            // Creo la query
            $housesResult = House::selectRaw("
            *,
            houses.id,
            (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(addresses.latitude)) * COS(RADIANS(addresses.longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(addresses.latitude))))
            AS distance", [$lat, $lng, $lat])
                ->join('addresses', 'houses.address_id', '=', 'addresses.id')
                ->with("services", "sponsors")
                ->where("houses.is_published", "1")
                ->orderBy('distance', "ASC");

            // Guardo se c'è un filtro per le stanze
            if (!empty($request->total_rooms)) {
                $housesResult->where('houses.total_rooms', '>=', $request->total_rooms);
            }
            // Guardo se c'è un filtro per i posti letto
            if (!empty($request->total_beds)) {
                $housesResult->where('houses.total_beds', '>=', $request->total_beds);
            }


            // $userFilterServices = explode(',', $request->service);

            // Trasformo la stringa in array
            $userFilterServices = json_decode($request->service);
            // Prendo le case
            $housesSelect = $housesResult->get();
            // Creo un Array
            $houseFilterServices = [];

            foreach ($housesSelect as $houseSelect) {
                // Prendo tutti gli id dei servizzi della casa
                $idsArray = $houseSelect->services->pluck("id")->toArray();
                // Creo un array con all'interno gli id dei servizzi che non sono presenti nella casa
                $missingServices = array_diff($userFilterServices, $idsArray);
                // Controllo che MissingServices sia vuoto
                if (empty($missingServices)) {
                    // Pusho dentro l'array
                    $houseFilterServices[] = $houseSelect;
                }
            }

            foreach ($houseFilterServices as $houseSelect) {
                foreach ($responseData["results"] as $result) {
                    // Vedo se id delle case esistono nella risposta dell'APi
                    if ($houseSelect->id == $result["address"]["idHouse"]) {
                        // Pusho dentro array
                        $housesList[] = $houseSelect;
                    }
                }
            }
            // Ritorno l'array di case
            // Giro su tutte le case
            foreach ($housesList as $house) {
                if ($house->photo) {
                    $house["photo"] = url("storage/" . $house->photo);
                }
            }
            return response()->json($housesList);
            // Se la chiamata non va a buon fine
        } else {
            // Ritorno errore 500
            return response()->json("La chiamata API non è andata a buon fine.", 500);
        }
    }
    // Views
    public function views(Request $request)
    {
        //
        $newViews = new View();
        $newViews->house_id = $request->house_id;
        $newViews->ip_viewer = $request->ip_viewer;
        $newViews->save();
        return response(null, 204);
    }

    public function showViews(Request $request)
    {
        //
        $house = House::find($request->house_id);
        $count = count($house->views);
        return response()->json($count);
    }
}
