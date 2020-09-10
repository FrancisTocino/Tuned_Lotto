// Este fichero sirve para que salga en la etiqueta info
// la resolución del ancho de la pantalla cuando se hace resize


$(document).ready(function() {
    //system_grid();
    $(window).resize(function(){       
        var ancho_pagina = window.innerWidth;
        $('#info').html('<p>ANCHO: '+ancho_pagina+'</p>');
    });    
});
 

 function  system_grid(_media){
     
    var _system_grid = [
        ["f1c1",  "col-xs-12  col-sm-12  col-md-12   col-lg-12   col-xl-12"],
        
        ["f2c1",  "col-xs-12  col-sm-12  col-md-12   col-lg-12   col-xl-12"],
        
        ["f3c1",  "col-xs-12  col-sm-12  col-md-12   col-lg-12  col-xl-12"],
        ["f4c1",  "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f4c2",  "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f4c3",  "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f5c1",  "col-xs-12  col-sm-12  col-md-12   col-lg-12  col-xl-12"],
        
        ["f6c1",  "col-xs-12  col-sm-12  col-md-12   col-lg-12  col-xl-12"],
        ["f7c1",  "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f7c2",  "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f7c3",  "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f8c1",  "col-xs-12  col-sm-12  col-md-12   col-lg-12  col-xl-12"],
            
        ["f9c1",  "col-xs-12  col-sm-12  col-md-12   col-lg-12  col-xl-12"],
        ["f10c1", "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f10c2", "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f10c3", "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],        
        ["f11c1", "col-xs-12  col-sm-12  col-md-8    col-lg-8   col-xl-8" ],
        ["f11c2", "col-xs-12  col-sm-12  col-md-4    col-lg-4   col-xl-4" ],
        
        ["f12c1", "col-xs-12  col-sm-12  col-md-12   col-lg-12  col-xl-12"],
        ["f13c1", "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f13c2", "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f13c3", "col-xs-6   col-sm-4   col-md-4    col-lg-4   col-xl-4" ],
        ["f14c1", "col-xs-12  col-sm-12  col-md-8    col-lg-8   col-xl-8" ],
        ["f14c2", "col-xs-12  col-sm-6   col-md-4    col-lg-4   col-xl-4" ],
        
        ["f15c1", "col-xs-12  col-sm-12  col-md-12   col-lg-12   col-xl-12"],
        ["f15c2", "col-xs-12  col-sm-12  col-md-12   col-lg-12   col-xl-12"],
        ["f15c3", "col-xs-12  col-sm-12  col-md-12   col-lg-12   col-xl-12"],
    ]; 

    var i;
    for (i=0 ; i <= _system_grid.length-1 ; i++){
        var _id=_system_grid[i][0];
        var _className=_system_grid[i][1];
        $('#'+_id).removeClass();     
        $('#'+_id).addClass(_className);
        }
        
}
