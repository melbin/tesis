<?php
 foreach ($opciones as $opc) {
	if(empty($opc['sic_padre'])){
		$check = "";
        foreach ($oxr as $oxropc)
        	{
            	if ($opc['sic_id'] == $oxropc['oxr_id_sic'])
                	{
                    	$check = "checked";
                    }
             }             
 ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="Checkbox1" value="<?php echo $opc['sic_id']?>" <?php echo $check ?> onchange="cambiarpermiso(this)" />
	<?php echo $opc['sic_nombre'] ?><br />
<?php 
	foreach ($opciones as $opc2) {
		if($opc['sic_id']==$opc2['sic_padre']){
			$check2 = "";
        foreach ($oxr as $oxropc)
        	{
            	if ($opc2['sic_id'] == $oxropc['oxr_id_sic'])
                	{
                    	$check2 = "checked";
                    }
             }             

 ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="Checkbox1" value="<?php echo $opc2['sic_id']?>" <?php echo $check2 ?> onchange="cambiarpermiso(this)" />
	<?php echo $opc2['sic_nombre'] ?><br />
<?php
	}}}}

	 ?>