$(document).ready(function(){
	// Write your code here...
    var pathArray = window.location.pathname.split( '/' );
    var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";

	  //$("#fecha_registro").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});

	  $(".select2").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });
	  $("#saldo, #cantidad").numeric('.');

	  $("#cantidad").blur(function(){
	  	$("#cantidad_error").text('');
	  });

      $("#saldo").focusout(function(){
         var fondo = parseFloat(($("#fondo option:selected").attr('saldo'))) + parseFloat($("#saldo_origen").val());

            if(parseFloat($(this).val()) > fondo){
                alertify.alert("Debe especificar una cantidad menor.").setHeader('');
                if($("#saldo_origen").val()!=null){
                    $("#saldo").val((parseFloat($("#saldo_origen").val())).toFixed(2));    
                } else {
                    $("#saldo").val('');
                }
            } else {
                $("#total_restante_hidden").val($(this).val());
            }
      });

	  $("#fondo").on('change', function(){
        if($(this).select2('val')!==null){
            //alert($("#fondo option:selected").attr('saldo'));    
            alertify.success("El saldo de este fondo es: <b>$"+$.number($("#fondo option:selected").attr('saldo'),2)+'<b>');
        }
	  });

	  var row=0;
      $("#agregar").on("click",function(){
       if($.trim($('#cantidad').val())!='' && $('#depto').val()>0 && $('#especifico').val()>0 && $('#fondo').val()!=0 && $.trim($("#descripcion").val()) != ''){
        if(parseFloat($("#total_restante_hidden").val())  >= parseFloat($("#cantidad").val())){ 
            var repetido=0; 
            $(".departamentos").each(function(){
               if($("#depto").select2('val')===$(this).val()){
                    alertify.error("Ya posee asignación");
                    repetido=1;
                    return false;
               }   
            });
            if(repetido === 0){
                $("#validar_datagried").text('');
                $("#saldo").attr('disabled',true);
                $("#fondo").attr('disabled',true);
                $("#especifico").attr('disabled',true);
                $("#registrar_entrada").attr('disabled',false);
                $('#cabezera').show();
         
                var N_fila = $("#datagried tbody tr").length + 1;
                var numero_fila = N_fila + 'F' + $("#depto").val() ; //id unico del tr

                $("#datagried").append('<tr id="'+numero_fila+'"><td><input type="hidden" value="'+numero_fila+'" name="ids_filaP[]" />'
                    +'<input type="hidden" name="departamentos[]" class="departamentos" value="'+$("#depto").val()+'"/>'
                    // +'<input type="hidden" name="descripcion[]" id="descripcioni'+row+'" value="'+$("#descripcion").val()+'"/>'
                    +'<label>'+N_fila+'</label></td>'
                    +'<td><label name="esp_label" id="esp_label">'+$("#especifico option:selected").text()+'</label></td>'
                    +'<td><label name="depto_label" id="depto_label"/>'+$("#depto option:selected").text()+'</td>'
                    +'<td><input name="cantidad_depto[]" id="cantidad_depto_'+row+'" class="monto_asignado" style="text-align:center;" value="'+$("#cantidad").val()+'"/></td>'
                    +'<td><button type="button" id="remove" id_fila="'+numero_fila+'" class="remove" ><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>'
                    +'</tr>');

                    $("#cantidad").val('');
                    restante(0);
                    row=row+1;
                    $('#depto').select2('val',0);
                    $(".monto_asignado").validarCampo('0123456789.');
                    $(".monto_asignado").focusout(function(){
                        $(this).css('background-color','#FFFFFF');
                        restante($(this).attr('id'));
                    }); 
            } else {
                $("#cantidad").val('');
                $('#depto').select2('val',0);
            }      
        }// End fondo > cantidad
        else {
                $("#cantidad").val('');
                if(parseFloat($("#total_restante_hidden").val())==0){
                    alertify.error("Sin fondos para asignar.");        
                } else 
                {
                    alertify.error("Asignar una cantidad menor.");    
                }
                
            }
            }else{
            $('#depto').addClass('error');
            if(!$("#cantidad").val()>0) {$('#cantidad_error').text('Campo requerido');} 
            if($.trim($("#descripcion").val()) == '') {$('#descripcion_error').text('Campo requerido');} 
            if($("#depto").val()==0 || $("#depto").val()==null) {$('#depto_error').text('Campo requerido');}
            if($("#especifico").val()==0 || $("#especifico").val()==null) {$('#especifico_error').text('Campo requerido');} 
            if($("#fondo").val()==0) {$('#fondo_error').text('Campo requerido');} 
            alertify.alert("Debe especificar las características del producto").setHeader('');
        }
        });
    
    $("#remove").live("click", function(){
    	$(this).parents("tr").remove();
        if($('#datagried tr').length<=1 && parseFloat($("#monto_asignado_total").val())!=='-1'){
            $("#total_restante").text('');
            $("#total_restante_hidden").val('');
            $("#saldo").val('').attr('disabled',false);
            $("#fondo").attr('disabled',false);
            $("#especifico").attr('disabled',false);
            $("#registrar_entrada").attr('disabled',true);
            $("#saldo").focusout();
        } else 
        if($('#datagried tr').length <= 1){
            $("#total_restante").text('');
            $("#total_restante_hidden").val('');
            $("#saldo").val('').attr('disabled',false);
            $("#fondo").attr('disabled',false);
            $("#especifico").attr('disabled',false);
            $("#registrar_entrada").attr('disabled',true);
            $("select").each(function (){
                $(this).select2('val','');
            });

        } else {
            restante(0);    
        }
    });//Fin de eliminar

     $("#registrar_entrada").click(function(event){
        event.preventDefault();
        if($('#datagried tr').length <= 1){
            $("#datagried").addClass('error');
        $("#validar_datagried").text('Seleccione al menos un producto');
        }
        else{
            $("#saldo").attr('disabled',false);
            setTimeout(function(){ $("#pro_entrada").submit(); },200);
            
        }
     });

}); // End document ready

    function restante(id){

        event.preventDefault();
        var total=parseFloat($("#saldo").val());
        var valor = 0;

        $('.monto_asignado').each(function(){
            if($(this).val()!=='' && $(this).val()!==null){
                $(this).val().replace(',', '');
                valor = parseFloat($(this).val());
            }

            total=total-valor;
                
            if(parseFloat(total)<0){
                if(parseFloat($('#'+id).attr('saldo_reserva'))>0){
                    $('#'+id).val($.number($('#'+id).attr('saldo_reserva'),2));  // Cuando se edita                  
                } else {
                    $('#'+id).val('1.00');                                       // Editar o agregar   
                }

                setTimeout(function(){ $('#'+id).css('background-color','#FE2E2E'); }, 200);
                alertify.error("Asignar una cantidad menor."); 
               restante(0);
               return false;
            }
        }); // End foreach

            if(total>=0){
                document.getElementById('total_restante_hidden').value=total;
                document.getElementById('total_restante').innerHTML = '<h1>Saldo pendiente: $'+$.number(total,2)+'</h1>';    
                document.getElementById('monto_asignado_total').value=valor;
            }
        } // End restante
