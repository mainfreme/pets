@extends('templates.app')

@section('title', 'Lista zwierząt')

@section('content')
    <form action="{{ route('pets.uploadImage', ['petId' => $pet['id']]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file">Wybierz zdjęcie:</label>
            <input type="file" name="file" id="file" required>
        </div>
        <div>
            <label for="additionalMetadata">Additional Metadata (optional):</label>
            <input type="text" name="additionalMetadata" id="additionalMetadata">
        </div>
        <div>
            <button type="submit">Upload</button>
        </div>
    </form>
@endsection
