@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-end mb-3"><a href="{{ route('user.houses.create') }}"
                class="btn btn-primary">Aggiungi
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
                                <form action="{{ route('user.houses.publish', $house) }}" id="is-published-form"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch ms-2">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="is_published-{{ $house->id }}" onclick="form.submit()"
                                            name="is_published" @if ($house->is_published == true) checked @endif>
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
@endsection
