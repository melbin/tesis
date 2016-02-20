
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

        $('#dataTables-example').DataTable({
                responsive: true,
                emptyTable:     "No data available in table",     
        });

    });
</script>        