const city = document.getElementById('event_city');
const place = document.getElementById('event_place');

function addPlaces(evt){
    //recup la valeur choisie dans la liste déroulante
   let chosenCity = evt.target.value;
  // let chosenCity = city.value;
   console.log(chosenCity);
}

city.addEventListener('change', addPlaces);