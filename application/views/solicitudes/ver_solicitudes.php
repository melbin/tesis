<style type="text/css">
    table th, td {
     border: 0px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 8px;
}
</style>

<div class="panel panel-default">
    <div class="panel-heading">
        Busqueda
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="box">
      <div class="title">
        <h4 style="text-align:center;">FILTRO PARA SOLICITUDES</h4>
      </div> 
      <div class="content">
        <form method="post" id="consultaform" name="consultaform">
          <table class="responsive table table-bordered" >
            <thead><tr style="font-size:13px;"><th width="25%">Tipo</th><th id="tdFiltro" width="25%">Buscar por</th><th width="35%">Criterio de Búsqueda</th><th width="15%">&nbsp;</th></tr></thead>
            <tbody>
              <tr>
                <td style="vertical-align: top !important;" width="25%" >
                    <div>
                        <select  name="tipo_req" id="tipo_req" onchange="$('#validar_filtro2').text('');" class="nostyle select2" style="width:250px;" placeholder="Seleccione">
                            <?php if(!empty($estado)){ echo $estado;} ?>
                        </select>
                    </div>
                  <span id="validar_filtro2" style="color:red;font-size:11px;"></span>
                </td>
                <td style="vertical-align: top !important;" width="25%">
                    <div>
                        <select  name="tipo_filtro" id="tipo_filtro" onchange="$('#validar_filtro').text('');" class="nostyle select2" style="width:250px;" placeholder="Seleccionar filtro">
                            <option value=""></option>
                            <option value="sol_dpi_id">Por Departamento</option>
                            <option value="sol_fecha">Por Fecha</option>
                            <option value="sol_ali_id">Por Bodega</option>
                        </select>
                    </div>
                  <span id="validar_filtro" style="color:red;font-size:11px;"></span>
                </td>
                <td style="vertical-align: top !important;" width="35%">     
                    <div>
                        <input style="width:89%;"  id="nada" name="nada"  placeholder="Seleccione filtro" readonly type="text">
                    </div>
                    <div id="sol_dpi_id" style="display:none;">
                        <select name="select_sol_dpi_id" id="select_sol_dpi_id" onchange="$('#validar_valor').text('');" class="nostyle select2" style="width:340px;" placeholder="Seleccione">
                            <option value=""></option>
                            <?php  if(!empty($departamentos)) {echo  $departamentos;} ?>
                        </select>
                        <!-- <input style="display:none; width:89%;" onkeyup="$('#validar_valor').text('');" id="sol_dpi_id" name="sol_dpi_id"  placeholder="Departamento" type="text"> -->
                    </div>
                    <div  style="display:none;" id="sol_fecha"> 
                        <input id="fecha1" name="fecha1" onchange="$('#validar_valor').text('');" style="width:44%;"  placeholder="Fecha desde" type="text">
                        <input id="fecha2" name="fecha2"  onchange="$('#validar_valor').text('');" style="width:44%;"  placeholder="Fecha hasta" type="text">
                    </div>
                    <div id="sol_ali_id" style="display:none;" >
                        <select name="select_sol_ali_id" id="select_sol_ali_id" onchange="$('#validar_valor').text('');" class="nostyle select2" style="width:340px;" placeholder="Seleccione">
                            <option value=""></option>
                            <?php  if(!empty($bodegas)) {echo  $bodegas;} ?>
                        </select>
                    </div>
                    <span id="validar_valor" style="color:red;font-size:11px;"></span>
                </td>
                <td style="vertical-align: top !important;" width="15%">
                    <button   class="btn btn-info" type="button" id="consultar_datos" >
                    <span class="icomoon-icon-info-2 white" ></span>
                    Consultar</button>
                </td>
              </tr>
            </tbody>
          </table>
        </form> <!-- Fin formulario filtrar --> 
       </div> <!-- Fin content -->  
    </div> <!-- Fin box -->    
    </div> <!-- Fin panel body -->
</div>    

<div class="panel panel-default">
    <div class="panel-heading">
        Proceso de solicitudes
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="dataTable_wrapper">
            <div id="tabla_dinamica">
                <?php echo $html; ?>    
            </div>
        </div>
    </div> 
</div>

    <script>

    var pathArray = window.location.pathname.split( '/' );
    var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";

    $(document).ready(function() {
        
        $("input[name^='fecha']").mask('99-99-9999');
        $("input[name^='fecha']").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});

        $("#tipo_filtro").change(function(event) {
          $('#validar_valor').text('');  
          mostrarElemento($(this).val());
        });

        $('#dataTables-example').DataTable({
                responsive: true,
                emptyTable:     "No data available in table",     
        });

        $(".select2").select2({
            minimumResultsForSearch: 4,
            placeholder: "Seleccione",
            theme: "classic", // bootstrap
            allowClear: true
        });

        $(document).on("click",'[id^=detalle_]',function(){

            var id_fila = $(this).attr('cont');
            var estado =  $(this).attr('estado');

            switch(estado){
              case "1":   alertify.alert("Su solicitud se encuentra actualmente en <b>Abastecimiento</b>, <br> esperando ser aprobada para enviarse a Financiero.").setHeader('');
                break;
              case "2":   alertify.alert("Los fondos para esta solicitud ya fueron <b>Aprobados</b> por San Salvaodr").setHeader('');
                break; 
              case "3":   cargar_alerta($(this).attr('value'));
                break;
              case "4":   alertify.alert("Esta solicitud se encuentra en <b>Negociación</b> con los Proveedores.").setHeader('');          
                break;
              case "5":   alertify.alert("Esta solicitud <b>ya fue procesada</b> satisfactoriamente.").setHeader('');          
                break;
              case "6":   alertify.alert("Su solicitud se encuentra actualmente en <b>Financiero</b>, <br> esperando ser aprobada.").setHeader('');
                break;
              case "7":   alertify.alert("Su solicitud se encuentra actualmente en <b>San Salvador</b>, <br> Esperando liberación de efectivo.").setHeader('');
                break;
              default:  alertify.alert("Esta solicitud no cuenta con un estado. Favor notificar al Administrador del Sistema.").setHeader('');
            }
        });

        $("#consultar").click(function(event){  // Ya no se esta ocupando
            event.preventDefault();
            var fecha_inicio = $("#fecha_inicio").val();
            var fecha_fin = $("#fecha_hasta").val();
            var depto = $("#depto").val();
            var estado = $("#estado").val();

            $.ajax({
                url: 'filtrar_solicitudes',
                type: 'POST',
                dataType: 'json',
                data: {fecha_inicio:fecha_inicio, fecha_hasta:fecha_fin, depto:depto, estado:estado},
                success:function(data) {
                    $("#tabla_dinamica").html('').hide();
                    $("#tabla_dinamica").html(data.drop).show("slide", { direction: "left" }, 1000);

                    $('#dataTables-example').DataTable({
                        responsive: true,
                        emptyTable: "No existen registros",     
                    });
                }
            });
        });

        $("#consultar_datos").click(function(event) 
        {   
        
        if($("#tipo_req").val() !=""){
           tipo_req = $("#tipo_req").val();

            if($("#tipo_filtro").val() !="" && $("#tipo_filtro").val() !=null){
              tipo=$("#tipo_filtro").val();
              if($("#"+tipo).is("input")) {
                if($("#"+tipo).val() != "" ) { consultarAjax(tipo_req,tipo,$("#"+tipo).val());   }
                else { $("#validar_valor").text('Campo requerido');   }
              }
              else {
                if(tipo=='sol_fecha') { 
                  if($("#fecha1").val() != "" && $("#fecha2").val() != "" ) { var fechas= $("#fecha1").val() + "#" + $("#fecha2").val(); consultarAjax(tipo_req,tipo,fechas);}
                  else { $("#validar_valor").text('Campo requerido');}
                  }
                  else { //es un div con select
                   if($("#select_"+tipo).val() != "" ) { consultarAjax(tipo_req,tipo,$("#select_"+tipo).val());   }
                   else { $("#validar_valor").text('Campo requerido');   }
                  }
               }
            } else { $("#validar_filtro").text('Campo requerido'); }
        }
        else {
            $("#validar_filtro2").text('Campo requerido');
        }
    }); // Fin if consultar_datos
    }); // end document ready

    function mostrarElemento(id_element){
      if(id_element!=""){ id_elemento=id_element; }
      else { id_elemento='nada'; }
      var mostrar = "#"+id_elemento;
      var elementos="#nada, #sol_dpi_id ,#sol_fecha ,#sol_ali_id";
      var excluir =  elementos.replace(mostrar, "#noexiste");
      $(excluir).hide('slow');
      $(mostrar).show('slow');
    }
    
    function cargar_alerta(sol_id)
    {
        $.ajax({
            url: urlj+"home/solicitudes/cargar_alerta_rechazada",
            type: 'POST',
            dataType: 'json',
            data: {id:sol_id},
            success:function(data) {
                alertify.alert(data.drop).setHeader('');    
                //$.fancybox(data.drop);    
            }
        });
    }   

    function consultarAjax(tipo_req,filtro,valor){
     //wait.start();
       $("#consultar_datos").attr('disabled', 'disabled');
       $("#tabla_dinamica").html('').hide("slide", { 
        direction: 'right'
      }, 400, function() { //despues de ocultar div enviar el ajax
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url:  urlj+"home/solicitudes/filtrar_solicitudes",
          data: {'filtro':filtro, 'valor':valor, 'tipo_req':tipo_req},
          success: function (data) {
            $("#tabla_dinamica").html(data.drop);
                 $("#consultar_datos").removeAttr('disabled');
                 $("#tabla_dinamica").show("slide", {
                  direction: 'left'
                }, 500);
               }
             });
      });
}

    </script>                         