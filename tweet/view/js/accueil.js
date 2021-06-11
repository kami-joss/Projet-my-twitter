$(document).ready(function () {
    let token = localStorage.getItem('token');
    if (token == 'deco' || token == '' || token.length < 6) {
        return window.location.href = "connexion.html";
    }

    $('#logout').on('click', function () {
        localStorage.setItem('token', 'deco');
        location.reload();
    });

    $.ajax({
        url: "../../control/php/accueil.php",
        type: "post",
        data: {
            "tokenFirst": token,
        },
        success: function (res) {
            if (res == '' || res == false) {
                window.location.href = "connexion.html";
            } else {
                let tabContenu = JSON.parse(res);
                for (let i = 0; i < tabContenu.length; i++) {
                    let contenu = tabContenu[i];
                    $('#tweet').after("<div class='contenu' ><div class='tweet' <p id = "+contenu.id_post+">" + contenu.arobase + ' : ' + contenu.contenu+ "</p></div> <div class='img'> <img src='"+contenu.images+"' class='img-thumbnail' width='100' height='auto' alt=''></div> </div>");
                }
                $('.tweet').append("<button class='btn-like'> J'aime</button>");
                $('.tweet').append("<button class='btn-reply'> Répondre</button>");
                $('.tweet').append("<button class='btn-retweet'> ReTweet</button>");
            }
            $(".btn-like").on("click", function (e) {
                if($(this).hasClass("text-like")) {
                    $(this).removeClass("text-like");
                } 
                else {
                    $(this).addClass("text-like");
                }
    
                let id = $(this).parent().attr("id");
                $.ajax({
                    url: "../../control/php/like.php",
                    type: "post",
                    data: {
                        "token": token,
                        "id": id
                    },
                    success: function (data) {
                    }
                });
            });

            $(".btn-reply").on("click", function (e) {

                let id_post = $(this).parent().attr("id");
                let reply = $("#tweet_reply").val();
                let tweet_content = $(this).parent().children(".contenu").html();
                $("#reply_target").html(tweet_content);
                $("#tweet_reply").val("");

                $(".poppin").fadeIn();

                $(".poppin").on("click", function (e) {
                    $(".poppin").fadeOut();
                })
                $(".bloc_poppin").on("click", function (e) {
                    e.stopPropagation();
                });

                $("#tweet_reply").on("change", function(e) {
                    console.log(e.key);
                    reply = $("#tweet_reply").val() ;
                    console.log(reply)

                    if (reply == "") {
                        $("#btn-reply-confirm").attr("disabled", "true");
                    }

                    else if (reply.length > 144) {
                        $("#btn-reply-confirm").attr("disabled", "true");
                        $("#error_tweet").html("Attention, un tweet doit faire moins de 144 caractères.");
                    } 

                    else if (reply.length >= 0 && reply.length < 144) {
                        $("#btn-reply-confirm").removeAttr("disabled");
                        $("#error_tweet").html("");
                    }
                });


                $("#btn-reply-confirm").off()
                $("#btn-reply-confirm").on("click", function (e) {
                    reply = $("#tweet_reply").val();
                    $(".poppin").fadeOut();
                    $.ajax({
                        url: "../../control/php/reply.php",
                        type: "post",
                        data: {
                            "token": token,
                            "id_post": id_post,
                            "reply": reply
                        },
                        success: function (data) {
                            //console.log(data);
                        }
                    });
                });
            });


            $(".btn-retweet").on("click", function (e) { let id_post = $(this).parent().attr("id");
                $.ajax({
                    url: "../../control/php/retweet.php",
                    type: "post",
                    data: {
                        "token": token,
                        "id_post": id_post,
                    },
                    success: function (data) {
                        console.log(data);
                    }
                });
            });
            
        }
    });

    $('#btn_tweet').on('click', function () {
        let new_tweet = $("#new_tweet").val();
        var img_tweet = $("#img_tweet")[0].files[0];
        if (new_tweet.length > 10) {
            $.ajax({
                url: "../../control/php/accueil.php",
                type: "post",
                data: {
                    "token": token,
                    "tweet": new_tweet,
                },
                success: function (data) {
                    console.log(data);
                }
            });
            $("#new_tweet").val("");
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
                        url: "../../control/php/accueil.php",
                        type: "post",
                        data: img,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            $('#img_tweet').before("<label class='success'> Téléchargé</label>");
                        },
                        success: function (data) {
                            $('.success').before(data);
                        }
                    });
                }
            }
        } else {
            $('.mini10').remove();
            $('textarea').after("<p class = 'mini10'> 10 caractères minimum ! </p>");
            $('.mini10').css('color', 'red');
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


});