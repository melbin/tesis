 
    <table class="table table-striped table-bordered table-hover"  id="dataTables-example">
            <thead>
                <tr class="color_fondo titulo">
                    <th>Proveedor</th>
                    <th>Categoría</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>U.M</th>
                    <th>Entradas</th>
                    <th>Salidas</th>
                    <th>Existencias</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($query as $key => $value) { ?>
                    <tr class="odd gradeX">
                        <td style="text-align:center;"><?=$value['proveedor']?></td>
                        <td style="text-align:center;"><?=$value['categoria']?></td>
                        <td style="text-align:center;"><?=$value['codigo']?></td>
                        <td style="text-align:center;"><?=$value['nombre']?></td>
                        <td style="text-align:center;"><?=$value['UM']?></td>
                        <td style="text-align:center;"><?=$value['entradas']?></td>
                        <td style="text-align:center;"><?=$value['salidas']?></td>
                        <td style="text-align:center;"><?=$value['existencias']?></td>
                    </tr>
                 <?php } ?> 
            </tbody>   
            <!--
            <?php if(count($query)<1){ ?>
                    <tr>
                        <td id="td_temporal" align="center">No se encontraron registros...</td>
                        <td class="drop"></td>
                        <td class="drop"></td>
                        <td class="drop"></td>
                        <td class="drop"></td>
                        <td class="drop"></td>
                        <td class="drop"></td>
                        <td class="drop"></td>
                    </tr>
            <?php } ?>
            -->
    </table>
