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
        Listado de Solicitudes
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

<div style="display:none;">
    <div id="aprobar_solicitud" style="width:600px;">
        <div class="panel panel-default">
        <div class="panel-heading">
            <b>Proceder a la adquisición de los productos</b> 
        </div>
        <div class="panel-body">
        <form id="frm_sol_aprobar" name="frm_sol_aprobar" method="POST" action="<?php echo base_url()?>home/financiero/aprobar_solicitud"> 
          <input type="hidden" name="solicitud" id="solicitud">
            <table width="100%" align="left">
                <tr><th></th><th></th></tr>
                <tr>
                <td width="20%" style="vertical-align:top;"><label>Observación:</label></td>
                    <td colspan="2">                        
                        <div class="form-group">    
                            <textarea id="observacion" name="observacion" placeholder="Observación..." style="width:100%"></textarea>
                            <!-- <label id="observacion_error" style="color:red;font-size:11px;"></label> -->
                        </div> 
                    </td>
                </tr>
                <tr>
                    <td><button id="btn_enviar" name="btn_enviar" type="submit" class="btn btn-success"><span class="fa fa-check white"></span> Aceptar</button></td>
                    <td><button id="btn_cancel" name="btn_cancel" type="button" class="btn btn-danger"> <span class="fa fa-times"> Cancelar</span></button></td>
                </tr>
              </table>
            </form>    
        </div>
        </div>     
    </div>
</div> <!-- End div Aprobar Solicitud -->



    <script>
    $(document).ready(function() {

        $("input[name^='fecha']").mask('99-99-9999');
        $("input[name^='fecha']").datepicker({dateFormat: 'dd-mm-yy',changeMonth: true, changeYear: true});

        $('#dataTables-example').DataTable({
                responsive: true,
                "aaSorting": [],
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

        $(".ver_modal").on('click', function(){
            $("#solicitud").val($(this).attr('id_sol'));
            $.fancybox({
                content: $("#aprobar_solicitud"),
                scrolling :'no' 
            });               
        });

        $("#btn_cancel").click(function(){
            $("#motivo").val('');
            $.fancybox.close();
        });

        $("#rechazar").click(function(){
        $.fancybox({
            content: $("#form_eliminar"),
            scrolling :'no' 
        });   
    });

    $("#anular").click(function(){
        if($("#motivo").val()!=''){
            $("#sol_rechazar").submit();     
        } else {
            $("#motivo_error").text('Campo requerido');
        }
    });
    $("#cancel").click(function(){
        $("#motivo").val('');
        $.fancybox.close();
    });
    });
    </script>                         