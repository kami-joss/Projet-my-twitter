$(document).ready(function () {
    let id = localStorage.getItem("id_user");
    $.ajax({
        url: "../../control/php/profil.php",
        type: "post",
        data: {
            "id": id,
            "token" : localStorage.getItem('token'),
        },
        success: function (data) {
            var data_profil = JSON.parse(data);
            console.log(data_profil);
            if (data_profil[0] == 'removeButton'){
                $('#follow').remove();
            }
            $('.arobase').val(data_profil['arobase']);
            $('.username').val(data_profil['username']);
            $('.follows').val(data_profil['nb_abonnements']);
            $('.followers').val(data_profil['nb_abonnes']);
            $('.bio').val(data_profil['bio']);
            $('.ville').val(data_profil['location']);
        }


    });



    $.ajax({
        url: "../../control/php/profil.php",
        type: "post",
        data: {
            'id_tweet': id,
        },
        success: function (data) {
            console.log(JSON.parse(data));
            var tweets = JSON.parse(data);
            for (const key in tweets) {
                element = tweets[key]
                $('.content').append("<div id='" + element['id_post'] + "' class= 'tweet'>" + element['arobase'] + " <p>  " + element['contenu'] + "</p> <span>" + element['date_tweet'] + "</div> <div class='img'><img src='" + element['url_image'] + "' alt='" + element['url_image'] + "' id = '" + element['id_image'] + "' class='img-thumbnail' width='100' height='auto'></div>");
            }
        }

    });

    $.ajax({
        url: "../../control/php/profil.php",
        type: "post",
        data: {
            'id_check_follow': id,
            'token_check_follow': localStorage.getItem('token'),
        },
        success: function (data) {
            console.log(data);
            if (data == 'abonne') {
                $("#follow").html("Suivi");
                $("#follow").css('background-color', 'blue');
            }
        }
    })

    $("#follow").on('click', () => {
        if ($("#follow").html() == "Suivre") {
            console.log('suivre');
            $.ajax({
                url: "../../control/php/profil.php",
                type: "post",
                data: {
                    'id_to_follow': id,
                    'token_follow': localStorage.getItem('token'),
                },
                success: function (follow) {
                    console.log(follow);
                    if (follow == 'I follow') {
                        $("#follow").html("Suivi");
                        $("#follow").css('background-color', 'blue');
                    }
                }
            });
        } else {
            console.log('unfollow');
            $.ajax({
                url: "../../control/php/profil.php",
                type: "post",
                data: {
                    'id_del': id,
                    'del_follow': localStorage.getItem('token'),
                },
                success: function (res) {
                    console.log(res);
                    $("#follow").html("Suivre");
                    $("#follow").css('background-color', 'grey');

                }
            });
        }

    });

    $("#btn-set-profil").on("click", function () {
        window.location = 'edit_profil.html';
    });

    $(document).on('change', '#search', function (e) {
        if ($('#search').val().length > 2) {
            $.ajax({
                url: "../../control/php/accueil_recherche.php",
                type: "post",
                data: {
                    "search": $('#search').val(),
                },
                success: function (res) {
                    if (res !== null) {
                        let test = JSON.parse(res);
                        for (let i = 0; i < test.length; i++) {
                            const arobase = test[i];
                            $('.resultat').prepend("<span><li class='resultSearch' id=" + arobase['id_user'] + ">" + arobase['arobase'] + "</li></span>");
                        }

                    }
                    $(document).on('click', function () {
                        $('.resultSearch').remove();
                    });
                }
            });

        }
        $('.resultat').on('click', function (e) {
            localStorage.setItem('id_user', e.target.id);
            window.location.href = "profil.html";
        });
    });

    $('#icone_user').click((e) => {
        e.preventDefault();
        $.ajax({
            url: "../../control/php/profil.php",
            type: "post",
            data: {
                "user_self": localStorage.getItem('token'),
            },
            success: function (res) {
                localStorage.setItem('id_user', res);
                location.reload();
            }
        })
    });

});