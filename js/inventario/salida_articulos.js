
	$(document).ready(function(){
        // Definir mascara
        var pathArray = window.location.pathname.split( '/' );
        var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";

        //$("#fecha_salida").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});

		$("#bodega, #categoria, #sub_categoria, #proveedor, #salida, #articulo").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });
		$("#cantidad").numeric('.'); 

        $("#articulo").change(function(){
            $("#descripcion").attr('disabled',false);
            $("#articulo_error").text('');

            // // Add Ajax to call UM
            var articulo =  $("#articulo option:selected").attr('id_articulo');
            if(articulo>0){
                $.ajax({
                //url: 'obtener_precio',
                url: urlj+"home/abastecimiento/obtener_um",
                type: 'POST',
                dataType: 'json',
                data: {id:articulo},
                success: function(data) {
                    $("#um").val(data.um);
                    $("#unidad_medida").show('slow');
                }
            });            
            } else {
                $("#um").val('');
                $("#unidad_medida").hide('slow');
            }
        });

		$("#cancelar").live("click", function(){
			// alertify.alert("Pendiente de programar");
              location.reload();
 		});

        $("#bodega").change(function(){
            $("#bodega-error").hide();
        });
        $("#salida").change(function(){
            $("#salida-error").hide();
        });    

        $("#cantidad").focus(function(){
            $("#cantidad_error").text('');
        });

        $("#bodega").change(function(){
            //alert($(this).val());
            var id_bod = $(this).val();
            if($.isNumeric(id_bod) && id_bod >0){
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

                    $("#descripcion").attr('disabled',false);
                    $("#articulo_error").text('');
                }
            })
          }
        });

        // Validar Cantidad
		jQuery.validator.addMethod("selectNone",function(value, element) { 
	   		if (element.value == "0" || element.value == '') { 
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
            bodega: {selectNone: true},
            //proveedor: {selectNone: true},
            salida: {selectNone: true},
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
      		bodega: "Seleccione una bodega",      
      		proveedor: "Seleccione una proveedor",      
            salida: "Seleccione un tipo de proceso",
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

		}); // Fin validar formulario

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
       if($.trim($('#cantidad').val())!='' && $("#cantidad").val() > 0 && $('#articulo').val() !=0){
        $("#validar_datagried").text('');
        
        $("#registrar_salida").attr('disabled',false);
        $('#cabezera').show();
        var N_fila = $("#datagried tbody tr").length + 1;
                var numero_fila = N_fila + 'F' + $("#articulo").val() ; //id unico del tr

                $("#datagried").append('<tr id="'+numero_fila+'"><td><input type="hidden" value="'+numero_fila+'" name="ids_filaP[]" />'
                    
                    +'<input type="hidden" name="sar_registro[]" id="sar_registro" value="'+$("#articulo").val()+'"/>'
                    +'<input type="hidden" name="cantidad[]" id="cantidadi'+row+'" value="'+$("#cant_real").val()+'"/>'
                    +'<input type="hidden" name="cantidad_salida[]" id="cantidad_salidai'+row+'" value="'+parseFloat($("#cantidad").val()).toFixed(1)+'"/>'
                    +'<input type="hidden" name="descripcion[]" id="descripcioni'+row+'" value="'+$("#descripcion").val()+'"/>'

                    +'<label name="producto_label" id="productos"/>'+$("#articulo option:selected").text()+'</td>'
                    +'<td><label name="cantidad_label" id="cantidadl'+row+'"/>'+parseFloat($("#cantidad").val()).toFixed(1)+'</td>'
                    +'<td><button type="button" id="remove" id_fila="'+numero_fila+'" class="remove" ><span class="glyphicon glyphicon-remove"></span> Anular</button>'
                    +'</tr>');

                row=row+1;
                $("#cantidad").val('');
                $("#descripcion").val('');
                $('#articulo').select2('val',0);

                }else{
                $('#articulo').addClass('error');
                if(!$("#cantidad").val()>0) {$('#cantidad_error').text('Campo requerido');} 
                if($("#articulo").val()==0 || $("#articulo").val()==null) {$('#articulo_error').text('Campo requerido');} 

                alertify.alert("Debe especificar las características del producto").setHeader('');
            }
        });
    
    $("#remove").live("click", function() {
       var sar_id =  $(this).parents('tr').find("td:first input[name='sar_registro[]']").val();    
       var cadena_select = $("#articulo option[value='"+sar_id+"']").text();
       var cadena = cadena_select.split('::');

       var cantidad_actual  =  cadena[1];    
       var cantidad_salida  =  $(this).parents('tr').find("td:first input[name='cantidad_salida[]']").val();    
       var saldo = (parseFloat(cantidad_actual) + parseFloat(cantidad_salida)).toFixed(2);
        
        $("#articulo option[value='"+sar_id+"']").text(cadena[0]+'::'+saldo);

        $("#articulo").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });

    $(this).parents("tr").remove();     
    });//Fin de eliminar

     $("#registrar_salida").click(function(event){
        event.preventDefault();
        if($('#datagried tr').length<=1 ){
            $("#datagried").addClass('error');
        $("#validar_datagried").text('Seleccione al menos un producto');
        }
        else{
            $("#pro_salida").submit();
        }
     });
    
	}); // Fin del document.ready

    function validar_cantidad()
    {
        var cantidad_sug = $("#cantidad").val();
        var cadena_select = $("#articulo option:selected").text();
        var cantidad_act = cadena_select.split('::');
        var total = parseFloat(cantidad_act[1]) - parseFloat(cantidad_sug).toFixed(1);

        if(total<0)
        {
            alertify.alert("Por favor, ingrese una cantidad menor").setHeader('');
            $("#cantidad").val('').focus();   
        } else
        { 
            $("#cant_real").val(total); // El saldo que queda
            $("#articulo option:selected").text(cantidad_act[0]+'::'+total);

            $("#articulo").select2({
                minimumResultsForSearch: 4,
                placeholder: "Seleccione",
                theme: "classic", // bootstrap
                allowClear: true
            });
            
        }
    };
	// $("#cancelar").click(function(){
	// 	alert("Aun en proceso");
	// });	 