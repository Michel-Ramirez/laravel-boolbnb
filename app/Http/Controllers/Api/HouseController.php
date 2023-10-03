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
        $houses = House::with("address")->where("is_published", "1")->get();
        $num = (int)$request->distance;
        $housesList = [];
        foreach ($houses as $house) {
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
            $housesList[] = $houseList;
        };
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

        $client = Http::withOptions([
            'verify' => false,
        ]);

        $response = $client->post(
            "https://api.tomtom.com/search/2/geometryFilter.json?key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe",
            $data
        );

        if ($response->successful()) {
            // La chiamata API è andata a buon fine.
            $responseData = $response->json();
            $housesList = [];
            foreach ($responseData["results"] as $result) {
                $house = House::with("address", "sponsors")->where("is_published", "1")->where("id", $result["address"]["idHouse"])->first();
                $housesList[] = $house;
            }
            return response()->json($housesList);
            // Puoi elaborare la risposta qui.
        } else {
            return response()->json("La chiamata API non è andata a buon fine.", 500);
        }
    }
}
