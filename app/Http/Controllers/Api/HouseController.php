<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\House;
use DateTime;
use Illuminate\Http\Request;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // Prendo tutte le case
        $houses = House::with("services", "address", "views", "sponsors")->get();

        // Creo una un array
        $housesSponsors = [];

        // Giro su tutte le case
        foreach ($houses as $house) {

            // Controllo se la casa ha una sponsorizzazione
            if (count($house->sponsors)) {

                //Giro sulle sponsorizzazioni
                foreach ($house->sponsors as $sponsor) {

                    // Controllo la DATA e ORA attuale
                    $currentDate = new DateTime();

                    // Formatto la data 
                    $formateDate = $currentDate->format("Y-m-d H:i:s");

                    // Controllo se la data di fine sponsorizzazione e maggiore della data corrente
                    if ($sponsor->pivot->sponsor_end > $formateDate) {

                        // Push dentro l'array 
                        $housesSponsors[] = $house;
                    }
                }
            }
        }
        return response()->json($housesSponsors);
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
}
