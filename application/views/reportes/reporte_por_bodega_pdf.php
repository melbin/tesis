<style>
	.tabla{
		font-weight: normal !important;
		width: 100% !important;
		border: 1 solid black;
	}
	.color_fondo {
		background-color: #C0C0C0;
	}	
	.titulo {
		text-align: center;
	}

</style>
<?php 
//echo $table_header;
?>
<br>
<table class="tabla">
	<?php 
	echo isset($html)? $html:null;
	?>
</table>
