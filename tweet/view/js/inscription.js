$(document).ready(() => {
    $('body').css({
        "background": "url('../ressources/bg.jpg')",
        "background-size": "Cover"
    });
    
    $('#btn-subscribe').on('click', (e) => {
        e.preventDefault();
        $('.nope').remove();
        $('.bloc_inscription input').each(function () {
            let input = $(this);
            input.removeClass("border-danger");
            if (input.val().length < 2) {
                input.addClass("border-danger");
                input.after("<p class ='text-danger nope'> Le champ " + input.attr('name') + " est requis !</p>");
            }
        });
        if ($('p').hasClass('nope')) {
            
        } else {
            var all = $('#inscription').serialize();
            let inscription = $.ajax({
                url: "../../control/php/inscription.php",
                type: "post",
                data: all,
            });
            inscription.done((res) => {
                if (res == 'existant') {
                    $("#exist").remove();
                    // $('h1').after("<div id='exist'>Mail already taken</div>");
                    $(".bloc_inscription").prepend("<p id='exist' class='alert alert-danger'> Mail already taken </p>")
                    console.log(res)
                } else {
                    $("#exist").remove();
                    $('.inscription').hide();
                    $('.bloc_inscription').prepend("<p id='succes' class='alert alert-success d-flex justify-content-center font-weight-bold'> You are registred ! <br/> Vous allez être rédirigé. </p>");
                    // $('#succes').css({
                    //     'display' : 'flex',
                    //     'justify-content': 'center',
                    //     'margin': '15px',
                    //     'padding' : '8px',
                    //     'font-weight' : 'bold',
                    // });
                    setTimeout(function() {
                        window.location = 'connexion.html';
                    }, 5000)

                    console.log('insc reussi')
                }
            });
        }
    });

});
