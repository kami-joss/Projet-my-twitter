$(document).ready(()=> {
    $('body').css({
        "background": "url('../ressources/bg.jpg')",
        "background-size": "Cover"
    });

    $('#login').on('click', (e)=> {
        e.preventDefault();
        $(".text-danger").remove();
        if ($('#mail').val().length < 6 || $('#pass').val().length <3 ) {
            $('.nope').remove();
            $('div[data-input = password]').after("<p class= 'text-danger text-center'> Please fill in the fields </p>");
        } else {
            $('.nope').remove();
            let ajax = $.ajax({
                url : '../../control/php/connexion.php',
                type : 'post',
                data : {
                    'mail' : $('#mail').val(),
                    'pass' : $('#pass').val(),
                }
            });
            ajax.done((res)=>{
                console.log(res);
                if (res == 'fail') {
                    $('.alert-danger').remove();
                    $('div[data-input = email').after("<p class = 'text-danger text-center'> E-mail or password wrong ! </p>");
                    $('.wrong').css({
                        'color' : 'red',
                        'font-size' : '20px',  
                        'border' : 'solid 2px red',
                        'padding' : '7px',
                        'border-radius' : '30px',
                    })
                }
                else {
                    localStorage.setItem('token', res);
                    window.location.href = "../html/accueil.html";
                }
            }); 
        }
    });
})