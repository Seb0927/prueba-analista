(() => {
    'use strict';

    const form = document.getElementById('country-form');
    const select = document.getElementById('country');
    const selectError = document.getElementById('country-error');
    const statusBox = document.getElementById('status');
    const results = document.getElementById('results');
    const topList = document.getElementById('top-list');
    const bottomList = document.getElementById('bottom-list');
    const tableTitle = document.getElementById('table-title');
    const cityCount = document.getElementById('city-count');
    const tableBody = document.getElementById('city-table-body');

    const formatter = new Intl.NumberFormat('es-CO');

    const endpoint = (code) => `/paises/${encodeURIComponent(code)}/ciudades`;

    function showStatus(message, isError = false) {
        statusBox.textContent = message;
        statusBox.classList.toggle('status--error', isError);
        statusBox.hidden = false;
    }

    function hideStatus() {
        statusBox.hidden = true;
        statusBox.textContent = '';
        statusBox.classList.remove('status--error');
    }

    function makeCell(text, className) {
        const cell = document.createElement('td');
        cell.textContent = text;
        if (className) {
            cell.className = className;
        }
        return cell;
    }

    function renderRankedList(target, cities) {
        target.replaceChildren();

        if (!cities.length) {
            const item = document.createElement('li');
            item.textContent = 'Sin ciudades registradas.';
            target.appendChild(item);
            return;
        }

        cities.forEach((city) => {
            const item = document.createElement('li');

            const name = document.createElement('span');
            name.className = 'city-name';
            name.textContent = city.Name;

            const population = document.createElement('span');
            population.className = 'city-pop';
            population.textContent = ` · ${formatter.format(city.Population)} hab.`;

            item.append(name, population);
            target.appendChild(item);
        });
    }

    function renderTable(cities) {
        tableBody.replaceChildren();

        cities.forEach((city, index) => {
            const row = document.createElement('tr');
            row.append(
                makeCell(String(index + 1), 'rank'),
                makeCell(city.Name),
                makeCell(city.District || '—'),
                makeCell(formatter.format(city.Population), 'numeric')
            );
            tableBody.appendChild(row);
        });
    }

    async function loadCities(code) {
        if (!code) {
            selectError.hidden = false;
            showStatus('Debe seleccionar un país para continuar.', true);
            results.hidden = true;
            select.focus();
            return;
        }

        selectError.hidden = true;
        results.hidden = true;
        showStatus('Consultando ciudades...');

        try {
            const response = await fetch(endpoint(code), {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error(`Respuesta inesperada del servidor (${response.status}).`);
            }

            const data = await response.json();

            renderRankedList(topList, data.top10);
            renderRankedList(bottomList, data.bottom10);
            renderTable(data.cities);

            tableTitle.textContent = `Todas las ciudades de ${data.country.name}`;
            cityCount.textContent = `${formatter.format(data.total)} ciudades`;

            hideStatus();
            results.hidden = false;
        } catch (error) {
            showStatus(`No fue posible consultar las ciudades. ${error.message}`, true);
            results.hidden = true;
        }
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        loadCities(select.value);
    });

    select.addEventListener('change', () => {
        loadCities(select.value);
    });
})();
