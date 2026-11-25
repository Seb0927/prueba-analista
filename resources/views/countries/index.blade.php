@extends('layouts.app')

@section('title', 'Consultar ciudades por país')

@section('content')
    <section class="card" aria-labelledby="form-title">
        <h2 id="form-title" class="card-title">Seleccione un país</h2>
        <p class="card-hint">Todos los campos son obligatorios.</p>

        <form id="country-form" novalidate>
            <div class="form-group">
                <label for="country">País <span class="required" aria-hidden="true">*</span></label>
                <select id="country" name="country" required aria-describedby="country-error">
                    <option value="">Seleccione un país...</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->Code }}">{{ $country->Name }}</option>
                    @endforeach
                </select>
                <p id="country-error" class="error-message" role="alert" hidden>Debe seleccionar un país.</p>
            </div>
        </form>
    </section>

    <div id="status" class="status" role="status" aria-live="polite" hidden></div>

    <section id="results" class="results" aria-live="polite" hidden>
        <div class="summary">
            <article class="card summary-card summary-card--top">
                <h2 class="card-title">Top 10 · más pobladas</h2>
                <ol id="top-list" class="ranked-list"></ol>
            </article>

            <article class="card summary-card summary-card--bottom">
                <h2 class="card-title">Bottom 10 · menos pobladas</h2>
                <ol id="bottom-list" class="ranked-list"></ol>
            </article>
        </div>

        <article class="card">
            <div class="table-header">
                <h2 id="table-title" class="card-title">Todas las ciudades</h2>
                <span id="city-count" class="badge">0 ciudades</span>
            </div>
            <div class="table-responsive">
                <table class="city-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ciudad</th>
                            <th scope="col">Región / Distrito</th>
                            <th scope="col" class="numeric">Población</th>
                        </tr>
                    </thead>
                    <tbody id="city-table-body"></tbody>
                </table>
            </div>
        </article>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection
