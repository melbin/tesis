
	$(document).ready(function(){
        
        $("#fecha_registro").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});

        // Codigo para los select
        $("#bodega ,#proveedor, #entrada, #articulo").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });

		// $("#bodega, #proveedor, #entrada, #articulo").selectmenu();
		$("#cantidad , #precio").validarCampo('0123456789.,'); 

		$("#cancelar").live("click", function(){
			// alertify.alert("Pendiente de programar");
            location.reload();
 		});

		jQuery.validator.addMethod("selectNone",function(value, element) { 
	   		if (element.value == "0") { 
	      		return false; 
	    	} 	    
    			else return true; 
	  		}, 
	  		"Please select an option." 
		);

		 $("#pro_entrada").validate({
		 	// Specify the validation rules
		 	ignore: "",
        rules: {
            fecha_registro: "required",
            cantidad: "required",
            bodega: {selectNone: true},
            proveedor: {selectNone: true},
            entrada: {selectNone: true},
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
            fecha_registro: "Seleccione una fecha de registro",
            cantidad: "Seleccione una cantidad",
      		bodega: "Seleccione una bodega",      
      		proveedor: "Seleccione un proveedor",      
            entrada: "Seleccione una entrada",
            articulo: "Seleccione un articulo",
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


	// $("#cancelar").click(function(){
	// 	alert("Aun en proceso");
	// });	 
