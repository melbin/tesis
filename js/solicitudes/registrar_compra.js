    
    $(document).ready(function(){

        $(".select2-selection__clear").live('click',function(){
            alert();
        });

        // Codigo para los select
        $(".select2").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });

        $(".enteros").validarCampo('0123456789'); 
        $(".decimales").numeric('.');
        $("#nit").mask("9999-999999-999-9"); 

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

         $("#frm_compra").validate({
            // Specify the validation rules
            ignore: "",
        rules: {
    //        fecha_entrega: "required",
        },

        // Specify the validation error messages
        messages: {
            fecha_entrega: "Seleccione una fecha de registro"
        },

        submitHandler: function(form) {
            form.submit();
        }

        }); // Fin validar Formulario

      var row=0;

      $("#add").on("click",function(){

       if( $.trim($('#contrato').val())!='' && $.trim($('#monto').val())!='' && $("#proveedor").val()>0){
        $("#validar_datagried").text('');  
        $("#fila_temporal").remove();
        // $('#cabezera').show();
        var N_fila = $("#datagried tbody tr").length + 1;
                var numero_fila = N_fila; //id unico del tr

                $("#datagried").append('<tr id="'+numero_fila+'"><td><input type="hidden" value="'+numero_fila+'" name="ids_filaP[]" />'
                    +'<input type="hidden" name="proveedor[]" value="'+$("#proveedor").val()+'"/>'
                    +'<input type="hidden" name="contrato[]"  value="'+$("#contrato").val()+'"/>'
                    +'<input type="hidden" name="monto[]"  value="'+$("#monto").val()+'"/>'
                    +'<input type="hidden" name="descripcion[]" value="'+$("#descripcion").val()+'"/>'
                    +'<input type="hidden" name="renta[]"  value="'+$("#renta").val()+'"/>'
                    +'<input type="hidden" name="nit[]"  value="'+$("#nit").val()+'"/>'
                    +'<input type="hidden" name="retencion[]"  value="'+$("#retencion").val()+'"/>'

                    +'<label>'+$("#proveedor option:selected").text()+'</label></td>'
                    +'<td><label>'+$("#contrato").val()+'</label></td>'
                    +'<td><label>'+$("#monto").val()+'</label></td>'
                    +'<td><label>'+$("#renta").val()+'</label></td>'
                    +'<td><label>'+$("#nit").val()+'</label></td>'
                    +'<td><label>'+$("#retencion").val()+'</label></td>'
                    +'<td><button type="button" id="remove" id_fila="'+numero_fila+'" class="remove" ><span class="glyphicon glyphicon-remove"></span> Anular</button>'
                );        
                row=row+1;

                // Todas los seteos aca
               $('#proveedor').select2('val',0);
                $("#descripcion, #contrato, #monto, #renta, #nit, #retencion").val('');
                $("#registrar_compra").attr('disabled',false);        
                $("#anular").attr('disabled',false);

                }else{
                //alert("Debe especificar las características del producto!");
                $('#proveedor').addClass('error');
                $('#descripcion').addClass('error');

                alertify.alert("Campos con <b style='color:red;'>*</b> son requeridos").setHeader('');
            }
        });

    $("#remove").live("click", function() {
    $(this).parents("tr").remove();     
      
     if($("input[name='proveedor[]']").length < 1 ){
            $("#registrar_compra").attr('disabled',true);  
        }    
    });//Fin de eliminar

    $("#registrar_compra").click(function(event){
    event.preventDefault();

    if($("input[name='proveedor[]']").length < 1 ){
        $("#datagried").addClass('error');
    $("#validar_datagried").text('Seleccione al menos un proveedor');
    }
    else{
        $("#frm_compra").submit();
    }
});


    }); // Fin del documento.ready 
