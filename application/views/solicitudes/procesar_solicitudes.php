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
        Solicitudes
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
                 $.fancybox("Su solicitud se encuentra actualmente en Abastecimiento, <br> esperando ser aprobada.");    
             }
        });
    });
    </script>                         