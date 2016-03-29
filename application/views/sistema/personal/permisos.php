<script>

var pathArray = window.location.pathname.split( '/' );
var urlj=window.location.protocol+"//"+window.location.host+"/"+pathArray[1]+"/";

 $(document).ready(function () {
 		
        $("#rol").select2();
        $("#opc").select2();
        cargarOpciones();
    });

 function cargarOpciones()
 {
 	rol=$("#rol").val();
 	opc=$("#opc").val();
 	 $.ajax
		({
		    type: 'post',
		    url: urlj+"sistema/personal/opciones",
		    data: { rol: rol, opc: opc },
		    success: function (json) {
		        $('#opciones').html(json);
		    }
		});
 }

 function cambiarpermiso(opc) {
   if (opc.checked)
        url = urlj+"sistema/personal/addopc";
    else
        url = urlj+"sistema/personal/delopc";
    rol = $("#rol").val();
    $.ajax
		({
		    type: 'POST',
		    url: url,
		    data: { rol: rol, opc: opc.value}
		});
 }
</script>

<select id="rol" name="rol" onchange="cargarOpciones()" class="nostyle" style="width:100%">
	<?php
		foreach ($rol as $rol)
		{
	?>
			<option value="<?php echo $rol['rol_id'] ?>"><?php echo $rol['rol_nombre'] ?></option>		
	<?php
		}
	?>	
</select>
<small>Rol</small>

<select id="opc" name="opc" onchange="cargarOpciones()" class="nostyle" style="width:100%">
	<option value="0">Principales</option>
	<?php	
		foreach ($opc as $opc) 
		{
	?>
			<option value="<?php echo $opc['sio_id'] ?>"><?php echo $opc['sio_nombre'] ?></option>		
	<?php
		}
	?>	
</select>
<small>Menú</small>

<div id="opciones"></div>