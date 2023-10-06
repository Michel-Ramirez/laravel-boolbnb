@extends('layouts.app')
@section('styles')
    @vite(['resources/scss/payment.scss'])
@endsection

@section('content')
    <div class="container-fluid  h-100">
        <div class="row h-100 ">
            <div class="col-6 col-left h-100">
                <div class="container">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center pt-5">
                            <a href="{{ route('user.houses.index') }}" class="btn btn-secondary">Annulla
                                Pagamento</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="container">
                    <div class="row">
                        <div class="col-8">
                            <form id="payment-form"
                                action="{{ route('user.houses.payment', ['house' => $house, 'sponsor' => $sponsor]) }}"
                                method="post" class="p-5">
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
                                    <input type="submit" value="Paga" class="btn btn-primary" disabled />
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
