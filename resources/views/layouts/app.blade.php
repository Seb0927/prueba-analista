<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulta las ciudades y la población de cualquier país usando la base de datos World">
    <title>@yield('title', 'Explorador de ciudades')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <p class="eyebrow">Infodec · Prueba técnica Nivel 1</p>
            <h1 class="site-title">Explorador de ciudades</h1>
            <p class="site-subtitle">Descubre las ciudades más y menos pobladas de cada país</p>
        </div>
    </header>

    <main class="container main-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>Datos: base de datos <strong>World</strong> de MySQL &middot; Laravel + PHP 8.4 + Docker</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
