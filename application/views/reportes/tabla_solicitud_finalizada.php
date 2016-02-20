
<!--    <table cellpadding="0" cellspacing="0" border="0" class="responsive dynamicTable display table table-bordered" width="100%"> -->
    <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
        <thead>
            <tr class="color_fondo titulo">
                <th style="text-align:center;" width="5%">#</th>
                <th style="text-align:center;" width="18%">Fondo</th>
                <th style="text-align:center;" width="18%">Específico</th>
                <th style="text-align:center;" width="18%">Solicitante</th>
                <th style="text-align:center;" width="15%">Monto</th>
                <th style="text-align:center;" width="13%">Fecha de creación</th>
                <th style="text-align:center;" width="13%">Fecha de cierre</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($solicitudes as $key => $value) { ?>
                <tr class="odd gradeX">
                    <td style="text-align:center;" width="5%"><?= $value['sol_id']; ?></td>
                    <td style="text-align:center;" width="18%"><?= $value['fon_nombre'] ?></td>
                    <td style="text-align:center;" width="18%"><?= $value['esp_nombre'] ?></td>
                    <td style="text-align:center;" width="18%"><?= $value['dpi_nombre'] ?></td> 
                    <td style="text-align:center;" width="15%">$ <?= number_format($value['des_total'],2) ?></td>
                    <td style="text-align:center;" width="13%"><?= date('d-m-Y', strtotime($value['des_fecha'])) ?></td>
                    <td style="text-align:center;" width="13%"><?= date('d-m-Y', strtotime($value['des_fecha_mod'])) ?></td>
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
                    <td class="drop"></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    