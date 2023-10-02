@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-end mb-3"><a href="{{ route('user.houses.index') }}" class="btn btn-primary">Torna
                alle tue case</a></div>
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
                                <form action="{{ route('user.houses.restore', $house->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-success">Ripristina</button>
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
