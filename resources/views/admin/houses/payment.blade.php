@extends('layouts.app')
@section('styles')
    @vite(['resources/scss/payment.scss'])
@endsection

@section('content')
    <div class="container-fluid  h-100">
        <div class="row h-100 ">
            <div class="col-12 col-md-6 col-left">
                <div class="container">
                    <div class="row ">
                        <div class="col-12 pt-5  ">
                            <a href="{{ route('user.houses.index') }}" class="fs-5 text-decoration-none back-btn">
                                <i class="fa-solid fa-arrow-left"></i> Annulla Pagamento</a>
                        </div>
                        <div class="col-12  ">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="row  justify-content-center">
                                        <div class="col-12 col-xl-6">
                                            <div class="card mb-3 ">
                                                <div class="card-body">
                                                    <img src="{{ Vite::asset('public/img/' . $sponsor->url) }}"
                                                        alt="ciao" class="img-fluid">
                                                    <h3 class="card-title text-center">{{ $sponsor->name }}</h3>
                                                    <div>
                                                        <h5 class="text-success text-center">Benefici</h5>
                                                        <ul>
                                                            <li class="mt-2">La tua casa verrà inseriti nella
                                                                homepage</li>
                                                            <li class="mt-2">La tua casa apparirà in cima alle
                                                                case non sponsorizzate nella pagina di ricerca
                                                            </li>
                                                            @if ($sponsor->price == '2.99')
                                                                <li class="mt-2"> Durata Sponsorizzazione :
                                                                    24h</li>
                                                            @endif
                                                            @if ($sponsor->price == '5.99')
                                                                <li class="mt-2"> Durata Sponsorizzazione :
                                                                    72h</li>
                                                            @endif
                                                            @if ($sponsor->price == '9.99')
                                                                <li class="mt-2"> Durata Sponsorizzazione :
                                                                    144h</li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <p class="card-text text-center fs-5"><strong>Prezzo:
                                                            €{{ $sponsor->price }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="container h-100">
                    <div class="row align-items-center h-100">
                        <div class="col-12 col-lg-8">
                            <h1>Inserisci i dati di pagamento</h1>
                            <form id="payment-form"
                                action="{{ route('user.houses.payment', ['house' => $house, 'sponsor' => $sponsor]) }}"
                                method="post" class="pt-5">
                                @csrf
                                <div class="mb-3">
                                    <label for="card-name" class="form-label">Nome e Cognome</label>
                                    <div id="card-name" class="form-control"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="card-number" class="form-label">Numero carta</label>
                                    <div id="card-number" class="form-control"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="card-expiration" class="form-label">Scadenza Carta</label>
                                    <div id="card-expiration" class="form-control"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="cvv" class="form-label">Cvv</label>
                                    <div id="cvv" class="form-control"></div>
                                </div>
                                <input type="hidden" id="nonce" name="payment_method_nonce" />
                                <div class="d-flex justify-content-end gap-2">
                                    <input type="submit" value="Paga" class="btn-custom" disabled />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.braintreegateway.com/web/3.97.2/js/client.min.js"></script>
    <script src="https://js.braintreegateway.com/web/3.97.2/js/hosted-fields.min.js"></script>
    @vite(['resources/js/payment.js'])
@endsection
