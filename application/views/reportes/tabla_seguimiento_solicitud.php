<?php 
if(!empty($pdf)){ ?>
    <style type="text/css">
    table, th, td {
    border: 0.1px solid gray;
<?php } ?>
}
    
</style>
<!--    <table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%"> -->
    <table class="table table-striped table-bordered table-hover"  id="dataTables-example" border="0">
        <thead>
            <tr class="color_fondo titulo">
                <th style="text-align:center;" width="5%">#</th>
                <th style="text-align:center;" width="15%">Solicitante</th>
                <th style="text-align:center;" width="15%">Fecha</th>
                <th style="text-align:center;" width="20%">Persona</th>
                <th style="text-align:center;" width="45%">Acci&oacute;n</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($solicitudes as $key => $value) { ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;" width="5%"><?= $value['sol_id']; ?></td>
                    <td style="text-align:center;" width="15%"><?= $value['dpi_nombre']; ?></td> 
                    <td style="text-align:center;" width="15%"><?= date('d-m-Y g:i A', strtotime($value['emh_fecha'])) ?></td>
                    <td style="text-align:center;" width="20%"><?= $value['per_nombre'].' '.$value['per_apellido'] ?></td>
                    <td style="text-align:center;" width="45%"><?= $value['emh_descripcion'] ?></td> 
                </tr>
                <?php } ?>

            <?php if(count($solicitudes)==0){ ?>
                <tr>
                    <td id="td_temporal" align="center">No se encontraron registros...</td>
                    <td class="drop"></td>
                    <td class="drop"></td>
                    <td class="drop"></td>
                    <td class="drop"></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    