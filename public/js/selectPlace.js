const city = document.getElementById('event_city');
// in twig "à la mano"
const placeChoice = document.getElementById('event_placeChoice');
// from PlaceHiddenType
const place = document.getElementById('event_place');

city.addEventListener('change', addPlaces);
placeChoice.addEventListener('change', onChangePlace);
//prepare request
let dbRequest = new XMLHttpRequest();

function addPlaces(evt) {
    //recup la valeur choisie dans la liste déroulante
    let chosenCity = evt.target.value;

    $.post(
        url,
        {
            cityId: chosenCity
        },
        function (data) {
            placeChoice.innerText = "";
            console.log(data)

            $.each(data, function (key, p) {
                let option = document.createElement('option');
                option.text = p.name;
                // option.setAttribute("data-value", p.id);
                option.value = p.id;
                placeChoice.appendChild(option);
                onChangePlace();
            })
        }
    );
}

function onChangePlace() {
    place.value = placeChoice.options[placeChoice.selectedIndex].value;
}

/* dbRequest.open('POST', url);
 // url? or file on the server with the code? ('GET', "nameoffichier.php?q="+chosenCity, true)

 dbRequest.onreadystatechange = () => {
    //when response is ready and successful
    if(dbRequest.readyState === 4 && dbRequest.status == 200){
       //get response
       const response = JSON.parse(dbRequest.response);
       console.log(response);

       // add array to drop down menu
       for(p in response) {
          let option = document.createElement('option');
          option.text = p.name;
          place.add(option);
       }
    }
 }

 dbRequest.send( "cityId=" + chosenCity);
}


function testAfficher() {
 var places =[ 'eni', 'plage'];

 for(let p =0; p< places.length; p++){
    let option = document.createElement('option');
    option.text = places[p];
    place.add(option);
 }

}

function addStreet(evt){

 let chosenPlace = place.value;
 console.log(chosenPlace);

 dbRequest.open('GET', '/recupererLieu/' + chosenPlace);
 // url? or file on the server with the code? ('GET', "nameoffichier.php?q="+chosenCity, true)
 dbRequest.send();
}*/