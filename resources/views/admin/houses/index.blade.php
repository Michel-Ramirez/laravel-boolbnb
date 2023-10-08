@extends('layouts.app')

@section('content')
  <div class="container mt-5">
    <div class="d-flex justify-content-end mb-3"><a href="{{ route('user.houses.create') }}" class="btn btn-primary">Aggiungi
        Casa</a></div>
    <div class="table-responsive">


      <table class="table">
        <thead>
          <tr>
            <th scope="col">Nome Casa</th>
            <th scope="col">Tipo</th>
            <th scope="col">Prezzo per notte</th>
            <th scope="col">Metri quadri</th>
            <th scope="col">Stato</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($houses as $house)
            <tr class="align-middle">
              <th scope="row">{{ $house->name }}</th>
              <td>{{ $house->type }}</td>
              <td>{{ $house->night_price }}€</td>
              <td>
                @if ($house->mq)
                  {{ $house->mq }} mq
                @else
                  ND
                @endif
              </td>
              <td>
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
              </td>
              <td>
                <div class="d-flex justify-content-end align-items-center gap-2">
                  <a href="{{ route('user.houses.edit', $house) }}" class="btn btn-warning">Modifica</a>
                  <a href="{{ route('user.houses.show', $house) }}" class="btn btn-info">Dettaglio</a>
                  <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#modal-{{ $house->id }}">
                    Elimina
                  </button>
                  @include('includes.trash.modal')
                </div>
              </td>
            </tr>
          @empty
            <td colspan="5">
              <h3>Non ci sono case</h3>
            </td>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="container mt-5">
    @foreach ($houses as $house)
      <div class="card mb-3">
        <div class="row g-0">
          <div class="col-md-4">
            <img
              src="{{ $house->photo ? asset('storage/' . $house->photo) : 'https://saterdesign.com/cdn/shop/products/property-placeholder_a9ec7710-1f1e-4654-9893-28c34e3b6399_600x.jpg?v=1500393334' }}"
              class="img-fluid rounded-start">
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <h5 class="card-title">{{ $house->name }}</h5>
                <h5>Prezzo per Notte: {{ $house->night_price }}€</h5>
              </div>
              <p class="card-text">{{ $house->description }}</p>
              <div>
                <h6>Informazioni</h6>
                <p class="card-text"><small class="text-body-secondary">{{ $house->type }} |
                    bagni: {{ $house->total_bath }} | stanze: {{ $house->total_rooms }} |
                    letti: {{ $house->total_beds }}</small></p>
              </div>
              <div class="d-flex align-items-center justify-content-between mt-3">
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
    @endforeach
  </div>
@endsection
