
async function fetchData() {
    try {
        const response = await fetch('https://restcountries.com/v3.1/all?fields=flags,idd,name');
        if (!response.ok) throw new Error('Error al obtener los datos de la API');
        return await response.json();
    } catch (error) {
        console.error("Error al obtener los datos:", error);
        return [];
    }
}

// Función para formatear la opción en el dropdown
function formatCountry(option) {
    if (!option.id) return option.text; // Placeholder
    const flagUrl = $(option.element).data('flag');
    const countryName = $(option.element).text().split(' (')[0]; // Extraer el nombre del país
    const countryCode = $(option.element).val(); // Extraer el código del país
    return $('<span><img src="' + flagUrl + '" alt="Bandera" class="flag-icon"> ' + countryName + ' (' + countryCode + ')</span>');
}

// Función para formatear la selección (solo bandera y código)
function formatSelection(option) {
    if (!option.id) return option.text; // Placeholder
    const flagUrl = $(option.element).data('flag');
    const countryCode = $(option.element).val(); // Extraer el código del país
    return $('<span><img src="' + flagUrl + '" alt="Bandera" class="flag-icon"> ' + countryCode + '</span>');
}

// Función para ordenar los países y colocar Perú al inicio
function sortCountries(countries) {
    // Encontrar Perú
    const peru = countries.find(country => country.name.common === 'Peru');
    // Filtrar los demás países
    const otherCountries = countries.filter(country => country.name.common !== 'Peru');
    // Ordenar los demás países alfabéticamente
    otherCountries.sort((a, b) => a.name.common.localeCompare(b.name.common));
    // Colocar Perú al inicio
    return peru ? [peru, ...otherCountries] : otherCountries;
}

// Función para llenar el select con los códigos de teléfono
async function populatePhoneCodes() {
    const data = await fetchData();
    const phoneCodeSelect = $('#phoneCode');

    // Limpiar el select y agregar una opción por defecto
    phoneCodeSelect.empty();
    phoneCodeSelect.append('<option value="" data-flag="">Selecciona un código</option>');

    // Ordenar los países y colocar Perú al inicio
    const sortedData = sortCountries(data);

    // Recorrer los datos y agregar opciones al select
    sortedData.forEach(country => {
        if (country.idd?.root && country.flags?.png) {
            const countryCode = country.idd.suffixes ? country.idd.root + country.idd.suffixes[0] : country.idd.root;
            const isPeru = country.name.common === 'Peru'; // Verificar si es Perú
            const option = `<option value="${countryCode}" data-flag="${country.flags.png}" ${isPeru ? 'selected' : ''}>
                ${country.name.common} (${countryCode})
            </option>`;
            phoneCodeSelect.append(option);
        }
    });

    // Inicializar Select2 con las opciones formateadas
    phoneCodeSelect.select2({
        templateResult: formatCountry, // Formato en el dropdown
        templateSelection: formatSelection, // Formato en la selección
        minimumResultsForSearch: Infinity
    });
}

// Inicializar cuando el documento esté listo
$(document).ready(populatePhoneCodes);