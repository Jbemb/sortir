const place = document.getElementById('modify_event_place');
console.log(place.value);

place.addEventListener("change", addStreet);

function addStreet(evt) {
    //récupérer la place choisie
    let chosenPlace = evt.target.value;

    //requête ajax post (url, )
    $.post(
        urlStreet,
        {
            placeId:chosenPlace
        },
        function (data) {
                $("#rue").val(data[0].street);
                $('#longitude').val(data[0].longitude);
                $('#latitude').val(data[0].latitude);
        }
    )
}

