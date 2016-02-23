    
    $(document).ready(function(){
        $(".unidad_medida").hide();
        //$("#fecha_entrega").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});
        $(".select2-selection__clear").live('click',function(){
            
        });

        if($("#lugar_entrega").text()!=''){
            $("#lugar_entrega").attr('disabled',false);
        }

        var pathArray = window.location.pathname.split( '/' );
        var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";

        $("#fondo").on('change', function(){
        $("#especifico, #dpi_interno").select2("val", "");
            
        var fondo = parseInt($(this).val());
        if(fondo>0){
            $.ajax({
            url: urlj+'bancos/especificos/get_especifico_fondo',
            type: 'POST',
            dataType: 'json',
            data: {fondo : fondo},
            success:function(data) {
              $("#especifico").html(data.especificos_origen);
            }
        });
        } else {
            $("#especifico").html("<option value='0' saldo='0'>Seleccione</option>").trigger('change');
        }
        });

        setTimeout(function(){
        $("#especifico").on('change', function(){
        $("#dpi_interno").select2("val", "");    
        var esp_id = $(this).val();
        var fondo_id = $("#fondo").val();
        var dpi_id =  $("#dpi_interno option:selected").val();
        if(esp_id>0){
            $.ajax({
            url: urlj+'bancos/especificos/get_departamento_asignaciones',
            type: 'POST',
            dataType: 'json',
            data: {esp_id:esp_id, fondo_id:fondo_id, dpi_id:dpi_id},
            success:function(data) {
                if(data.congelado){
                    alertify.alert('Este específico posee saldo Congelado<br>por lo tanto, No se pueden crear solicitudes con él.').setHeader('').setHeader('');
                    $("#dpi_interno").html('');
                } else {
                    $("#dpi_interno").html(data.depto_asignaciones);      
                }
            }
        });
        } else {
            $("#dpi_interno").html("<option value='0' saldo='0'>Seleccione</option>").trigger('change');
        }
        });
        }, 500);

        $("#dpi_interno").on('change', function(){
        var axd_id = $("#dpi_interno option:selected").attr('axd_id');
        var dpi_id = $(this).val();
        
        if(dpi_id>0){
            $.ajax({
            url: urlj+'bancos/especificos/get_saldo_dpi_asignado',
            type: 'POST',
            dataType: 'json',
            data: {axd_id:axd_id},
            success:function(json) {
              $("#dpi_monto_asignado").val(json.monto);
              $(".asignacion_depto").val(json.axd_id);
              $("#axd_id").val(axd_id);  // Esta linea es reciente, no se que le paso a la anterior.
              $("#categoria").prop('disabled', false);
              alertify.success('Monto asignado: <b>$'+$.number(json.monto,2,'.',',')+'</b>');
            }
        });
        }
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
            $("#dpi_interno-error").text('');
        });
        $("#bodega").change(function(){
            $("#bodega_error").text('');
            $("#bodega-error").text('');
        });
        $("#categoria").change(function(){
            $("#categoria_error").text('');
        });
        $("#fondo").change(function(){
            $("#fondo_error").text('');
            $("#fondo-error").text('');
        });

        $("#especifico").change(function(){
            $("#especifico_error").text('');
            $("#especifico-error").text('');
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
                //url: 'obtener_precio',
                url: urlj+"home/solicitudes/obtener_precio",
                type: 'POST',
                dataType: 'json',
                data: {id:articulo},
                success: function(data) {
                    $("#precio").val(data.drop);
                    $("#um").val(data.um);
                    $(".unidad_medida").show();
                    if(data.existencias.length>0){
                        alertify.success(data.existencias);
                    }
                }
            });            
        });


        $("#categoria").on("select2:open", function(){
            if($('#datagried tr').length>1 ){
                alertify.alert("Por favor seleccione artículos de una sola categoría.").setHeader('').setHeader('');  
            }
        });

        $("#categoria").change(function(){
         
            var id_cat = $("#categoria").val();
            $("#sub_categoria, #articulo").select2("val", "");
            $("#sub_categoria").attr('disabled',false);
            $(".unidad_medida").hide();

            $.ajax({
                url: urlj+"home/solicitudes/cargar_subcategorias",
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
            $(".unidad_medida").hide();
            $("#articulo").select2("val", "");
            if($.isNumeric(id_sub) && id_sub>0){
                $.ajax({
                    url: urlj+"home/solicitudes/cargar_productosxsubcategoria",
                    type: 'POST',
                    dataType: 'json',
                    data: {id : id_sub},
                    success:function(data) {
                       //alert(data.drop);
                       $("#articulo").select2('destroy').html(data.drop).select2({theme: "classic", allowClear: true});
                       $("#precio").val('');
                    }
                });
            }   
        });

        $("#bodega").change(function(){
            var id_bod=$("#bodega").val();
            $("#lugar_entrega").val('');
            $("#lugar_entrega").attr('disabled',false);    
            if($.isNumeric(id_bod) && id_bod>0){
                $.ajax({
                    url: urlj+"home/solicitudes/cargar_direccion",
                    type: 'POST',
                    dataType: 'json',
                    data: {id : id_bod},
                    success:function(data) {
                        $("#lugar_entrega").val(data.drop['ali_direccion']);
                    }
                });
            } else {
                $("#lugar_entrega").val('').attr('disabled',true);
            }    
        });

        jQuery.validator.addMethod("selectNone",function(value, element) { 
            if (element.value == "0") { 
                return false; 
            }       
                else return true; 
            }, 
            "Please select an option." 
        );

         $("#frm_solicitud").validate({
            // Specify the validation rules
            ignore: "",
        rules: {
            fecha_entrega: "required",
            fondo: {
                required: true,
                min: 1
            },
            bodega: {
                required: true,
                min: 1
            },    
            dpi_interno: {
                required: true,
                min: 1
            },
            categoria: {
                required: true,
                min: 1
            },
            especifico : {
              required: true,
                min: 1  
            }, 
            lugar_entrega: {
                required: true,
                minlength: 5
            }, 
        },

        // Specify the validation error messages
        messages: {
            fecha_entrega: "Seleccione una fecha de registro",
            lugar_entrega: "Seleccione una dirección",
            bodega: "Seleccione una bodega",      
            categoria: "Seleccione una categoría",      
            dpi_interno: "Seleccione un departamento",
            especifico: "Seleccione un especifico",
            fondo: "Seleccione un fondo",
            lugar_entrega: {
                 required: "Ingrese un lugar de entrega",
                 minlength: "Debe colocar al menos 5 caracteres"
            },
        },
        errorPlacement: function (error, element) {
            var nombre=$(element).attr("id");
            console.log(nombre+':: '+error);
            $('#'+nombre+'_error').html(error);
        },
        submitHandler: function(form) {
            $("#fondo, #especifico, #dpi_interno, #categoria").attr('disabled',false);
            setTimeout(function(){ 
               form.submit();     
            }, 300);
            
        }

        }); // Fin validar Formulario

      var row=0;

      $("#add").on("click",function(){
            
       if($.trim($('#precio').val())!='' && $.trim($('#cantidad').val())!='' && $('#articulo').val() !=0  && $('#um').val() !=0 && $("#fondo").val()>0 && $("#especifico").val()>0 && $("#dpi_interno").val()>0 ){
        $("#validar_datagried").text('');
       
        if((parseFloat($("#total_suma_hidden").val()) + parseFloat($("#precio").val()*$("#cantidad").val())) <= parseFloat($("#dpi_monto_asignado").val())){

        $('#cabezera').show();
        var N_fila = $("#datagried tbody tr").length + 1;
                var numero_fila = N_fila + 'F' + $("#articulo").val() ; //id unico del tr
                var subtotal = $("#precio").val()*$("#cantidad").val();

                $("#datagried").append('<tr id="'+numero_fila+'"><td><input type="hidden" value="'+numero_fila+'" name="ids_filaP[]" />'
                    
                    // +'<input type="hidden" name="categoria[]" id="categoria" value="'+$("#categoria").val()+'"/>'
                    +'<input type="hidden" name="productos[]" id="productos" value="'+$("#articulo").val()+'"/>'
                    // +'<input type="hidden" name="unidad_med[]" id="unidad_med" value="'+$("#um").val()+'"/>'
                    +'<input type="hidden" name="financiamiento[]" id="financiamiento" value="'+$("#fondo").val()+'"/>'
                    +'<input type="hidden" name="cantidad[]" id="cantidadi'+row+'" value="'+$("#cantidad").val()+'"/>'
                    +'<input type="hidden" name="precios[]" id="precioi'+row+'" value="'+parseFloat($("#precio").val()).toFixed(2)+'"/>'
                    +'<input type="hidden" name="descripcion[]" id="descripcioni'+row+'" value="'+$("#descripcion").val()+'"/>'
                    +'<input type="hidden" name="total[]" id="totali'+row+'" class="activo" value="'+parseFloat(subtotal).toFixed(2)+'"/>'

                    +'<label name="producto_label" id="productos"/>'+$("#articulo option:selected").text()+'</td>'
                    +'<td><label name="cantidad_label" id="cantidadl'+row+'"/>'+$("#cantidad").val()+'</td>'
                    +'<td><label name="precio_label" id="preciol'+row+'"/>'+parseFloat($("#precio").val()).toFixed(2)+'</td>'
                    +'<td><label name="total_label" id="totall'+row+'"/>'+parseFloat($("#precio").val()*$("#cantidad").val()).toFixed(2)+'</td>'
                    +'<td><button type="button" id="remove" id_fila="'+numero_fila+'" class="remove" ><span class="glyphicon glyphicon-remove"></span> Anular</button>'
                    /*+'<button type="button" id="editar" name="'+row+'" class="editar" style="width:35px;height:35px;"/></td>*/+'</tr>');
        

                // $('#cantidadi'+row+'').val($("#cantidad").val());
                // $('#precioi'+row+'').val($("#precio").val());
                // $('#descripcioni'+row+'').val($("#descripcion").val());
                // $('#totali'+row+'').val($("#precio").val()*$("#cantidad").val());

                $("#cantidad").val('');
                $("#precio").val('');
                $("#descripcion").val('');
                
                sumar_total();
               
                row=row+1;
                $('#sub_categoria').select2('val',0);
                $('#articulo').select2('val',0);

                // Todas los seteos aca
                $("#registrar_solicitud").attr('disabled',false);        
                $("#anular").attr('disabled',false);
                $("#fondo, #especifico, #dpi_interno, #categoria").attr('disabled',true);

                } else {
                    alertify.alert("La suma del costo de los productos solicitados <b>excede</b> el monto asignado.<br>Saldo asignado: <b>$ "+$.number($("#dpi_monto_asignado").val(),2,'.',',')+"</b>.").setHeader('');
                }

                }else{
                //alert("Debe especificar las características del producto!");
                var prueba = $("#dpi_interno").val();
                $('#categoria').addClass('error');
                $('#articulo').addClass('error');
                $('#um').addClass('error');
                if(!$("#cantidad").val()>0) {$('#cantidad_error').text('Campo requerido');} 
                if($("#articulo").val()==0 || $("#articulo").val()==null) {$('#articulo_error').text('Campo requerido');} 
                if($("#um").val()==0) {$('#um_error').text('Campo requerido');}
                if($("#precio").val() =='') {$('#precio_error').text('Campo requerido');}
                if($("#bodega").val() ==0 || $("#bodega").val()==null) {$('#bodega_error').text('Campo requerido');} 
                if($("#dpi_interno").val() == 0 || $("#dpi_interno").val() == null ) {$('#dpi_interno_error').text('Campo requerido');} 
                if($("#fondo").val() == 0 || $("#fondo").val()== null) {$('#fondo_error').text('Campo requerido');} 
                if($("#categoria").val() == 0 || $("#categoria").val()== null) {$('#categoria_error').text('Campo requerido');} 
                if($("#especifico").val() ==0 || $("#especifico").val() == null) {$("#especifico_error").text('Campo requerido');}
                var asterisco = '*';
                alertify.alert("Los campos con <b><font color='red'>*</font></b> en rojo son requeridos.").setHeader('');
            }
        });

    // $("#remove").live("click", function() {
    // $(this).parents("tr").remove();     
    //   sumar_total();
      
    //   if($('#datagried tr').length<=1 ){
    //         $("#fondo, #especifico, #dpi_interno, #categoria").attr('disabled',false);  
    //     }    
    // });//Fin de eliminar

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
