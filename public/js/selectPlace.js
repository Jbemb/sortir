const city = document.getElementById('event_city');
const place = document.getElementById('event_place');

city.addEventListener('change', addPlaces);

//prepare request
let dbRequest = new XMLHttpRequest();

function addPlaces(evt){
    //recup la valeur choisie dans la liste déroulante
   let chosenCity = evt.target.value;

   $.post(
       url,
       {
          cityId: chosenCity
       },
       function(data){
          console.log(data);
       }
   );

  /* dbRequest.open('POST', url);
   // url? or file on the server with the code? ('GET', "nameoffichier.php?q="+chosenCity, true)

   dbRequest.onreadystatechange = () => {
      //when response is ready and successful
      if(dbRequest.readyState === 4 && dbRequest.status == 200){
         //get response
         console.log('coucou');
         console.log(dbRequest.responseText);
         let response = JSON.parse(dbRequest.response);


         // add array to drop down menu
         for(p in response) {
            let option = document.createElement('option');
            option.text = p.name;
            place.add(option);
         }
      }
   }

   dbRequest.send( "cityId=" + chosenCity);*/
}





function addStreet(evt){

   let chosenPlace = place.value;
   console.log(chosenPlace);

   dbRequest.open('GET', '/recupererLieu/' + chosenPlace);
   // url? or file on the server with the code? ('GET', "nameoffichier.php?q="+chosenCity, true)
   dbRequest.send();
}