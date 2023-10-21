@csrf
<div class="row">
    {{-- Name --}}
    <div class="col-6">
        <div class="mb-3">
            <label for="name" class="form-label">Nome struttura<span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $house->name) }}" placeholder="Inserisci il nome della casa" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- Structure type --}}
    <div class="col-6">
        <div class="mb-3">
            <label for="type" class="form-label">Tipo di struttura<span class="text-danger">*</span></label>
            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="">---</option>
                <option @if (old('type', $house->type) == 'Villa') selected @endif>Villa</option>
                <option @if (old('type', $house->type) == 'Villa a schiera') selected @endif>Villa a schiera</option>
                <option @if (old('type', $house->type) == 'Appartamento') selected @endif>Appartamento</option>
                <option @if (old('type', $house->type) == 'Hotel') selected @endif>Hotel</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- Address --}}
    <div class="col-12">
        <div class="mb-3">
            <label for="address" class="form-label">Indirizzo<span class="text-danger">*</span></label>
            <input type="text" class="form-control rounded-0 rounded-top @error('home_address') is-invalid @enderror"
                id="address" name="home_address"
                value="{{ old('home_address', $house->address->home_address ?? null) }}"
                placeholder="Es. Via Francesco Benaglia, 13, 00153 Roma" AUTOCOMPLETE="Off" required>
            @error('home_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="d-none" id="invalid-address"></div>
            <div class="card rounded-0 d-none" id="container-list-address">
                <ul class="list-group list-group-flush" id="list-address">
                </ul>
            </div>
        </div>
    </div>
    {{-- Lat e Long --}}
    <input class="d-none" type="text" id="latitude" name="latitude">
    <input class="d-none" type="text" id="longitude" name="longitude">
    {{-- Price per night --}}
    <div class="col-6 col-md-4 col-lg">
        <div class="mb-3">
            <label for="price" class="form-label">Prezzo per notte<span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control @error('night_price') is-invalid @enderror"
                id="price" name="night_price" value="{{ old('night_price', $house->night_price) }}" min="0"
                max="9999999" required>
            @error('night_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- Bath numb --}}
    <div class="col-6 col-md-4 col-lg">
        <div class="mb-3">
            <label for="bath" class="form-label">Numero bagni<span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('total_bath') is-invalid @enderror" id="bath"
                name="total_bath" value="{{ old('total_bath', $house->total_bath) }}" min="0" max="255"
                required>
            @error('total_bath')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- Rooms numb --}}
    <div class="col-6 col-md-4 col-lg">
        <div class="mb-3">
            <label for="rooms" class="form-label">Numero camere<span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('total_rooms') is-invalid @enderror" id="rooms"
                name="total_rooms" value="{{ old('total_rooms', $house->total_rooms) }}" min="0" max="255"
                required>
            @error('total_rooms')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- Beds Numb --}}
    <div class="col-6 col-md-4 col-lg">
        <div class="mb-3">
            <label for="beds" class="form-label">Numero posti letto<span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('total_beds') is-invalid @enderror" id="beds"
                name="total_beds" value="{{ old('total_beds', $house->total_beds) }}" min="0" max="255"
                required>
            @error('total_beds')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- MQ --}}
    <div class="col-6 col-md-4 col-lg">
        <div class="mb-3">
            <label for="mq" class="form-label">Metri quadri</label>
            <input type="number" class="form-control @error('mq') is-invalid @enderror" id="mq"
                name="mq" value="{{ old('mq', $house->mq) }}" min="0" max="32000">
            @error('mq')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- Description --}}
    <div class="col-12">
        <div class="mb-3">
            <label for="description" class="form-label">Descrizione della struttura<span
                    class="text-danger">*</span></label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                rows="5" required>{{ old('description', $house->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- Photo --}}
    <form action="{{ route('user.photoes.store') }}" method="POST" id="form-houses" enctype="multipart/form-data"
    data-type="create">
    @csrf
        <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="photo" class="form-label">Inserisci l'immagine della casa</label>
                <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo"
                    name="photoes" multiple>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </form>
    {{-- Preview --}}
    <div class="col-12 col-md-3 col-lg-2">
        <img src="{{ $house->photo ? asset('/storage/' . $house->photo) : 'https://saterdesign.com/cdn/shop/products/property-placeholder_a9ec7710-1f1e-4654-9893-28c34e3b6399_600x.jpg?v=1500393334' }}"
            alt="image-preview" class="img-fluid" id="preview-img">
    </div>
    {{-- Is Publish --}}
    <div class="col-12 mt-3">
        <div class="form-check form-switch">
            <label class="form-check-label" for="published">Pubblica</label>
            <input class="form-check-input @error('is_published') is-invalid @enderror" name="is_published"
                value="1" type="checkbox" role="switch" id="published"
                @if (old('is_published', $house->is_published)) checked @endif>
            @error('is_published')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    {{-- Services --}}
    <h2 class="text-center">Servizi aggiuntivi<span class="text-danger">*</span></h2>
    <div class="col-12 d-flex gap-3 flex-wrap mt-4">
        @foreach ($services as $service)
            <div class="form-check">
                <input class="form-check-input service-checkbox" type="checkbox" value="{{ $service->id }}"
                    id="service-{{ $service->id }}" name="service[]"
                    @if (in_array($service->id, old('service', $servicesIdArray ?? []))) checked @endif>
                <label class="form-check-label" for="service-{{ $service->id }}">
                    {{ $service->name }}
                </label>
            </div>
        @endforeach
    </div>
    @error('service')
        <div class="text-danger mt-2">{{ $message }}</div>
    @enderror
    <div class="d-none" id="invalid-services"></div>
    <div class="mt-4"><span class="text-danger">*</span> Campi obbligatori </div>
    {{-- Button --}}
    <div class="col-12 d-flex justify-content-between my-5">
        <a class="btn btn-secondary" href="{{ route('user.houses.index') }}">Torna Indietro</a>
        <button class="btn btn-success">Aggiungi</button>
    </div>
</div>
