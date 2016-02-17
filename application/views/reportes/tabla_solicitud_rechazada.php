
<!--    <table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%"> -->
    <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
        <thead>
            <tr class="color_fondo titulo">
                <th style="text-align:center;" width="5%">#</th>
                <th style="text-align:center;" width="15%">Específico</th>
                <th style="text-align:center;" width="15%">Solicitante</th>
                <th style="text-align:center;" width="15%">Fecha de creación</th>
                <th style="text-align:center;" width="15%">Fecha rechazo</th>
                <th style="text-align:center;" width="35%">Observación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($solicitudes as $key => $value) { ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;" width="5%"><?= $value['sol_id']; ?></td>
                    <td style="text-align:center;" width="15%"><?= $value['esp_nombre'] ?></td>
                    <td style="text-align:center;" width="15%"><?= $value['dpi_nombre'] ?></td>
                    <td style="text-align:center;" width="15%"><?= date('d-m-Y', strtotime($value['sol_fecha'])) ?></td>
                    <td style="text-align:center;" width="15%"><?= date('d-m-Y', strtotime($value['res_fecha'])) ?></td>
                    <td style="text-align:center;" width="35%"><?= strstr($value['res_descripcion'],'<br>',true)  ?></td>
                </tr>
                <?php } ?>

            <?php if(count($solicitudes)==0){ ?>
                <tr>
                    <td id="td_temporal" align="center">No se encontraron registros...</td>
                    <td class="drop"></td>
                    <td class="drop"></td>
                    <td class="drop"></td>
                    <td class="drop"></td>
                    <td class="drop"></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    