// Función para obtener la lista de países
async function fetchData(url = 'https://countriesnow.space/api/v0.1/countries/') {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('La respuesta de la red no fue correcta.');
        }
        const json = await response.json();
        // La API retorna un objeto con la propiedad "data" que contiene el arreglo de países
        return json.data;
    } catch (error) {
        console.error("Error al obtener los datos:", error);
        return null;
    }
}

// Función auxiliar para limpiar un <select> y agregar una opción por defecto
function clearAndSetDefault(selectElement, defaultText = '--Seleccionar--') {
    selectElement.innerHTML = '';
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = defaultText;
    selectElement.appendChild(defaultOption);
}

// Función para obtener los estados o regiones de un país usando la API (POST)
async function loadStates(countryName) {
    try {
        const response = await fetch("https://countriesnow.space/api/v0.1/countries/states", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ country: countryName })
        });
        if (!response.ok) throw new Error("Error al obtener estados.");
        const result = await response.json();
        return result.data.states;
    } catch (error) {
        console.error("Error al obtener estados:", error);
        return [];
    }
}
/*
// Función para obtener las ciudades de un estado (tratadas como provincias o municipios)
async function loadCities(countryName, stateName) {
    try {
        const response = await fetch("https://countriesnow.space/api/v0.1/countries/state/cities", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ country: countryName, state: stateName })
        });
        if (!response.ok) throw new Error("Error al obtener ciudades.");
        const result = await response.json();
        return result.data;
    } catch (error) {
        console.error("Error al obtener ciudades:", error);
        return [];
    }
}
*/

// Función principal para llenar el <select> de países y configurar los eventos para cargar estados y ciudades
function getCountries(data, selectId, regionSelectId = null, provinceSelectId = null) {
    if (!data || !selectId) return;

    const countrySelect = document.getElementById(selectId);
    if (!countrySelect) return;
    clearAndSetDefault(countrySelect, '--Seleccionar país--');

    // Ordenar y llenar el select de países (la propiedad es "country" en esta API)
    data.sort((a, b) => a.country.localeCompare(b.country));
    data.forEach(country => {
        const option = document.createElement('option');
        option.value = country.country;
        option.textContent = country.country;
        countrySelect.appendChild(option);
    });

    // Si se proporcionó un select para regiones/estados, configurar el evento change
    if (regionSelectId) {
        const regionSelect = document.getElementById(regionSelectId);
        if (!regionSelect) return;
        clearAndSetDefault(regionSelect, '--Seleccionar región/estado--');

        countrySelect.addEventListener('change', async function() {
            const selectedCountry = this.value;
            clearAndSetDefault(regionSelect, '--Seleccionar región/estado--');
            if (provinceSelectId) {
                const provinceSelect = document.getElementById(provinceSelectId);
                if (provinceSelect) clearAndSetDefault(provinceSelect, '--Seleccionar provincia/municipio--');
            }
            if (selectedCountry) {
                const states = await loadStates(selectedCountry);
                if (states && states.length) {
                    states.sort((a, b) => a.name.localeCompare(b.name));
                    states.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.name;
                        option.textContent = state.name;
                        regionSelect.appendChild(option);
                    });
                }
            }
        });

        // Si se proporcionó un select para ciudades/provincias, configurar el evento change en el select de estados
        if (provinceSelectId) {
            const provinceSelect = document.getElementById(provinceSelectId);
            if (!provinceSelect) return;
            clearAndSetDefault(provinceSelect, '--Seleccionar provincia/municipio--');

            regionSelect.addEventListener('change', async function() {
                const selectedState = this.value;
                clearAndSetDefault(provinceSelect, '--Seleccionar provincia/municipio--');
                const selectedCountry = countrySelect.value;
                if (selectedCountry && selectedState) {
                    const cities = await loadCities(selectedCountry, selectedState);
                    if (cities && cities.length) {
                        cities.sort((a, b) => a.localeCompare(b));
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city;
                            option.textContent = city;
                            provinceSelect.appendChild(option);
                        });
                    }
                }
            });
        }
    }
}

// Ejemplo de uso: llenar distintos selects para diferentes secciones de la aplicación
fetchData().then(data => {
    // Sección de datos personales con regiones y provincias
    getCountries(data, 'PaisDatoDominicial', 'PaisDatoDominicialRegion', 'PaisDatoDominicialProvincia');

    // Sección de Información Laboral Actual (solo país)
    getCountries(data, 'PaisInformacionLaboral');

    // Sección de formación académica (solo país)
    getCountries(data, 'paisFormacion');

    // Sección de experiencia laboral (solo país)
    getCountries(data, 'paisEmpresa');

    // Sección de experiencia laboral - docente (solo país)
    getCountries(data, 'paisDocente');

    // Sección de experiencia laboral - par evaluador (solo país)
    getCountries(data, 'paisEvaluador');
}).catch(error => console.error('Error al obtener los países:', error));

document.addEventListener('DOMContentLoaded', async () => {
    const data = await fetchData();
    // Ejemplo de cargar select de datos personales con regiones y provincias
    // getCountries(data, 'PaisDatoDominicial', 'PaisDatoDominicialRegion', 'PaisDatoDominicialProvincia');
});