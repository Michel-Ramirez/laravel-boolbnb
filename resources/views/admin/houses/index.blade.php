@extends('layouts.app')

@section('styles')
  @vite(['resources/scss/house-index.scss'])
@endsection

@section('content')
  <div class="container mt-5">
    <div class="d-flex justify-content-end mb-4"><a href="{{ route('user.houses.create') }}" class="btn btn-primary"><i
          class="fa-solid fa-house me-2"></i>Aggiungi
        Casa</a></div>
    @forelse($houses as $house)
      <div class="card mb-3">
        <div class="row g-0">
          <div class="col-md-4 col-img">
            <img
              src="{{ $house->photo ? asset('storage/' . $house->photo) : 'https://saterdesign.com/cdn/shop/products/property-placeholder_a9ec7710-1f1e-4654-9893-28c34e3b6399_600x.jpg?v=1500393334' }}"
              class="rounded-start  img-fluid">
          </div>
          <div class="col-md-8">
            <div class="card-body pb-0">
              <div class="d-flex justify-content-between">
                <h5 class="card-title">{{ $house->name }}</h5>
                <div>
                  <h5>Prezzo per Notte: {{ $house->night_price }}€</h5>
                  <p><small>{{ $house->address->home_address }}</small></p>
                </div>
              </div>
              <p class="card-text">
              <h6>Servizi</h6>
              @foreach ($house->services as $service)
                <small><span><i class="{{ $service->icon }}"></i> {{ $service->name }}</span></small>
                @if (!$loop->last)
                  <small><span>|</span></small>
                @endif
              @endforeach
              </p>
              <div>
                <h6>Informazioni</h6>
                <p class="card-text"><small class="text-body-secondary">{{ $house->type }} |
                    bagni: {{ $house->total_bath }} | stanze: {{ $house->total_rooms }} |
                    letti: {{ $house->total_beds }}</small></p>
              </div>
              <div class="d-flex align-items-center justify-content-between mt-2 mb-1">
                <form action="{{ route('user.houses.publish', $house) }}" id="is-published-form" method="POST">
                  @csrf
                  @method('PATCH')
                  <div class="form-check form-switch ms-2">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published-{{ $house->id }}"
                      onclick="form.submit()" name="is_published" @if ($house->is_published == true) checked @endif>
                    <label class="form-check-label" for="is_published-{{ $house->id }}">
                      {{ $house->is_published ? 'Visibile' : 'Non Visibile' }}
                    </label>
                  </div>
                </form>
                <div class="d-flex justify-content-end align-items-center gap-2">
                  <a href="{{ route('user.houses.edit', $house) }}" class="btn btn-warning">Modifica</a>
                  <a href="{{ route('user.houses.show', $house) }}" class="btn btn-info">Dettaglio</a>
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#modal-{{ $house->id }}">
                    Elimina
                  </button>
                  @include('includes.trash.modal')
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @empty
      <h1>Aggiungi la tua prima casa</h1>
    @endforelse
    <div class="d-flex justify-content-end my-5">
      @if ($houses->hasPages())
        {{ $houses->links() }}
      @endif
    </div>
  </div>


@endsection
