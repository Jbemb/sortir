const city = document.getElementById('event_city');
const place = document.getElementById('event_place');

city.addEventListener('change', testAfficher);

//prepare request
let dbRequest = new XMLHttpRequest();

function addPlaces(evt){
    //recup la valeur choisie dans la liste déroulante
   //let chosenCity = evt.target.value;
   let chosenCity = city.value;
   console.log(url + chosenCity);

   dbRequest.open('POST', url);
   // url? or file on the server with the code? ('GET', "nameoffichier.php?q="+chosenCity, true)

   dbRequest.onreadystatechange = function() {
      //when response is ready and successful
      if(dbRequest.readyState === 4 && dbRequest.status == 200){
         //get response
         let response = JSON.parse(dbRequest.responseText);
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


