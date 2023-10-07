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
                        <div class="card text-center mb-3 h-100" style="width: 18rem;">
                            <div class="card-body">
                                <img src="{{ Vite::asset('public/img/' . $sponsor->url) }}" alt="ciao" class="img-fluid">
                                <h5 class="card-title">{{ $sponsor->name }}</h5>
                                <p class="card-text">Prezzo: €{{ $sponsor->price }}
                                </p>
                                <a href="{{ route('user.houses.sponsor', ['house' => $house, 'sponsor' => $sponsor]) }}"
                                    class="btn btn-primary">Sponsorizza</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
