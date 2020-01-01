<?php
	
	############################################################################
	# 	
	#	Meteotemplate
	# 	http://www.meteotemplate.com
	# 	Free website template for weather enthusiasts
	# 	Author: Jachym
	#           Brno, Czech Republic
	# 	First release: 2015
	#
	############################################################################
	#
	#	Daylength calculation
	#
	# 	Script to draw day/night graphs.
	#
	#############################################################################
	#	
	#
	# 	v17.2 Nectarine 2019-06-27
	#
	############################################################################
	
	include("../../config.php");
	include($baseURL."header.php");
	include($baseURL."css/design.php");
	$lat = $stationLat;
	$lon = $stationLon;
	
	$y = $_GET['y'];

	?>
	<table class="table" style="width:95%!important;margin-left:auto;margin-right:auto" cellspacing="2">
		<thead>
			<tr>
				<th colspan=13 style="text-align:center"><?php echo lang('month','c')?></th>
			</tr>
			<tr>
				<th></th>
				<?php
				for($i=1;$i<13;$i++){
					echo "<th align=center>".$i."</th>";
				}			
				?>
			</tr>
		</thead>
		<tbody>
		<?php	
			for($i=1;$i<32;$i++){
				echo "<tr><td>".$i.".</td>";
				for($a=1;$a<13;$a++){
					if($a==1|$a==3|$a==5|$a==7|$a==9|$a==11){
						$color="#".$color_schemes[$design2]['800'];
					}
					else{
						$color="#".$color_schemes[$design2]['900'];
					}
					$currentDate = date("U", strtotime($y."-".$a."-".$i));
					$date_check = checkdate($a, $i, $y);

					if($date_check==true){
						$dateTimeZone = new DateTimeZone($stationTZ);
						$dateTime = new DateTime("now", $dateTimeZone);
						$transition = $dateTimeZone->getTransitions(mktime(0, 0, 0, $a, $i, $y),mktime(23, 59, 0, $a, $i, $y)); 
						$current_offset=($transition[0]['offset'])/3600; 
						$rise = date_sunrise($currentDate,SUNFUNCS_RET_TIMESTAMP,$lat,$lon,90.5,$current_offset);
						$set = date_sunset($currentDate,SUNFUNCS_RET_TIMESTAMP,$lat,$lon,90.5,$current_offset);
						$difference = $set - $rise;
						$difference = gmdate("H:i:s", $difference);
						echo "<td style=\"background:".$color."\">".$difference."</td>";
					}
					else{
						echo "<td style=\"background:".$color."\"></td>";
					}
				}
				echo "</tr>";
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td class="rounded-foot-left"></td>
			<td colspan=23></td>
			<td class="rounded-foot-right"></td>
		</tr>
	</tfoot>
	</table>