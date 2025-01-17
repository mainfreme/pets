@extends('templates.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Lista zwierząt')

@section('content')
    <table>
        <tr>
            <td>Zdjecie</td>
            <td>Status</td>
            <td>Nazwa</td>
            <td>Tagi</td>
            <td colspan="2"></td>
        </tr>
        <tbody>


        @forelse ($data as $item)
            @if (array_key_exists('name', $item))
                <tr>
                    <td>
                        @forelse ($item['photoUrls'] as $photoUrl)
                            @if (app(\App\Services\PetService::class)->urlFileExists($photoUrl))
                                <img src="{{ $photoUrl }}" style="width: 50px; max-width: 50px; max-height: 50px;"/>
                            @endif
                        @empty

                        @endforelse
                    </td>
                    <td>{{ $item['status'] }}</td>
                    <td>{{ Str::limit($item['name'], 50, '...') }}</td>
                    <td>
                        @forelse ($item['tags'] as $tag)
                            <span>{{ $tag['name'] }}</span>
                        @empty
                            ---
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('pets.show', ['petId' => $item['id']]) }}">Zobacz</a>
                    </td>
                    <td>
                        <a href="{{ route('pets.edit', ['petId' => $item['id']]) }}">Edytuj</a>
                    </td>
                    <td>
                        <form action="{{ route('pets.delete', ['petId' => $item['id']]) }}" method="POST"
                              style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Usuń</button>
                        </form>
                    </td>
                </tr>
            @endif
        @empty
            <tr>
                <td colspan="5">Brak dostępnych danych.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection
