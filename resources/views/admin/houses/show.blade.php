@extends('layouts.app')

@section('styles')
    @vite(['resources/scss/show.scss'])
@endsection
@section('content')
    <div class=" mb-3 bg-white">
        <div class="house-detail container my-5 d-flex justify-content-between align-items-center"
            v-for="house in  houseData " key="$house->id">
            <h1 class="my-5">{{ $house->type }} | {{ $house->name }}</h1>
            <div class="text-end">
                @if ($house->is_published)
                    <h5 class="badge p-3 fs-6" style="background-color: #24dd85">L'appartamento è Visibile</h5>
                @else
                    <h5 class="badge text-bg-danger p-3 fs-6">L'appartamento non è Visibile</h5>
                @endif
            </div>
        </div>
        <div class=" container">
            <div class="carousel">
                <div id="prev">
                    <i class="fa-solid fa-chevron-left"></i>
                </div>
                <div class='gallery'>
                    {{-- CAROUSEL --}}
                </div>
                <div id="next">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-xl-8 content-container">
                    <div>
                        <h5>{{ $house->address->home_address }}</h5>
                    </div>
                    <div>
                        <span><strong>Stanze:</strong> {{ $house->total_rooms }}</span> |
                        <span><strong>Letti:</strong> {{ $house->total_beds }}</span> |
                        <span><strong>Bagni:</strong> {{ $house->total_bath }}</span>
                        <p class="text-end"><strong>{{ $house->night_price }}€</strong>/Notte</p>
                    </div>
                    <div class="house-description" id="houseId" data-house-id="{{ $house->id }}">
                        <p>
                            {{ $house->description }}
                        </p>
                    </div>
                    <div class="house-composition pb-5">
                        <h5 class="py-5">Composizione dell'alloggio</h5>
                        <div class="row">
                            <div class="col-12 wrapper-composition-cards ">
                                <div class="card">
                                    <i class="fa-solid fa-house fa-xl my-3" style="color: #24bb83"></i>
                                    <div class="card-title">
                                        <p>
                                            <strong>Alloggio</strong>
                                        </p>
                                        <p class="text-center"><strong>{{ $house->mq }} mq</strong></p>
                                    </div>
                                </div>
                                <div class="card ">
                                    <i class="fa-solid fa-bed fa-xl my-3" style="color: #24bb83"></i>
                                    <div class="card-title">
                                        <p>
                                            <strong>Camere da letto</strong>
                                        </p>
                                        <p class="text-center"><strong>{{ $house->total_rooms }}</strong></p>
                                    </div>
                                </div>
                                <div class="card ">
                                    <i class="fa-solid fa-mattress-pillow fa-rotate-90 fa-xl my-3"
                                        style="color: #24bb83"></i>
                                    <div class="card-title">
                                        <p>
                                            <strong>Letti</strong>
                                        </p>
                                        <p class="text-center"><strong>{{ $house->total_beds }}</strong></p>
                                    </div>
                                </div>
                                <div class="card ">
                                    <i class="fa-solid fa-toilet fa-xl my-3" style="color: #24bb83"></i>
                                    <div class="card-title">
                                        <p>
                                            <strong>Bagni</strong>
                                        </p>
                                        <p class="text-center"><strong>{{ $house->total_bath }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="house-services py-5">
                        <h5 class="pb-4">Servizi dell'alloggio</h5>
                        <div class="row">
                            <div class="col-12 wrapper-house-services-card">
                                @foreach ($house->services as $service)
                                    <div class="card">
                                        <i class="{{ $service->icon }}" class="fa-xl my-3" style="color: #24bb83"></i>
                                        <p>
                                            <strong>{{ $service->name }}</strong>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 sponsor mt-5">
                    <div class="card">
                        @if (!$sponsorEndDate)
                            <div class="card-title">
                                <figure>
                                    <img src="{{ Vite::asset('/public/img/9d23f024-bb5b-4307-b100-c1477e998fe5-removebg-preview.png') }}"
                                        alt="">
                                    <h4 class="text-center text-bronze">Bronze</h4>
                                </figure>
                                <figure>
                                    <img src="{{ Vite::asset('/public/img/5a22a130-4d86-4dc3-b902-99a3fa792571-removebg-preview.png') }}"
                                        alt="">
                                    <h4 class="text-center text-silver">Silver</h4>
                                </figure>
                                <figure>
                                    <img src="{{ Vite::asset('/public/img/c65c1df1-853e-4c3c-bab6-a0cc4d7b0ac8-removebg-preview.png') }}"
                                        alt="">
                                    <h4 class="text-center text-gold">Gold</h4>
                                </figure>
                            </div>
                            <div class="card-body text-center">
                                <h2 class="my-3 pb-3 fw-bold">Sponsorizza la tua casa</h2>
                                <p>Scegli uno dei nostri piani promozionali per massimizzare la visibilità del tuo annuncio!
                                    Mettendo in evidenza il tuo annuncio, raggiungerai un pubblico più ampio e otterrai
                                    vantaggi
                                    esclusivi. Incrementa la visibilità e l'efficacia della tua promozione scegliendo uno
                                    dei
                                    nostri piani oggi stesso.
                                </p>
                                <h2 class="mb-5">Cosa aspetti?</h2>
                                @if (!$house->is_published)
                                    <div class="alert alert-danger ">
                                        <h5 class="fw-bold">ATTENZIONE</h5>
                                        <small>Per poter sposorizzare devi rendere il tuo annuncio visibile</small>
                                    </div>
                                @else
                                    <div class="btn-sponsor my-4">
                                        <a href="{{ route('user.houses.sponsors', $house) }}"
                                            class="btn-custom">Sponsorizza</a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="card-title-sponsor">
                                <i class="fa-solid fa-medal" style="color: #24dd85;"></i>
                                <h2 class="text-cente fw-bold p-2 my-5">Annuncio sposorizzato</h2>
                                <p class="fw-bold"> Scadenza: {{ $sponsorEndDate }}</p>
                                <p class="p-4">Hai investito nella sponsorizzazione di questo annuncio, il che
                                    significa
                                    che stai
                                    facendo sul serio! Continua a monitorare attentamente i messaggi in arrivo, poiché la
                                    tua probabilità di essere contattato è ora notevolmente aumentata. Il tuo impegno si
                                    tradurrà in una maggiore interazione e opportunità, quindi resta pronto a rispondere
                                    alle richieste dei potenziali interessati.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="container">

            <h2 class="text-center mt-5">Statistiche</h2>
            <select class="form-select w-50 mt-5" id="period-statistic">
                <option value="1">Primo Semestre (Gen-Giu)</option>
                <option value="2">Seconso Semestre (Lug-Dic)</option>
                <option value="3">Annuale</option>
            </select>
            <div class="container container-chart my-5">
                <canvas id="myChart"></canvas>
            </div>
            </li>
            <li class=" list-group-item">
                <div class="d-flex justify-content-between mt-3">
                    <a class="btn btn-secondary" href="{{ route('user.houses.index') }}">Torna Indietro</a>
                </div>
            </li>
            </ul>
        </div>
        <button class="btn btn-primary btn-message" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <i class="fa-solid fa-comment fa-3x"></i>
        </button>
        <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasExample"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title p-50" id="offcanvasExampleLabel">Utenti interessati alla casa:
                    {{ $house->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ">
                @forelse($house->messages as $message)
                    <div class="card mt-4 ">
                        <div class="my-offcanvas-body d-flex justify-content-between">
                            <span>{{ $message->email }}</span>
                            <span>{{ $message->created_at }}</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $message->name }}</h5>
                            <p class="card-text">{{ $message->message }}</p>
                        </div>
                    </div>
                @empty
                    <h4>Nessun messaggio trovato</h4>
                @endforelse
            </div>
        </div>
    </div>
    <script>
        //********* CREO UN CAROSELLO DI IMG *************//

        // richiamo l'oggetto con il metodo json per renderlo leggibile su JS
        const photos = {!! json_encode($house->photos) !!};

        // creao nuovo array con solo gli url delle immagini
        const images = photos.map(function(photo) {
            return photo.img;
        })

        const innerGallery = document.querySelector('.gallery');
        const prevBtn = document.getElementById('prev');
        const nextBtn = document.getElementById('next');

        let imgElement = '';
        const basiUrl = 'http://127.0.0.1:8000/storage/';
        const placeHolder =
            'https://saterdesign.com/cdn/shop/products/property-placeholder_a9ec7710-1f1e-4654-9893-28c34e3b6399_600x.jpg?v=1500393334';

        if (!images.length) {
            imgElement += `<img src = "${placeHolder}" >`;
            prevBtn.classList.add('invisible');
            nextBtn.classList.add('invisible');
        }
        for (let i = 0; i < images.length; i++) {
            imgUrl = basiUrl + images[i];
            imgElement += `<img src = "${imgUrl}">`;
        }

        innerGallery.innerHTML = imgElement;

        const imagesView = document.querySelectorAll('.gallery img');

        let currrentImg = 0;
        imagesView[currrentImg].classList.add('d-block', 'img-fluid');


        nextBtn.addEventListener('click', function() {
            imagesView[currrentImg].classList.remove('d-block')

            currrentImg++;

            if (currrentImg === images.length) {
                currrentImg = 0;
            }

            imagesView[currrentImg].classList.add('d-block', 'img-fluid');
        });

        prevBtn.addEventListener('click', function() {
            imagesView[currrentImg].classList.remove('d-block');

            currrentImg--;

            if (currrentImg < 0) {
                currrentImg = images.length - 1;
            }

            imagesView[currrentImg].classList.add('d-block');
        })
    </script>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/scriptGraph.js'])
@endsection
