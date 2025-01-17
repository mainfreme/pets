@php
    use App\Enums\Status;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista danych</title>
</head>
<body>
    <header>
        <h1 class="mb-4">@yield('title')</h1>
    </header>

    <nav>
        <ul>
            <li><a href="{{ route('pets.index', ['status' => Status::AVAILABLE->value]) }}">Lista zwierząt dostępnych</a></li>
            <li><a href="{{ route('pets.index', ['status' => Status::PENDING->value]) }}">Lista zwierząt oczekujących</a></li>
            <li><a href="{{ route('pets.index', ['status' => Status::SOLD->value]) }}">Lista zwierząt sprzedanych</a></li>
        </ul>
    </nav>

    <main>
        <div class="container mt-5">
        @yield('content')
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Moja aplikacja. Wszelkie prawa zastrzeżone.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
