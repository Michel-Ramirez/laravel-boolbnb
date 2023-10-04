<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\House;
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
            $housesSelect = House::selectRaw("
            *,
            houses.id,
            (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(addresses.latitude)) * COS(RADIANS(addresses.longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(addresses.latitude))))
            AS distance", [$lat, $lng, $lat])
                ->join('addresses', 'houses.address_id', '=', 'addresses.id')
                ->where("houses.is_published", "1")
                ->orderBy('distance', "ASC");

            if (!empty($request->total_rooms)) {
                $housesSelect->where('houses.total_rooms', '>=', $request->total_rooms);
            }

            $housesSelect->get();

            foreach ($housesSelect as $houseSelect) {
                foreach ($responseData["results"] as $result) {
                    // Vedo se id dfelle case esistono nella risposta dell'APi
                    if ($houseSelect->id == $result["address"]["idHouse"]) {
                        // Pusho dentro array
                        $housesList[] = $houseSelect;
                    }
                }
            }
            // Ritorno l'array di case
            return response()->json($housesList);
            // Se la chiamata non va a buon fine
        } else {
            // Ritorno errore 500
            return response()->json("La chiamata API non è andata a buon fine.", 500);
        }
    }
}
