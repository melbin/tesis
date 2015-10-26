<style type="text/css">
    table th, td {
     border: 0px solid black;
    border-collapse: collapse;
}
th, td {
    padding: 8px;
}
</style>
<form method="POST" action="<?php echo base_url()?>bancos/especificos/crear_detalle_especifico">
    <button type="submit" class="btn btn-outline btn-primary">Crear detalle específico</button>
</form>
<p></p>                
<div class="panel panel-default">
    <div class="panel-heading">
        Específicos
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

        var pathArray = window.location.pathname.split( '/' );
        var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";

        $('#dataTables-example').DataTable({
                responsive: true,
                emptyTable:     "No data available in table",     
        });

    //    $("input[name^='fecha']").mask('99-99-9999');
    //    $("input[name^='fecha']").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});

        // $('[id^=detalle_]').click(function(){
        //     //alert($(this).val());
        //     var id_fila = $(this).attr('cont');
        //     var estado = $.trim($('#estado_'+id_fila).text());
            
        //      if(estado === 'evaluacion'){
        //          $.fancybox("Su solicitud se encuentra actualmente en Abastecimiento, <br> esperando ser aprobada.");    
        //      }
        // });

        // $(".ver_modal").on('click', function(){
        //     $.fancybox({
        //         content: 'En desarrollo...',
        //         scrolling :'no' 
        //     });               
        // });
    });
    </script>                         