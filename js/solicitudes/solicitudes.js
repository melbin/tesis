    
    $(document).ready(function(){
        $(".unidad_medida").hide();
        $("#fecha_entrega").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});
        $(".select2-selection__clear").live('click',function(){
            alert();
        });

        // Quitar campos requeridos 
        $("#articulo").change(function(){
            $("#articulo_error").text('');
            $("#precio_error").text('');
            $("#um_error").text('');
            $("#cantidad_error").text('');
        });
        $("#dpi_interno").change(function(){
            $("#dpi_interno_error").text('');
        });
        $("#bodega").change(function(){
            $("#bodega_error").text('');
        });
        $("#categoria").change(function(){
            $("#categoria_error").text('');
        });
        $("#fondo").change(function(){
            $("#fondo_error").text('');
        });
        $("#cantidad").change(function(){
            $("#cantidad_error").text('');
        });
        $("#um").change(function(){
            $("#um_error").text('');
        });

        // Codigo para los select
        $(".select2").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });

        // $("#bodega, #proveedor, #entrada, #articulo").selectmenu();
        $(".enteros").validarCampo('0123456789'); 
        $(".decimales").validarCampo('0123456789.,'); 
        $("#numero_entrega").validarCampo('123456789');

        $("#cancelar").live("click", function(){
            // alertify.alert("Pendiente de programar");
            location.reload();
        });

        $("#articulo").change(function(){
            $("#descripcion").attr('disabled',false);
            var articulo = $("#articulo").val();
            // Cargando precio, en proceso.
            $.ajax({
                url: 'obtener_precio',
                type: 'POST',
                dataType: 'json',
                data: {id:articulo},
                success: function(data) {
                    $("#precio").val(data.drop);
                    $(".unidad_medida").show();
                    if(data.existencias.length>0){
                        alertify.success(data.existencias);
                    }
                }
            });            
        });

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
                   $("#precio").val('');
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
                   //alert(data.drop);
                   $("#articulo").select2('destroy').html(data.drop).select2({theme: "classic", allowClear: true});
                   $("#precio").val('');
                }
            });
        });

        $("#bodega").change(function(){
            id_bod=$("#bodega").val();
            $("#lugar_entrega").attr('disabled',false);    

            $.ajax({
                url: 'cargar_direccion',
                type: 'POST',
                dataType: 'json',
                data: {id : id_bod},
                success:function(data) {
                    $("#lugar_entrega").text(data.drop['ali_direccion']);
                }
            });
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

      var row=0;

      $("#add").on("click",function(){
            
       if($.trim($('#precio').val())!='' && $.trim($('#cantidad').val())!='' && $('#articulo').val() !=0  && $('#um').val() !=0 ){
        $("#validar_datagried").text('');
        
        $("#registrar_solicitud").attr('disabled',false);
        $("#anular").attr('disabled',false);
        $('#cabezera').show();
        var N_fila = $("#datagried tbody tr").length + 1;
                var numero_fila = N_fila + 'F' + $("#articulo").val() ; //id unico del tr
                var subtotal = $("#precio").val()*$("#cantidad").val();

                $("#datagried").append('<tr id="'+numero_fila+'"><td><input type="hidden" value="'+numero_fila+'" name="ids_filaP[]" />'
                    
                    +'<input type="hidden" name="categoria[]" id="categoria" value="'+$("#categoria").val()+'"/>'
                    +'<input type="hidden" name="productos[]" id="productos" value="'+$("#articulo").val()+'"/>'
                    +'<input type="hidden" name="unidad_med[]" id="unidad_med" value="'+$("#um").val()+'"/>'
                    +'<input type="hidden" name="financiamiento[]" id="financiamiento" value="'+$("#fondo").val()+'"/>'
                    +'<input type="hidden" name="cantidad[]" id="cantidadi'+row+'" value="'+$("#cantidad").val()+'"/>'
                    +'<input type="hidden" name="precios[]" id="precioi'+row+'" value="'+$("#precio").val()+'"/>'
                    +'<input type="hidden" name="descripcion[]" id="descripcioni'+row+'" value="'+$("#descripcion").val()+'"/>'
                    +'<input type="hidden" name="total[]" id="totali'+row+'" class="activo" value="'+subtotal+'"/>'

                    +'<label name="producto_label" id="productos"/>'+$("#articulo option:selected").text()+'</td>'
                    +'<td><label name="cantidad_label" id="cantidadl'+row+'"/>'+$("#cantidad").val()+'</td>'
                    +'<td><label name="precio_label" id="preciol'+row+'"/>'+$("#precio").val()+'</td>'
                    +'<td><label name="total_label" id="totall'+row+'"/>'+$("#precio").val()*$("#cantidad").val()+'</td>'
                    +'<td><button type="button" id="remove" id_fila="'+numero_fila+'" class="remove" ><span class="glyphicon glyphicon-remove"></span> Anular</button>'
                    /*+'<button type="button" id="editar" name="'+row+'" class="editar" style="width:35px;height:35px;"/></td>*/+'</tr>');
        

                $('#cantidadi'+row+'').val($("#cantidad").val());
                $('#precioi'+row+'').val($("#precio").val());
                $('#descripcioni'+row+'').val($("#descripcion").val());
                $('#totali'+row+'').val($("#precio").val()*$("#cantidad").val());

                $("#cantidad").val('');
                $("#precio").val('');
                $("#descripcion").val('');
                
                sumar_total();
               
                row=row+1;
                $('#sub_categoria').select2('val',0);
                $('#articulo').select2('val',0);

                }else{
                //alert("Debe especificar las características del producto!");
                $('#categoria').addClass('error');
                $('#articulo').addClass('error');
                $('#um').addClass('error');
                if(!$("#cantidad").val()>0) {$('#cantidad_error').text('Campo requerido');} 
                if($("#articulo").val()==0 || $("#articulo").val()==null) {$('#articulo_error').text('Campo requerido');} 
                if($("#um").val()==0) {$('#um_error').text('Campo requerido');}
                if($("#precio").val() =='') {$('#precio_error').text('Campo requerido');}
                if($("#bodega").val()==0) {$('#bodega_error').text('Campo requerido');} 
                if($("#dpi_interno").val()==0) {$('#dpi_interno_error').text('Campo requerido');} 
                if($("#fondo").val()==0) {$('#fondo_error').text('Campo requerido');} 
                if($("#categoria").val()==0) {$('#categoria_error').text('Campo requerido');} 

                alertify.alert("Debe especificar las características del producto");
            }
        });

    $("#remove").live("click", function() {
    $(this).parents("tr").remove();     
      sumar_total();
    });//Fin de eliminar

    $("#registrar_solicitud").click(function(event){
    event.preventDefault();
    if($('#datagried tr').length<=1 ){
        $("#datagried").addClass('error');
    $("#validar_datagried").text('Seleccione al menos un producto');
    }
    else{
        $("#frm_solicitud").submit();
    }
});


    }); // Fin del documento.ready 

    function sumar_total(){
        var total=0;
        $('.activo').each(function(){
        total=parseFloat(total)+parseFloat($(this).val());
    })
        document.getElementById('total_suma_hidden').value=total;
        document.getElementById('total_suma').innerHTML = '<h1>Total: $'+$.number(total,2)+'</h1>';
    }

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
