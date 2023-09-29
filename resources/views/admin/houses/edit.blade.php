@extends('layouts.app')

@section('content')
  <div class="container">
    <h1 class="my-5 text-center">Modifica i dati della tua casa</h1>
    <form action="{{ route('user.houses.update', $house) }}" method="POST" id="form-houses" enctype="multipart/form-data">
      @method('PUT')
      @include('includes.form')
    </form>
  </div>
@endsection
