<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Aggiungi Casa</title>
  @vite(['resources/js/app.js'])
</head>

<body>
  <div class="container">
    <h1 class="my-5 text-center">Inserisci i campi richiesti</h1>
    <form action="{{ route('user.houses.store') }}" method="POST" id="form-houses" enctype="multipart/form-data">
      @include('includes.form')
    </form>
  </div>
</body>

</html>
