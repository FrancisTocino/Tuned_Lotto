
function llamada(){
			alert('llegoa al JS?');
			$.ajax({
				url : 'test1.php',
				type: 'post',
				beforeSend: function () {
							//alert('BEFORE');
	                        $("#resultado").html("Procesando, espere por favor...");
	        	},
	            success:  function (response) {
	            			//alert('RESPONSSE');
	                        $("#resultado").html(response);
	            }
			});

};

