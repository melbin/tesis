<div class="panel panel-default">
    <div class="panel-heading">
    </div>
    <div class="panel-body">

		<table border="0" align="center">
			<tr>

			<?php 
				foreach ($opcion_menu as $key => $value) {
					?>
						<td rowspan="6" align="center">
							<table>
								<tr>
									<td>
									<a class="btn btn-info btn-circle btn-lg botones_maestro" href="<?php echo mb_strtolower(base_url() . $value['sio_nombre'] .'/'. $value['sio_controlador'],'UTF-8'); ?>">               
										<i class="<?php echo $value['sio_icono'];?>"></i>
									</a>
									</td>
								</tr>
								<tr><td align="justify"><?php echo $value['sio_nombre']; ?></td></tr>
							</table>
						</td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<?php
				}
			 ?>
				
			</tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
		</table>
	</div>
</div>
<script type="text/javascript">

	$(document).ready(function(){
	//	alert("<?php echo base_url();?>");
	/*	$(document).on('click', '.botones_maestro', function(e){
			e.preventDefault();
   			var id_boton = $(this).attr("value");
			$.ajax({
				  method: "POST",
				  dataType: "html",
				  url:   "<?php echo base_url(); ?>"+"menu/cargar_menu",
				  data: { id: id_boton}
				
				}).done(function(msg) {
				    //alert( "Valor: " + msg);
				    $("#menu").html(msg);
				    $("#menu").show(); 
				  });
				});
*/
/*		$(".botones_maestro").on('click','', function(){
			//alert($(this).attr("value"));
			var id_boton = $(this).attr("value");
			$.ajax({
				  method: "POST",
				  dataType: "html",
				  url:   "<?php echo base_url(); ?>"+"menu/cargar_menu",
				  data: { id: id_boton}
				})
				  .done(function(msg) {
				    //alert( "Valor: " + msg);
				    $("#menu").html(msg);
				    $("#menu").show(); 
				  });

		}); */
	});
</script>