@extends('templates.app')

@section('title')
    @php
        $method = explode('::', $action)[1];
    @endphp
    {{ $method === 'create' ? 'Dodaj' : 'Edytuj' }} zwierzęcia
@endsection

@section('content')
    <form action="{{ isset($pet) ? route('pets.update', $pet['id']) : route('pets.store') }}" method="POST">
        @csrf
        @if(isset($pet))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label for="name" class="form-label">Nazwa</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name', $pet['name'] ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                @foreach($status as $availableStatus)
                    <option {{ (isset($pet) && $pet['status'] == $availableStatus) ? 'selected' : '' }} value="{{ $availableStatus }}">{{ ucfirst($availableStatus) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="category_name" class="form-label">Nazwa kategorii</label>
            <input type="text" name="category_name" id="category_name"
                   class="form-control">
        </div>
        <div class="mb-3">
            <label for="tags" class="form-label">Tagi</label>
            <div id="tags-container">
                <div class="tag mb-2">
                    @if(isset($pet))
                        @foreach($pet['tags'] as $tags)
                            <input type="text" name="tags[0][name]" class="form-control" value="{{ $tags['name'] }}">
                        @endforeach
                    @else
                        <input type="text" name="tags[0][name]" class="form-control" placeholder="Tag">
                    @endif
                </div>
            </div>
            <button type="button" class="btn btn-secondary mt-2" id="add-tag">Dodaj Tag</button>
        </div>

        <div class="mb-3">
            <label for="photoUrls" class="form-label">Photo URLs</label>
            <div id="photoUrlsWrapper">
                <input type="url" name="photoUrls[]" class="form-control mb-2" placeholder="URL Zdjęcia" value="{{ old('photoUrls.0') }}">
            </div>
            <button type="button" id="addPhotoUrl" class="btn btn-secondary">Dodaj kolejne zdjęcie</button>
        </div>

        <div class="mb-12">
            <button type="submit" class="btn btn-primary">{{ isset($pet) ? 'Zapisz' : 'Dodaj' }}</button>
        </div>
    </form>


    <script>
        document.getElementById('addPhotoUrl').addEventListener('click', function() {
            const wrapper = document.getElementById('photoUrlsWrapper');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'photoUrls[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'URL Zdjęcia';
            wrapper.appendChild(input);
        });

        let tagIndex = 1;
        document.getElementById('add-tag').addEventListener('click', function () {
            const container = document.getElementById('tags-container');
            const div = document.createElement('div');
            div.classList.add('tag', 'mb-2');
            div.innerHTML = `
                <input type="text" name="tags[${tagIndex}][name]" class="form-control" placeholder="Tag">
            `;
            container.appendChild(div);
            tagIndex++;
        });
    </script>

@endsection
