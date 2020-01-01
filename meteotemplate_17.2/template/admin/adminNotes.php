<?php
	
	############################################################################
	# 	Meteotemplate
	# 	http://www.meteotemplate.com
	# 	Free website template for weather enthusiasts
	# 	Author: Jachym
	#           Brno, Czech Republic
	# 	First release: 2015
	#
	############################################################################
	#
	#	Info pages setup
	#
	############################################################################
	
	session_start();
	if($_SESSION['user']!="admin"){
		echo "Unauthorized access.";
		die();
	}
	
	include("../config.php");
	include($baseURL."css/design.php");
	include($baseURL."header.php");
	
	if(file_exists("adminNotesText.php")){
		include("adminNotesText.php");
        $textData = urldecode($text);
	}
	else{
		$textData = "These are my notes :)";
	}

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
			<?php include($baseURL."menu.php");?>
		</div>
		<div id="main">
			<div class="textDiv" style="width:90%">
			<h1>Admin Notes</h1>
			<p>This page is just for you to take notes if there is something you want to note down for future reference. The file is saved so that no-one can display it, so it is completely up to you what you write here. Using this is of course optional and information written here will not be used for any other purposes.</p>
			<form action="saveNotes.php" method="POST" target="_blank">
				<textarea name="notes" rows="40" cols="300" style="text-align:justify;cursor:auto;background:white;color:black;font-size:1em;margin:0 auto;padding:5px;max-width:100%;display:block"><?php echo $textData?></textarea>
                <br><br>
				<div style="width:100%;text-align:center">
					<input type="submit" value="Save" class="button2">
				</div>
			</form>
			<br><br>
		</div>
		</div>
		<?php include($baseURL."footer.php");?>		
	</body>
</html>
	