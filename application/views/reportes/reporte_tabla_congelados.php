
<!--    <table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%"> -->
    <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
        <thead>
            <tr class="color_fondo titulo">
                <th style="text-align:center;">#</th>
                <th style="text-align:center;">Fecha</th>
                <th style="text-align:center;">Fondo</th>
                <th style="text-align:center;">Específico</th>
                <th style="text-align:center;">Saldo actual</th>
                <th style="text-align:center;">Saldo congelado</th>
            </tr>
        </thead>
        <tbody>
            <?php $cont =0; foreach ($saldos_congelados as $key => $value) { ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;"><?= ++$cont ?></td>
                    <td style="text-align:center;"><?= date('d-m-Y g:i A', strtotime($value['foc_fecha'])) ?></td>
                    <td style="text-align:center;"><?= $value['fon_nombre'] ?></td>
                    <td style="text-align:center;"><?= $value['esp_nombre'] ?></td>
                    <td style="text-align:center;">$<?= number_format($value['det_saldo'],2)?></td>
                    <td style="text-align:center;">$<?= number_format($value['foc_cantidad'],2)?></td>
                </tr>
                <?php } ?>

            <?php if($cont==0){ ?>
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
    