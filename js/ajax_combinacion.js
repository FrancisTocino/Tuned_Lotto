
function llamada_combinacion_primitiva(timeline_valor){
		var parametros = {"timeline":timeline_valor};
		$.ajax({
			url : '../_tunedlotto/php/calculo_combinacion/calculo_primitiva.php',
			type: 'post',
			data: parametros,
			beforeSend: function () {
                        $("#resultado_pr").html("Procesando, espere por favor...");
        	},
            success:  function (response) {
                        $("#resultado_pr").html(response);
            }
		});

};


function llamada_combinacion_bonoloto(timeline_valor){
		var parametros = {"timeline":timeline_valor};
		$.ajax({
			url : '../_tunedlotto/php/calculo_combinacion/calculo_bonoloto.php',
			type: 'post',
			data: parametros,
			beforeSend: function () {
						//alert('BEFORE');
                        $("#resultado_bn").html("Procesando, espere por favor...");
        	},
            success:  function (response) {
            			//alert('RESPONSSE');
                        $("#resultado_bn").html(response);
            }
		});

};


function llamada_combinacion_euromillones(timeline_valor){
		var parametros = {"timeline":timeline_valor};
		$.ajax({
			url : '../_tunedlotto/php/calculo_combinacion/calculo_euromillones.php',
			type: 'post',
			data: parametros,
			beforeSend: function () {
						//alert('BEFORE');
                        $("#resultado_eu").html("Procesando, espere por favor...");
        	},
            success:  function (response) {
            			//alert('RESPONSSE');
                        $("#resultado_eu").html(response);
            }
		});

};


function llamada_combinacion_elgordo(timeline_valor){

		var parametros = {"timeline":timeline_valor};
		$.ajax({
			url : '../_tunedlotto/php/calculo_combinacion/calculo_elgordo.php',
			type: 'post',
			data: parametros,
			beforeSend: function () {
						//alert('BEFORE');
                        $("#resultado_eg").html("Procesando, espere por favor.");
        	},
            success:  function (response) {
            			//alert(response);
                        $("#resultado_eg").html(response);
            }
		});

};

