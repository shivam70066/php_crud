var config = {
    cUrl: 'https://api.countrystatecity.in/v1/countries',
    ckey: 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA=='
};

var countrySelect = document.querySelector('.country'),
    stateSelect = document.querySelector('.state');

function loadCountries() {
    let apiEndPoint = config.cUrl;

    fetch(apiEndPoint, {headers: {"X-CSCAPI-KEY": config.ckey}})
    .then(response => response.json())
    .then(data => {
        // Sort the countries alphabetically by name
        data.sort((a, b) => a.name.localeCompare(b.name));
        
        // Clear existing options
        // countrySelect.innerHTML = '';
        
        // Append options to the country select dropdown
        data.forEach(country => {
            const option = document.createElement('option');
            option.value = country.iso2; // Assign country name as value
            option.textContent = country.name;
            countrySelect.appendChild(option);
        });
    })
    .catch(error => console.error('Error loading countries:', error));

    // Disable and reset state select
    stateSelect.disabled = true;
    stateSelect.style.pointerEvents = 'none';
}

function loadStates() {
    stateSelect.disabled = false;
    stateSelect.style.pointerEvents = 'auto';

    const selectedCountryName = countrySelect.value; // Get selected country name
    stateSelect.innerHTML = '<option value="select">Select State</option>'; // Clear existing states

    fetch(`${config.cUrl}/${selectedCountryName}/states`, {headers: {"X-CSCAPI-KEY": config.ckey}})
    .then(response => response.json())
    .then(data => {
        // Sort the states alphabetically by name
        data.sort((a, b) => a.name.localeCompare(b.name));

        data.forEach(state => {
            const option = document.createElement('option');
            option.value = state.iso2;
            option.textContent = state.name;
            stateSelect.appendChild(option);
        });
    })
    .catch(error => console.error('Error loading states:', error));
}

window.onload = loadCountries;
