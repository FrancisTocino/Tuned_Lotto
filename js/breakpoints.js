// Este archivo funciona junto a system-grid.js
// No tiene nada que ver con jsgrid.js
// |----------|------------|-------------|------------|-----------|
// |    xs    |     sm     |      md     |      lg    |    xl     |
// 0         544          768           992          1200         +


$(document).ready(function() {
    checkSize();
    $(window).resize(checkSize);
});


//Function de Grid de Columnas
function checkSize(){

    var _window_width = window.innerWidth;
     $("#info").html('<p>Ancho de ventana: '+_window_width+'</p>');

    if (_window_width <= 544) {
        system_grid("_xs_grid");
    } else if (_window_width > 544 && _window_width <= 768) {
        system_grid("_sm_grid");
    } else if (_window_width > 768 && _window_width <= 992) {
        system_grid("_md_grid");
    } else if (_window_width > 992 && _window_width <= 1200) {
        system_grid("_lg_grid");
    } else if (_window_width > 1200 ) {
        system_grid("_xl_grid");
    } else {
        alert ('Resolución No Soportada');
    }
}

function  system_grid(_media){
    console.log("_media: "+_media);
    var i;
    for (i=0 ; i <= _media.length-1 ; i++){
        var _id=_media[i][0];
        var _className=_media[i][1];
        console.log("_id=_media[i][0]"+_id);
        console.log("_className=_media[i][1]"+_className);
        $('#'+_id).removeClass();
        $('#'+_id).addClass(_className);
        }

}
