//Extra small devices Phones - Small devices Tablets - Medium devices Desktops - Large devices Desktops
//       (<768px)	                  (≥768px)	              (≥992px)	              (≥1200px)
//       .col-xs-	                   .col-sm-	               .col-md-	               .col-lg-
//


$(document).ready(function() {

   var tododom = document.getElementsByTagName('*');
   var tododiv = document.getElementsByTagName('div');


   console.log(tododom);
   //console.log(tododiv); 

   var i=0;
   var j = tododom.length;
   var numero=0;
   var patron_start='%col-';
   var patron_end='/%'
   var split='-';
   var breakpoints = ['xs','sm','md','lg'];


   for(i=0 ; i< j; i++) {
       var objetoActual = tododom[i];
       var claseName    = tododom[i].className.toLowerCase();
       //var claseNameLowerCase = claseName.toLowerCase();
       var encontrado = claseName.includes(patron_start);
        if(encontrado){
            numero++;
            var claseExtraida   = extraerClase (claseName,patron_start,patron_end);
            var arrayConvertida = toBootstrap (claseExtraida,split,breakpoints);
            var claseDefinitiva = addBootstarpClass (objetoActual,arrayConvertida);
            //console.log('ACIERTO!!!');
            //console.log('Encontrado clase = ',encontrado);
            //console.log('Toda la Clase ->',claseName);
            //console.log ('Clase Extraida -> ',claseExtraida);
            //console.log('Array Convertida-> ',arrayConvertida);
            //console.log('Objeo Actual-> ',objetoActual);
            //console.log('clase definitiva-> ',claseDefinitiva);
            //console.log(numero);
       }else{
           //console.log('--------');
       }
       //var f = clasename; //.addClass(arrayconvertida);

    }
   //console.log('el atributo class queda al final--------->',f)
   //console.log(numero);



   function extraerClase (clase='',patron_start='',patron_end=''){
       var patron_start=patron_start;
       var patron_end=patron_end;
       var a = clase.indexOf(patron_start);
       var b = clase.indexOf(patron_end);
       //console.log('a: ',a,' b ',b);
       var extracto=clase.substring((a+patron_start.length),b);
       return(extracto);
   }


   function toBootstrap(clase='',split='',breakpoints){
       var classArrayEntrada = clase.split(split);
       var classLongitud = classArrayEntrada.length;
       var a = '';var b ='';var c='';
       var classBootstrapArray = [];
       for (var i=0;i<classLongitud;i++){
            a = breakpoints[i];
            b = classArrayEntrada[i];
            c = ('col-'+a+'-'+b);
            //console.log('Valor de a->',a,' Valor de b->',b,' Valor de C->',c);
            classBootstrapArray.push(c);
            //console.log('classbootstraparray-> ',classbootstraparray);
       }
       //console.log(c);
       return(classBootstrapArray);
   }

   function addBootstarpClass(obj,array){
       var a = array.toString();
       var b = a.replace(/,/g, ' ');
       //console.log('Primero a es un array convertida en String:',a);
       //console.log('Después b es a pero sin comas',b);
       //console.log('function addBootstarpClass className: ',claseName,' array: ',a,b);
       var final= obj;
       for (var i=0;i<array.length;i++){
           final.classList.add(array[i]);
       }
       return (final)
    }


});
