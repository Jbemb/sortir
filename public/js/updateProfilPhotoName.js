
$('#user_update_photo').on('change', function(){
    //get the name of the photo
    var fileName = $(this).val().replace('C:\\fakepath\\', " ");
    //put the name into the input
    $(this).next('.custom-file-label').html(fileName);
})
