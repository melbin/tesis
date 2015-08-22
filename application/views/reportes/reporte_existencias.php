<script type="text/javascript">
    var accion_excel="<?php echo base_url('home/reportes/print_existencia/1'); ?>";
    var accion_pdf="<?php echo base_url('home/reportes/print_existencia/2'); ?>";
</script>

<div class="panel panel-default">
        <div class="panel-heading">
            <b>Existencias</b> 
        </div>
       
        <div class="panel-body">
            <!-- All your code here -->
            <form target="_blank" id="frm-descarga" method="POST">
                <div class="form-actions">
                    <button type="button" onclick="javascript: this.form.action=accion_excel; this.form.submit(); " class="btn btn-info" title="Exportar a excel" id="" ><strong><span class="icomoon-icon-file-excel white"></span>Exportar a excel</strong></button>
                    <button type="button"  onclick="javascript: this.form.action=accion_pdf; this.form.submit(); " class="btn btn-info" title="Exportar a PDF" id="" ><strong><span class="icomoon-icon-file-pdf white"></span>Exportar PDF</strong></button>
                </div>
            </form>
            <br><br>
            <div class="content noPad clearfix">
                <!--    <table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%"> -->
                    <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Bodega</th>
                                <th>Categoría</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>U.M</th>
                                <th>Existencia</th>
                                <th>Costo</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( $detalle): 
                            
                            foreach ( $detalle as $sld ) : ?>
                            <tr class="odd gradeX">
                                <td style="text-align:center;"><?=$sld['bodega']?></td>
                                <td style="text-align:center;"><?=$sld['linea']?></td>
                                <td style="text-align:center;"><?=$sld['codigo']?></td>
                                <td style="text-align:center;"><?=$sld['nombre']?></td>
                                <td style="text-align:center;"><?=$sld['UM']?></td>
                                <td style="text-align:right;"><?=number_format($sld['cantidad'],2)?></td>
                                <td style="text-align:right;">$<?=number_format($sld['precio'],2)?></td>
                                <td style="text-align:right;">$<?=number_format($sld['precio']*$sld['cantidad'],2)?></td>
                            </tr>
                        <?php       endforeach;
                        else: ?>
                        <tr class="odd gradeX">
                            <td>0</td>
                            <td>Cero Registros Encontrados</td>
                            <td class="center"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php   endif; ?> 
                </tbody>
            </table>
        </div>
        </div>
</div>            

<!-- <script src="<?=base_url('js/inventario/reportes.js')?>"></script> -->
<script type="text/javascript">
    $(document).ready(function(){
        $('#dataTables-example').DataTable({
                responsive: true,
                emptyTable:     "No data available in table",     
        });
    });
</script>