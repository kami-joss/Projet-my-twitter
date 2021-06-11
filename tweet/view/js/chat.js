$( document ).ready(function() {
    cible="";
    
    liste_search="";
    newContact="";
    deplace="";
    $('#search').after("<div id='resultat'></div>");
    $('#resultat').css({
        'position' : 'absolute',
        'background-color' : 'white',
        'border' : '1px solid black',
        'top': '159' 
    });
     function clique(effacer){
        $('.contacte').click(function(){
            cible=this.dataset.action;
            if(effacer==1){
                $('#resultat .contacte').remove();
            }
            $.post(
                '../../control/php/chat.php', 
                {
                        
                    id_user : cible,
                    list_message_on : 1,
                    token : localStorage.getItem('token'),

                },
                list_message_id_user
            );
            
            function list_message_id_user(texte_recu){
                
                $('#reception').html(texte_recu);
                
                $('#reception').css({
                    'display':'flex',
                    'flex-direction':'column-reverse',

                });
            }
            
        });   
    }
    //lancement automatique des personnes contacter
    
    $.post(
        '../../control/php/chat.php', 
        {
            chargementContacte : 1,
            token : localStorage.getItem('token'),
        },
        people_Contact
    );
    
    function people_Contact(chargement_Contacte){
        $('.form-control').after(chargement_Contacte);
        clique(0);
        // console.log(chargement_Contacte);
    }    
    $('#search').keydown(function(event) {
        $('#resultat .contacte').remove();
        
        var caracode=event.keyCode;
        let length_search=(($('#search').val()).length+1);
        if(caracode==8){
            length_search=0;
        }
        if (length_search > 2 ){ // a partir de 3 carac
            $.post(
                '../../control/php/chat.php', 
                {
                    mot : $('#search').val(),
                    token : localStorage.getItem('token'),

                },
                last_message
            );
        }
        function last_message(texte_recu){
            $('#resultat').append(texte_recu);
            clique(1);
            
               
        }
    });

    // envoyer le message
    $('#send_chat').click(function(){
            $.post(
                '../../control/php/chat.php', 
                {
                    message : $('#message').val(),
                    id_user : cible,
                    token : localStorage.getItem('token'),
                    list_message_on : 1,
                },
                reception_message_seed
            );
        
        function reception_message_seed(texte_envoyer){
            var cute_text = texte_envoyer.split('@@@@@');
            console.log(cute_text);
            $('#auto_contacte').html(cute_text[1]);
            $('#reception').html('');
            $('#reception').html(cute_text[0]);
            
            clique(0);
        }
    });
    //https://stackoverflow.com/questions/2320069/jquery-ajax-file-upload
    //https://www.geeksforgeeks.org/how-to-upload-files-asynchronously-using-jquery/
    //https://makitweb.com/how-to-upload-image-file-using-ajax-and-jquery/
    
    /*$("form").submit(function(evt){	 
      evt.preventDefault();
      var formData = new FormData($(this)[0]);
   $.ajax({
       url: 'fileUpload',
       type: 'POST',
       data: formData,
       async: false,
       cache: false,
       contentType: false,
       enctype: 'multipart/form-data',
       processData: false,
       success: function (response) {
         alert(response);
       }
   });
   return false;
 });*/
});

