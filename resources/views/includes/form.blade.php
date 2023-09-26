<div class="container">
  <h1 class="my-5 text-center">Inserisci i campi richiesti</h1>
  <form action="{{ route('user.houses.store') }}" method="POST">
    @csrf
    <div class="row">
      <div class="col-6">
        <div class="mb-3">
          <label for="name" class="form-label">Nome struttura</label>
          <input type="text" class="form-control" id="name" name="name"
            placeholder="Inserisci il nome della casa">
        </div>
      </div>
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
      <div class="col">
        <div class="mb-3">
          <label for="price" class="form-label">Prezzo per notte</label>
          <input type="number" class="form-control" id="price" name="night_price">
        </div>
      </div>
      <div class="col">
        <div class="mb-3">
          <label for="bath" class="form-label">Numero bagni</label>
          <input type="number" class="form-control" id="bath" name="total_bath">
        </div>
      </div>
      <div class="col">
        <div class="mb-3">
          <label for="rooms" class="form-label">Numero camere</label>
          <input type="number" class="form-control" id="rooms" name="total_rooms">
        </div>
      </div>
      <div class="col">
        <div class="mb-3">
          <label for="beds" class="form-label">Numero posti letto</label>
          <input type="number" class="form-control" id="beds" name="total_beds">
        </div>
      </div>
      <div class="col">
        <div class="mb-3">
          <label for="mq" class="form-label">Metri quadri</label>
          <input type="number" class="form-control" id="mq" name="total_mq">
        </div>
      </div>
      <div class="col-12">
        <div class="mb-3">
          <label for="description" class="form-label">Descrizione della struttura</label>
          <textarea class="form-control" id="description" rows="5"></textarea>
        </div>
      </div>
      <div class="col-6">
        <div class="mb-3">
          <label for="photo" class="form-label">Inserisci l'immagine della casa</label>
          <input class="form-control" type="file" id="photo" name="photo">
        </div>
      </div>
      <div class="col-12 mt-3">
        <div class="form-check form-switch">
          <label class="form-check-label" for="published">Pubblica</label>
          <input class="form-check-input" type="checkbox" role="switch" id="published">
        </div>
      </div>
      <h2 class="text-center">Servizi aggiuntivi</h2>
      <div class="col-12 d-flex gap-3 flex-wrap mt-4">
        @foreach ($services as $service)
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $service->id }}" id="service-{{ $service->id }}"
              name="service[]">
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
