@extends('templates.app')

@section('title', 'Szczegóły zwierzęcia')

@section('content')
    <a href="{{route('pets.index')}}">Wróć</a>

    <div class="card">
        <a href="{{ route('pets.edit', ['petId'=> $data['id']]) }}">Edytuj</a>
        <div class="card-body">
            <h2 class="card-title">{{ $data['name'] }}</h2>

            <p><strong>Kategoria:</strong>
                @if(isset($data['category']))
                    {{ $data['category']['name'] }}
                @endif
            </p>

            <p><strong>Status:</strong> {{ $data['status'] }}</p>

            <h5>Tagi:</h5>
            <ul>
                @foreach ($data['tags'] as $tag)
                    <li>{{ $tag['name'] }}</li>
                @endforeach
            </ul>

            <h5>Zdjęcia:</h5>
            <ul>
                @foreach ($data['photoUrls'] as $url)
                    @if (app(\App\Services\PetService::class)->urlFileExists($url))
                        <li>
                            <img src="{{ $url }}" style="width: 50px; max-width: 50px; max-height: 50px;"/>
                            <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                        </li>
                    @endif
                @endforeach
                <li>
                    <a href="{{ route('pets.images', ['petId' => $data['id']]) }}">Dodaj zdjęcie</a>
                </li>
            </ul>
        </div>
    </div>
@endsection

