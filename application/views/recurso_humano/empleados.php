
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

	<div style='height:20px;'></div>  

    <div class="panel panel-default">
        <div class="panel-heading">
        </div>
        <div class="panel-body">
        	<?php echo $output; ?>
       </div>
    </div> 

<script>
    $(document).ready(function() {
        $("#field-per_telefono").mask('9999-9999');
        $("input[name*='fecha']").mask("99/99/9999");    
        $("input[name='per_edad']").on('keyup', function(){
           var numero = $(this).val();
           var tmp = numero.replace(/^[0]/g,'');
           $(this).val(tmp.replace(/[^0-9\.]/g,''));
        });
        //$("#per_fecha_nac_input_box").html($("#per_fecha_nac_input_box").html().replace('(dd/mm/yyyy) hh:mm:ss','(dd/mm/yyyy)'));

        $('#dataTables-example').DataTable({
                responsive: true,
                emptyTable:     "No data available in table",     
        });

    });
</script>        