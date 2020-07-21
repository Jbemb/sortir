const city = document.getElementById('event_city');
// in twig "à la mano"
const placeChoice = document.getElementById('event_placeChoice');
// from PlaceHiddenType
const place = document.getElementById('event_place');

let placeId = place.value;

function addPlaces() {
    //recup la valeur choisie dans la liste déroulante
    let chosenCity = city.value;

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
                // option.setAttribute("data-value", p.id);
                option.value = p.id;
                placeChoice.appendChild(option);
                onReload();
                onChangePlace();
            })
        }
    );
}

function onChangePlace() {
    place.value = placeChoice.options[placeChoice.selectedIndex].value;
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
