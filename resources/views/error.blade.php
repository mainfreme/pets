@extends('templates.app')

@section('title', 'Błąd aplikacji')

@section('content')

    <p><strong>Wiadomość:</strong> {{ $exception->getMessage() }}</p>
    <p><strong>Plik:</strong> {{ $exception->getFile() }}</p>
    <p><strong>Linia:</strong> {{ $exception->getLine() }}</p>
    <pre>
        {{ $exception->getTraceAsString() }}
    </pre>

@endsection
