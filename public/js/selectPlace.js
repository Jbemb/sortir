const city = document.getElementById('event_city');
// in twig "à la mano"
const placeChoice = document.getElementById('event_placeChoice');
// from PlaceHiddenType
const place = document.getElementById('event_place');

let placeId = place.value;

function addPlaces() {
    //recup la valeur choisie dans la liste déroulante
    let chosenCity = city.value;
    //put postal code
    //$('#codePostal').val(pData['latitude']);

    $.post(
        url,
        {
            cityId: chosenCity
        },
        function (data) {
            placeChoice.innerText = "";

            $.each(data, function (key, p) {
                let option = document.createElement('option');
                option.text = p.name;
                option.setAttribute("data-value", JSON.stringify(p));
                option.value = p.id;
                placeChoice.appendChild(option);
                onReload();
            });
                onChangePlace();
        }
    );
}

function onChangePlace() {
    let p = placeChoice.options[placeChoice.selectedIndex];
    place.value = p.value;

    pData = JSON.parse(p.getAttribute('data-value'));
    $('#street').val(pData['street']);
    $('#latitude').val(pData['latitude']);
    $('#longitude').val(pData['longitude']);
}


city.addEventListener('change', addPlaces);
placeChoice.addEventListener('change', onChangePlace);

if (placeId !== '') {
    let indexToSelect;
    addPlaces();
    onReload();
}

function onReload() {
    for (let i = 0; i < placeChoice.options.length; i++) {
        if (placeChoice.options[i].value == placeId) {
            indexToSelect = i;
            placeChoice.options[i].selected = 'selected'
            break;
        }
    }
}
