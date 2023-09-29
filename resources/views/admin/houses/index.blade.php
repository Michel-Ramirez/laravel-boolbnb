@extends('layouts.app')

@section('content')
  <div class="container mt-5">
    <div class="d-flex justify-content-end mb-3"><a href="{{ route('user.houses.create') }}" class="btn btn-primary">Aggiungi
        Casa</a></div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Nome Casa</th>
          <th scope="col">Tipo</th>
          <th scope="col">Prezzo per notte</th>
          <th scope="col">Metri quadri</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($houses as $house)
          <tr>
            <th scope="row">{{ $house->name }}</th>
            <td>{{ $house->type }}</td>
            <td>{{ $house->night_price }}</td>
            <td>
              @if ($house->mq)
                {{ $house->mq }} mq
              @else
                ND
              @endif
            </td>
            <td>
              <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('user.houses.edit', $house) }}" class="btn btn-warning">Modifica</a>
                <a href="{{ route('user.houses.show', $house) }}" class="btn btn-info">Dettaglio</a>
                <form action="{{ route('user.houses.destroy', $house) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger">Cancella</button>
                </form>
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
@endsection
