$(document).ready(function ($) {
    var token = localStorage.getItem("token");
    $.ajax({
        url: "../../control/php/edit_profil.php",
        type: "post",
        data: {
            "token": token
        },
        success: function (data) {
            console.log(data);
            let donnees = JSON.parse(data);
            $("#nom").val(donnees.username)
            $("#bio").val(donnees.bio);
            $("#date_naissance").val(donnees.date_naissance)
            $("#email").val(donnees.email);
        }
    })

    $.ajax({
        url: "../../control/php/abonnements.php",
        type: "post",
        data: {
            "token": token
        },
        success: function (data) {
            let donnees = JSON.parse(data);
            for (let i in donnees) {
                $(".abonnements").append("<div class='col-2 d-flex align-items-center justify-content-between bg-white' id='" + donnees[i].id_user + "'><div><p class='font-weight-bold my-0'>" + donnees[i].username + "</p><p class='font-italic my-0'>" + donnees[i].arobase + "</p></div><button class='btn-sm btn-primary btn-delete-abo'> Supprimer </button></div>");
            }

            $(".btn-delete-abo").on("click", function (e) {
                let id = $(this).parent().attr('id');
                $(this).parent().remove();
                $.ajax({
                    url: "../../control/php/abonnements.php",
                    type: "post",
                    data: {
                        "token": token,
                        "id_abo": id,
                        "action": "delete"
                    },
                    success: function (data) {}
                });
            })
        }
    })

    $("#btn_edit_profil").on("click", function () {
        let p = $("#photo_profil").val();
        let t = $("#photo_profil")[0].files[0];
        $.ajax({
            url: "../../control/php/edit_profil.php",
            type: "post",
            data: {
                "token": token,
                "nom": $("#nom").val(),
                "bio": $("#bio").val(),
                "date_naissance": $("#date_naissance").val(),
                "email": $("#email").val(),
                "photo": t
            },
            success: function (data) {
                console.log(data);
                window.location = 'edit_profil.html';
            }
        })
    })

    $("#btn-set-password").on("click", function (e) {
        let new_password = $("#new_password").val();
        let confirm = $("#new_password_confirm").val()
        if (new_password !== confirm) {
            alert("Les mots de passe doivent correspondrent.");
        } else {
            $.ajax({
                url: "../../control/php/edit_profil.php",
                type: "post",
                data: {
                    "token": token,
                    "password": new_password,
                    "password_confirm": confirm
                },
                success: function (data) {
                    if (data == "true") {
                        $(".success_msg").remove();
                        $("#profil_password").prepend("<div class='success_msg'> Mot de passe mis à jour. </div>");
                    } else {
                        $(".error_msg").remove();
                        $("#profil_password").prepend("<div class='error_msg'> Une erreur est survenu, réessayer plus tard </div>");
                        console.log(data);
                    }
                }
            })
        }
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

    $('#btn_edit_profil').on('click', function () {
        var img_tweet = $("#photo_profil")[0].files[0];
        if (img_tweet != 'undefined') {
            var image_name = img_tweet.name;
            var image_extension = image_name.split('.').pop().toLowerCase();
            if ($.inArray(image_extension, ['png', 'jpg', 'jpeg']) == -1) {
                alert("Format non accepté");
            }
            var image_size = img_tweet.size;
            if (image_size > 3000000) {
                alert("La taille de l'image dépasse la taille autorisé");
            } else {
                var img = new FormData();
                img.append("file", img_tweet);
                $.ajax({
                    url: "../../control/php/edit_profil.php",
                    type: "post",
                    data: {
                        img,
                        'toekn' : localStorage.getItem('token'),
                    } ,
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $('#photo_profil').after("<label class='success'> Téléchargé</label>");
                    },
                    success: function (data) {
                        $('.success').before(data);
                    }
                });
            }
        }
    });

});