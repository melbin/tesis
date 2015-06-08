
	$(document).ready(function(){
        // Definir mascara
        $("#fecha_salida").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});

		$("#bodega ,#proveedor, #salida, #articulo").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });
		$("#cantidad , #precio").validarCampo('0123456789.,'); 

		$("#cancelar").live("click", function(){
			// alertify.alert("Pendiente de programar");
              location.reload();
 		});    

        $("#bodega").change(function(){
            //alert($(this).val());
            var id_bod = $(this).val();

            $.ajax({
                dataType:'json',
                type:'post',
                url: 'cargar_articulos',
                data:{id_bod:id_bod},
                success: function(data){
                    // alert(data.articulos);
                    $("#articulo").select2('destroy').html(data.articulos).select2({
                        placeholder: "Seleccione",
                        minimumResultsForSearch: 4,
                        theme: "classic",
                        allowClear: true
                    });
                }
            })
        });

        // Validar Cantidad
		jQuery.validator.addMethod("selectNone",function(value, element) { 
	   		if (element.value == "0") { 
	      		return false; 
	    	} 	    
    			else return true; 
	  		}, 
	  		"Please select an option." 
		);

		 $("#pro_salida").validate({
		 	// Specify the validation rules
		 	ignore: "",
        rules: {
            fecha_salida: "required",
            cantidad: "required",
            bodega: {selectNone: true},
            proveedor: {selectNone: true},
            salida: {selectNone: true},
            articulo: {selectNone: true},
            // email: {
            //     required: true,
            //     email: true
            // },
            // password: {
            //     required: true,
            //     minlength: 5
            // },
            // agree: "required"
        },

        // Specify the validation error messages

        messages: {
            fecha_salida: "Seleccione una fecha",
            cantidad: "Seleccione una cantidad",
      		bodega: "Seleccione una bodega",      
      		proveedor: "Seleccione una proveedor",      
            salida: "Seleccione un tipo de proceso",
            articulo: "Seleccione un artículo",
            // password: {
            //     required: "Please provide a password",
            //     minlength: "Your password must be at least 5 characters long"
            // },
            // email: "Please enter a valid email address",
            // agree: "Please accept our policy"
        },

        submitHandler: function(form) {
            form.submit();
        }

		});

	});

    function validar_cantidad()
    {
        var cantidad_sug = $("#cantidad").val();
        var cadena_select = $("#articulo option:selected").text();
        var cantidad_act = cadena_select.split('::');
        var total = parseInt(cantidad_act[1]) - parseInt(cantidad_sug);

        if(total<=0)
        {
            alertify.alert("Por favor, ingrese una cantidad menor");
            $("#cantidad").val('').focus();   
        } else
        { $("#cant_real").val(total); }
    };
	// $("#cancelar").click(function(){
	// 	alert("Aun en proceso");
	// });	 