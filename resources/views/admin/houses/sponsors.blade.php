@extends('layouts.app')

@section('styles')
  @vite(['resources/scss/sponsorsPage.scss'])
@endsection

@section('content')
  <div class="container h-100">
    <div class="d-flex justify-content-center align-items-center h-100">
      <div class="row">
        @foreach ($sponsors as $sponsor)
          <div class="col">
            <div class="card mb-3 h-100" style="width: 18rem;">
              <div class="card-body">
                <img src="{{ Vite::asset('public/img/' . $sponsor->url) }}" alt="ciao" class="img-fluid">
                <h3 class="card-title text-center">{{ $sponsor->name }}</h3>
                <div>
                  <h5 class="text-success text-center">Benefici</h5>
                  <ul>
                    <li class="mt-2">La tua casa verrà inseriti nella homepage</li>
                    <li class="mt-2">La tua casa apparirà in cima alle case non sponsorizzate nella pagina di ricerca
                    </li>
                    @if ($sponsor->price == '2.99')
                      <li class="mt-2"> Durata Sponsorizzazione : 24h</li>
                    @endif
                    @if ($sponsor->price == '5.99')
                      <li class="mt-2"> Durata Sponsorizzazione : 72h</li>
                    @endif
                    @if ($sponsor->price == '9.99')
                      <li class="mt-2"> Durata Sponsorizzazione : 144h</li>
                    @endif
                  </ul>
                </div>
                <p class="card-text text-center fs-5"><strong>Prezzo: €{{ $sponsor->price }}</strong>
                </p>
                <div class="d-flex justify-content-center">
                  <a href="{{ route('user.houses.sponsor', ['house' => $house, 'sponsor' => $sponsor]) }}"
                    class="btn-custom">Sponsorizza</a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection
