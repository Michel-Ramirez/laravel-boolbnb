<div class="container">
  <h1 class="my-5 text-center">Inserisci i campi richiesti</h1>
  <form action="{{ route('user.houses.store') }}" method="POST" id="form-houses" enctype="multipart/form-data">
    @csrf
    <div class="row">
      {{-- Name --}}
      <div class="col-6">
        <div class="mb-3">
          <label for="name" class="form-label">Nome struttura</label>
          <input type="text" class="form-control" id="name" name="name"
            placeholder="Inserisci il nome della casa">
        </div>
      </div>
      {{-- Structure type --}}
      <div class="col-6">
        <div class="mb-3">
          <label for="type" class="form-label">Tipo di struttura</label>
          <select class="form-select" id="type" name="type">
            <option>Villa</option>
            <option>Villa a schiera</option>
            <option>Appartamento</option>
            <option>Hotel</option>
          </select>
        </div>
      </div>
      {{-- Address --}}
      <div class="col-12">
        <div class="mb-3">
          <label for="address" class="form-label">Indirizzo</label>
          <input type="text" class="form-control" id="address" name="home_address"
            placeholder="Es. Via Francesco Benaglia, 13, 00153 Roma">
        </div>
      </div>
      <input class="d-none" type="text" id="latitude" name="latitude">
      <input class="d-none" type="text" id="longitude" name="longitude">
      {{-- Price per night --}}
      <div class="col">
        <div class="mb-3">
          <label for="price" class="form-label">Prezzo per notte</label>
          <input type="number" step="0.01" class="form-control" id="price" name="night_price">
        </div>
      </div>
      {{-- Bath numb --}}
      <div class="col">
        <div class="mb-3">
          <label for="bath" class="form-label">Numero bagni</label>
          <input type="number" class="form-control" id="bath" name="total_bath">
        </div>
      </div>
      {{-- Rooms numb --}}
      <div class="col">
        <div class="mb-3">
          <label for="rooms" class="form-label">Numero camere</label>
          <input type="number" class="form-control" id="rooms" name="total_rooms">
        </div>
      </div>
      {{-- Beds Numb --}}
      <div class="col">
        <div class="mb-3">
          <label for="beds" class="form-label">Numero posti letto</label>
          <input type="number" class="form-control" id="beds" name="total_beds">
        </div>
      </div>
      {{-- MQ --}}
      <div class="col">
        <div class="mb-3">
          <label for="mq" class="form-label">Metri quadri</label>
          <input type="number" class="form-control" id="mq" name="mq">
        </div>
      </div>
      {{-- Description --}}
      <div class="col-12">
        <div class="mb-3">
          <label for="description" class="form-label">Descrizione della struttura</label>
          <textarea class="form-control" id="description" name="description" rows="5"></textarea>
        </div>
      </div>
      {{-- Photo --}}
      <div class="col-6">
        <div class="mb-3">
          <label for="photo" class="form-label">Inserisci l'immagine della casa</label>
          <input class="form-control" type="file" id="photo" name="photo">
        </div>
      </div>
      {{-- Is Publish --}}
      <div class="col-12 mt-3">
        <div class="form-check form-switch">
          <label class="form-check-label" for="published">Pubblica</label>
          <input class="form-check-input" type="checkbox" role="switch" id="published">
        </div>
      </div>
      {{-- Services --}}
      <h2 class="text-center">Servizi aggiuntivi</h2>
      <div class="col-12 d-flex gap-3 flex-wrap mt-4">
        @foreach ($services as $service)
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $service->id }}"
              id="service-{{ $service->id }}" name="service[]">
            <label class="form-check-label" for="service-{{ $service->id }}">
              {{ $service->name }}
            </label>
          </div>
        @endforeach
      </div>
      <div class="col-12 d-flex justify-content-between mt-5">
        <a class="btn btn-secondary" href="{{ route('user.houses.index') }}">Torna Indietro</a>
        <button class="btn btn-success">Aggiungi</button>
      </div>
    </div>
  </form>
</div>


@vite(['resources/js/scriptAddress.js'])
