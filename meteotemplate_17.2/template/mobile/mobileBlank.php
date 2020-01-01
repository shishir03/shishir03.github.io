<?php
	############################################################################
	# 	
	#	Meteotemplate
	# 	http://www.meteotemplate.com
	# 	Free website template for weather enthusiasts
	# 	Author: Jachym
	#  Brno, Czech Republic
	# 	First release: 2015
	#
	############################################################################
	#
	#	Main Homepage
	#
	############################################################################
	#	
	#
	# 	v17.2 Nectarine 2019-06-27
	#
	############################################################################
	
	include("../../../config.php");
	include("../../../css/design.php");
	include("../../header.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $pageName?></title>
		<?php metaHeader()?>
		<style>
			
		</style>
	</head>
	<body>
		<div id="main_top">
			<?php bodyHeader();?>
			<?php include("../../menu.php")?>
		</div>
		<div id="main" style="text-align:center">
			
		</div>
		<?php include("../../footer.php")?>
	</body>
</html>