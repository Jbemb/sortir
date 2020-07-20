console.log('oui!')
const place = document.getElementById('modify_event_place');
console.log(place.value);

place.addEventListener("change", addStreet);

function addStreet(evt) {
    //récupérer la place choisie
    let chosenPlace = evt.target.value;
    console.log(chosenPlace);

    //requête ajax post (url, )
    $.post(
        urlStreet,
        {
            placeId:chosenPlace
        },
        function (data) {
            console.log(data);
            $("#rue").val(data);
        }
    )
}

