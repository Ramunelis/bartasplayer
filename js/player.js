$(document).ready(function(){

            var stream = {
                title: "Radio Bartas",
                mp3:   "http://stream.franclr.fr:8000/radiobartas"
            },
            ready = false;

            $("#jquery_jplayer_1").jPlayer({
                ready: function (event) {
                    ready = true;
                    $(this).jPlayer("setMedia", stream);
                },
                pause: function() {
                    $(this).jPlayer("clearMedia");
                },
                error: function(event) {
                    if(ready && event.jPlayer.error.type === $.jPlayer.error.URL_NOT_SET) {
                        // Setup the media stream again and play it.
                        $(this).jPlayer("setMedia", stream).jPlayer("play");
                    }
                },
                swfPath: "swf",
                supplied: "mp3",
                preload: "none",
                wmode: "window", //Pour être sur d'utiliser flash pour le mp3 dans Firefox
                keyEnabled: true
            });

            //Affichage du initial titre
            $.ajax({ 
                type: "GET",
                url: "php/streamMeta.php", 
                success: function(text){
                    $("#NowPlaying").html(text);
                }, dataType: "text"});

            //$("#jplayer_inspector").jPlayerInspector({jPlayer:$("#jquery_jplayer_1")});

        });
        
        // Met a jour le titre de la chanson toute les 30s
        (function poll(){
            setTimeout(function(){
                $.ajax({ 
                    type: "GET",
                    url: "php/streamMeta.php", 
                    success: function(text){
                        $("#NowPlaying").html(text);
                        poll();
                    }, dataType: "text"});
            }, 30000);
        })();

