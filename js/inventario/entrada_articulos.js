
	$(document).ready(function(){

        var pathArray = window.location.pathname.split( '/' );
        var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";
                
        $("#fecha_registro").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});

        // Codigo para los select
        $("#categoria, #sub_categoria, #bodega ,#proveedor, #entrada, #articulo").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });

        $("#proveedor").change(function(){
            $("#proveedor-error").hide();
        });
        $("#bodega").change(function(){
            $("#bodega-error").hide();
        });
        $("#entrada").change(function(){
            $("#entrada-error").hide();
        });

        $("#articulo").change(function(){
            $("#descripcion").attr('disabled',false);
            $("#articulo_error").text('');

            // Add Ajax to call UM
            var articulo = $("#articulo").val();
            if(articulo>0){
                $.ajax({
                //url: 'obtener_precio',
                url: urlj+"home/abastecimiento/obtener_um",
                type: 'POST',
                dataType: 'json',
                data: {id:articulo},
                success: function(data) {
                    $("#um").val(data.um);
                    $("#unidad_medida").show();
                }
            });            
            }
            // Cargando precio, en proceso.
        });

        $("#cantidad").focus(function(){
            $("#cantidad_error").text('');
        });
        $("#precio").focus(function(){
            $("#precio_error").text('');
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
            bodega: {selectNone: true},
            proveedor: {selectNone: true},
            entrada: {selectNone: true},
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
      		bodega: "Seleccione una bodega",      
      		proveedor: "Seleccione un proveedor",      
            entrada: "Seleccione una entrada",
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

		}); // Fin validdar formulario

    $("#categoria").change(function(){
         
        var id_cat = $("#categoria").val();
        $("#sub_categoria").attr('disabled',false);

        $.ajax({
            url: 'cargar_subcategorias',
            type: 'POST',
            dataType: 'json',
            data: {id : id_cat},
            success:function(data) {
              //  alert(data.drop);
               $("#sub_categoria").select2('destroy').html(data.drop).select2({theme: "classic", allowClear: true});
               cargar_articulos(id_cat);
            }
        });
    });

    $("#sub_categoria").change(function(){
        var id_sub = $("#sub_categoria").val();

        $.ajax({
            url: 'cargar_productosxsubcategoria',
            type: 'POST',
            dataType: 'json',
            data: {id : id_sub},
            success:function(data) {
               $("#articulo").select2('destroy').html(data.drop).select2({theme: "classic", allowClear: true});
            }
        });
    });

    function cargar_articulos(id)
    {
        $.ajax({
            url: 'cargar_productosxcategoria',
            type: 'POST',
            dataType: 'json',
            data: {id:id},
            success:function(data) {
               $("#articulo").select2('destroy').html(data.drop).select2({theme: "classic", allowClear: true});
            }
        });
    }

      var row=0;
      $("#agregar").on("click",function(){
       $("#unidad_medida").hide();         
       if($.trim($('#precio').val())!='' && $.trim($('#cantidad').val())!='' && $('#articulo').val() !=0){
        $("#validar_datagried").text('');
        
        $("#registrar_entrada").attr('disabled',false);
        $('#cabezera').show();
        var N_fila = $("#datagried tbody tr").length + 1;
                var numero_fila = N_fila + 'F' + $("#articulo").val() ; //id unico del tr

                $("#datagried").append('<tr id="'+numero_fila+'"><td><input type="hidden" value="'+numero_fila+'" name="ids_filaP[]" />'
                    
                    +'<input type="hidden" name="productos[]" id="productos" value="'+$("#articulo").val()+'"/>'
                    +'<input type="hidden" name="cantidad[]" id="cantidadi'+row+'" value="'+$("#cantidad").val()+'"/>'
                    +'<input type="hidden" name="precios[]" id="precioi'+row+'" value="'+$("#precio").val()+'"/>'
                    +'<input type="hidden" name="descripcion[]" id="descripcioni'+row+'" value="'+$("#descripcion").val()+'"/>'

                    +'<label name="producto_label" id="productos"/>'+$("#articulo option:selected").text()+'</td>'
                    +'<td><label name="cantidad_label" id="cantidadl'+row+'"/>'+$("#cantidad").val()+'</td>'
                    +'<td><label name="precio_label" id="preciol'+row+'"/>'+$("#precio").val()+'</td>'
                    +'<td><button type="button" id="remove" id_fila="'+numero_fila+'" class="remove" ><span class="glyphicon glyphicon-remove"></span> Anular</button>'
                    +'</tr>');

                $("#cantidad").val('');
                $("#precio").val('');
                $("#descripcion").val('');
               
                row=row+1;
                $('#articulo').select2('val',0);

                }else{
                $('#articulo').addClass('error');
                if(!$("#cantidad").val()>0) {$('#cantidad_error').text('Campo requerido');} 
                if($("#articulo").val()==0 || $("#articulo").val()==null) {$('#articulo_error').text('Campo requerido');} 
                if($("#precio").val() =='') {$('#precio_error').text('Campo requerido');}

                alertify.alert("Debe especificar las características del producto");
            }
        });
    
    $("#remove").live("click", function() {
    $(this).parents("tr").remove();     
    });//Fin de eliminar

     $("#registrar_entrada").click(function(event){
        event.preventDefault();
        if($('#datagried tr').length<=1 ){
            $("#datagried").addClass('error');
        $("#validar_datagried").text('Seleccione al menos un producto');
        }
        else{
            $("#pro_entrada").submit();
        }
     });   

	}); // Cierre del document.ready
