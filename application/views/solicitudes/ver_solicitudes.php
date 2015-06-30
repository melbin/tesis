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
    <form class="form-horizontal" autocomplete="off" method="post" id="form_solicitudes" action="<?php echo base_url();?>home/solicitudes/filtrar_solicitudes ">
    <table width="50%" border="0">
        <tr>
            <td width="15%" style="text-align:center"><label>Desde:</label></td><td> <input id="fecha_inicio" name="fecha_inicio" type="text" value="" maxlength="19" class="datetime-input form-control"></td>
            <td><label>Hasta:</label></td><td> <input id="fecha_hasta" name="fecha_hasta" type="text" value="" maxlength="19" class="datetime-input form-control"></td>
        </tr>
        <tr>
        <td width="15%"><label>Departamento:</label></td>
            <td colspan="3">
                <select class="form-control select2" id="depto" name="depto">
                    <?php if(isset($departamentos)) {echo $departamentos;} ?>
                </select>
                <div id="depto_error" style="color:red;font-size:11px;"></div>
            </td>
        </tr>
        <tr>
        <td width="15%"><label>Estado:</label></td>
            <td colspan="3">
                <select class="form-control select2" id="estado" name="estado">
                    <?php if(isset($estado)) {echo $estado;} ?>
                </select>
                <div id="estado_error" style="color:red;font-size:11px;"></div>
            </td>
        </tr>
        <tr><td colspan="4" style="text-align:right; padding:20px;"><button id="consultar" name="consultar" type="button" class="btn btn-info"> <span class="fa fa-search"> Consultar</span></button></td></tr>
    </table>
    </form>
    </div>
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

        $('[id^=detalle_]').click(function(){
            //alert($(this).val());
            var id_fila = $(this).attr('cont');
            var estado = $.trim($('#estado_'+id_fila).text());
            
             if(estado === 'evaluacion'){
                 $.fancybox("Su solicitud se encuentra actualmente en <b>Abastecimiento</b>, <br> esperando ser aprobada.");    
             } else
             if( estado === 'aprobada'){
                 $.fancybox("Su solicitud se encuentra actualmente en <b>Financiero</b>, <br> esperando ser aprobada.");    
             } else
             if(estado === 'rechazada'){
                cargar_alerta($(this).val());
             }
        });

        $("#consultar").click(function(event){
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
    }); // end document ready
    
    function cargar_alerta(sol_id)
    {

        $.ajax({
            url: urlj+"home/solicitudes/cargar_alerta_rechazada",
            type: 'POST',
            dataType: 'json',
            data: {id:sol_id},
            success:function(data) {
                $.fancybox(data.drop);    
            }
        });
    }

    </script>                         