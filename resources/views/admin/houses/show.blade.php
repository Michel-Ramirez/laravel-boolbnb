@extends('layouts.app')

@section('content')
    <div class="card-header text-center mb-3 bg-white">
        <h1>{{ $house->name }}</h1>
    </div>
    <div class="card-body container">
        <div class="card mb-3">
            <img src="{{ $house->photo ? asset('storage/' . $house->photo) : 'https://saterdesign.com/cdn/shop/products/property-placeholder_a9ec7710-1f1e-4654-9893-28c34e3b6399_600x.jpg?v=1500393334' }}"
                class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title text-center">{{ $house->description }} Lorem ipsum dolor sit amet, consectetur
                    adipisicing elit. Dolore rem similique explicabo amet consectetur quaerat fugit vitae. Corrupti enim,
                    consequatur, molestias quas quidem animi, perspiciatis incidunt at ratione exercitationem quos?</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item ">
                        <address class="text-end">{{ $house->type }}<br>
                            {{ $house->address->home_address }}</address>
                        <h4>Prezzo per Notte: {{ $house->night_price }}€</h4>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <div class=" col-5 col-md-1">
                                <p>Bagni:</p>
                                <p>Camere:</p>
                                <p>Letti:</p>
                                <p>Mq:</p>
                            </div>
                            <div class="col-5 col-md-1">
                                <p>{{ $house->total_bath }} <i class="fa-solid fa-toilet ps-2"></i></p>
                                <p>{{ $house->total_rooms }} <i class="fa-brands fa-microsoft ps-2"></i></p>
                                <p>{{ $house->total_beds }} <i class="fa-solid fa-bed ps-2"></i></p>
                                <p>{{ $house->mq }}mq <i class="fa-solid fa-square ps-2"></i></p>

                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">Servizi:
                        <div class="d-lex justify-cotent-around">
                            @foreach ($house->services as $service)
                                <div class="">
                                    <i class="{{ $service->icon }}"></i>
                                    <span>{{ $service->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </li>
                    <li class="list-group-item">La sponsorizzazione scade: {{ $sponsorEndDate }}
                        <div class="text-end">
                            @if ($house->is_published)
                                <h5>L'appartamento è Pubblicato</h5>
                            @else
                                <h5 class="text-danger">L'appartamento non è Pubblicato</h5>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    @endsection