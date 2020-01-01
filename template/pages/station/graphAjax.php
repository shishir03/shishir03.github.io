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
	#	Interactive Graphs Data
	#
	# 	A script that generates data for the interactive graphs.
	#
	############################################################################
	#	
	#
	# 	v17.2 Nectarine 2019-06-27
	#
	############################################################################
	
	include("../../config.php");
	include($baseURL."header.php");

	// error_reporting(E_ALL);
	
	$parameter = $_GET['parameter'];
	$value = $_GET['value'];
	$interval = $_GET['interval'];
	
	if($interval=="custom"){
		$from = $_GET['from'];
		$to = $_GET['to'];
		$checkFrom = strtotime($from);
		if(date('Y',$checkFrom)<1900 || date('Y',$checkFrom)>2100){
			die();		
		}
		$checkTo = strtotime($to);
		if(date('Y',$checkTo)<1900 || date('Y',$checkTo)>2100){
			die();		
		}
	}
	
	$result = array();
	
	// first select grouping
	if($value=="all"){
		$grouping = "YEAR(DateTime), MONTH(DateTime), DAY(DateTime), HOUR(DateTime), MINUTE(DateTime)";
	}
	if($value=="h"){
		$grouping = "YEAR(DateTime), MONTH(DateTime), DAY(DateTime), HOUR(DateTime)";
	}
	if($value=="d"){
		$grouping = "YEAR(DateTime), MONTH(DateTime), DAY(DateTime)";
	}
	if($value=="m"){
		$grouping = "YEAR(DateTime), MONTH(DateTime)";
	}
	
	// select interval
	if($interval == "1h"){
		$span = "WHERE DateTime >= now() - interval 1 hour";
	}
	if($interval == "today"){
		$span = "WHERE DATE(DateTime) = CURDATE()";
	}
	if($interval == "24h"){
		$span = "WHERE DateTime >= now() - interval 24 hour";
	}
	if($interval == "yesterday"){
		$span = "WHERE DATE(DateTime) = CURDATE() - INTERVAL 1 DAY";
	}
	if($interval == "thisweek"){ // here we have to consider whether user wants Sunday or Monday as first day of the week - set in config file
		$span = "WHERE YEARWEEK(DateTime,$firstWeekday) = YEARWEEK(CURDATE(),$firstWeekday)";
	}
	if($interval == "lastweek"){
		$span = "WHERE YEARWEEK(DateTime,$firstWeekday) = (YEARWEEK(CURDATE(),$firstWeekday)-1)";
	}
	if($interval == "thismonth"){
		$span = "WHERE YEAR(DateTime) = YEAR(CURDATE()) AND MONTH(DateTime) = MONTH(CURDATE())";
	}
	if($interval == "lastmonth"){
		$span = "WHERE YEAR(DateTime) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(DateTime) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)";
	}
	
	if($interval == "custom"){
		$span = "WHERE DateTime >= '$from' AND DateTime <= '$to'";
	}

	if($parameter=="T"){
		$result['name1'] = lang("temperature",'c');
		$result['name2'] = lang("apparent temperature",'c');

		$x = mysqli_query($con,
			"
			SELECT DateTime, avg(T), avg(A), min(Tmin), max(Tmax)
			FROM alldata 
			$span
			GROUP BY $grouping
			ORDER BY DateTime
			"
		);

		while($row = mysqli_fetch_array($x)){
			$date1 = strtotime($row['DateTime']);
			$date1 = array(date('Y',$date1),date('m',$date1),date('d',$date1),date('H',$date1),date('i',$date1));
			$result1 = array();
			$result2 = array();
			$result3 = array();
			array_push($result1,$date1);
			array_push($result1,convertT($row['avg(T)']));
			$result['data1'][] = $result1;
			array_push($result2,$date1);
			array_push($result2,convertT($row['avg(A)']));
			$result['data2'][] = $result2;
			array_push($result3,$date1);
			array_push($result3,convertT($row['min(Tmin)']));
			array_push($result3,convertT($row['max(Tmax)']));
			$result['data3'][] = $result3;
		}
	}
	
	if($parameter=="H"){
		$result['name1'] = lang("humidity",'c');
		$result['name2'] = lang('range','c');
		$x = mysqli_query($con,
			"
			SELECT DateTime, avg(H), min(H), max(H)
			FROM alldata 
			$span
			GROUP BY $grouping
			ORDER BY DateTime
			"
		);

		while($row = mysqli_fetch_array($x)){
			$date1 = strtotime($row['DateTime']);
			$date1 = array(date('Y',$date1),date('m',$date1),date('d',$date1),date('H',$date1),date('i',$date1));
			$result1 = array();
			$result2 = array();
			array_push($result1,($date1));
			array_push($result1,$row['avg(H)']);
			$result['data1'][] = $result1;
			array_push($result2,($date1));
			array_push($result2,$row['min(H)']);
			array_push($result2,$row['max(H)']);
			$result['data2'][] = $result2;
		}
	}
	
	if($parameter=="D"){
		$result['name1'] = lang("dewpoint",'c');
		$result['name2'] = lang('range','c');
		$x = mysqli_query($con,
			"
			SELECT DateTime, avg(D), min(D), max(D)
			FROM alldata 
			$span
			GROUP BY $grouping
			ORDER BY DateTime
			"
		);

		while($row = mysqli_fetch_array($x)){
			$date1 = strtotime($row['DateTime']);
			$date1 = array(date('Y',$date1),date('m',$date1),date('d',$date1),date('H',$date1),date('i',$date1));
			$result1 = array();
			$result2 = array();
			array_push($result1,($date1));
			array_push($result1,convertT($row['avg(D)']));
			$result['data1'][] = $result1;
			array_push($result2,($date1));
			array_push($result2,convertT($row['min(D)']));
			array_push($result2,convertT($row['max(D)']));
			$result['data2'][] = $result2;
		}
	}
	
	if($parameter=="P"){
		$result['name1'] = lang("pressure",'c');
		$result['name2'] = lang('range','c');
		$x = mysqli_query($con,
			"
			SELECT DateTime, avg(P), min(P), max(P)
			FROM alldata 
			$span
			GROUP BY $grouping
			ORDER BY DateTime
			"
		);

		while($row = mysqli_fetch_array($x)){
			$date1 = strtotime($row['DateTime']);
			$date1 = array(date('Y',$date1),date('m',$date1),date('d',$date1),date('H',$date1),date('i',$date1));
			$result1 = array();
			$result2 = array();
			array_push($result1,($date1));
			array_push($result1,convertP($row['avg(P)']));
			$result['data1'][] = $result1;
			array_push($result2,($date1));
			array_push($result2,convertP($row['min(P)']));
			array_push($result2,convertP($row['max(P)']));
			$result['data2'][] = $result2;
		}
	}
	
	if($parameter=="S"){
		$result['name1'] = lang("solar radiation",'c');
		$result['name2'] = lang('range','c');
		$x = mysqli_query($con,
			"
			SELECT DateTime, avg(S), min(S), max(S)
			FROM alldata 
			$span
			GROUP BY $grouping
			ORDER BY DateTime
			"
		);

		while($row = mysqli_fetch_array($x)){
			$date1 = strtotime($row['DateTime']);
			$date1 = array(date('Y',$date1),date('m',$date1),date('d',$date1),date('H',$date1),date('i',$date1));
			$result1 = array();
			$result2 = array();
			array_push($result1,($date1));
			array_push($result1,$row['avg(S)']);
			$result['data1'][] = $result1;
			array_push($result2,($date1));
			array_push($result2,$row['min(S)']);
			array_push($result2,$row['max(S)']);
			$result['data2'][] = $result2;
		}
	}
	
	if($parameter=="W"){
		$result['name1'] = lang("wind speed",'c');
		$result['name2'] = lang("wind gust",'c');
		$result['name3'] = lang('maximumAbbr','c');

		$x = mysqli_query($con,"
				SELECT DateTime, avg(W), avg(G), min(W), max(G)
				FROM alldata 
				$span
				GROUP BY $grouping
				ORDER BY DateTime
				"
		);
		while($row = mysqli_fetch_array($x)){
			$date1 = strtotime($row['DateTime']);
			$date1 = array(date('Y',$date1),date('m',$date1),date('d',$date1),date('H',$date1),date('i',$date1));
			$result1 = array();
			$result2 = array();
			$result3 = array();
			array_push($result1,($date1));
			array_push($result1,convertW($row['avg(W)']));
			$result['data1'][] = $result1;
			array_push($result2,($date1));
			array_push($result2,convertW($row['avg(G)']));
			$result['data2'][] = $result2;
			array_push($result3,($date1));
			array_push($result3,convertW($row['min(W)']));
			array_push($result3,convertW($row['max(G)']));
			$result['data3'][] = $result3;
		}
	}
	
	if($parameter=="R"){
		if($value=="all" || $value=="h"){
			$cumulativeRain = 0;
			$lastRain = 0;
			$x = mysqli_query($con,
				"
				SELECT DateTime, max(R)
				FROM alldata 
				$span
				GROUP BY $grouping
				ORDER BY DateTime
				"
			);
			while($row = mysqli_fetch_array($x)){
				$date1 = strtotime($row['DateTime']);
				$date1 = array(date('Y',$date1),date('m',$date1),date('d',$date1),date('H',$date1),date('i',$date1));
				$result1 = array();
				$result2 = array();
				$thisRain = convertR($row['max(R)']) - $lastRain;
				if($thisRain<0){
					$thisRain = 0;
				}
				$cumulativeRain += $thisRain;
				$lastRain = convertR($row['max(R)']);
				$result1[] = $date1;
				$result1[] = $thisRain;
				$result['data'][] = $result1;
				$result2[] = $date1;
				$result2[] = $cumulativeRain;
				$result['data2'][] = $result2;
			}
		}
		if($value=="d"){
			$cumulativeRain = 0;
			$lastRain = 0;
			$x = mysqli_query($con,
				"
				SELECT DateTime, max(R)
				FROM alldata 
				$span
				GROUP BY $grouping
				ORDER BY DateTime
				"
			);
			while($row = mysqli_fetch_array($x)){
				$date1 = strtotime($row['DateTime']);
				$date1 = array(date('Y',$date1),date('m',$date1),date('d',$date1),date('H',$date1),date('i',$date1));
				$result1 = array();
				$result2 = array();
				$thisRain = convertR($row['max(R)']);
				if($thisRain<0){
					$thisRain = 0;
				}
				$cumulativeRain += $thisRain;
				$lastRain = convertR($row['max(R)']);
				$result1[] = $date1;
				$result1[] = $thisRain;
				$result['data'][] = $result1;
				$result2[] = $date1;
				$result2[] = $cumulativeRain;
				$result['data2'][] = $result2;
			}
		}
		if($value=="m"){
			$cumulativeRain = 0;
			$x = mysqli_query($con,
				"
				SELECT YEAR(DateTime), MONTH(DateTime), DAY(DateTime), max(R)
				FROM alldata 
				$span
				GROUP BY YEAR(DateTime), MONTH(DateTime), DAY(DateTime)
				ORDER BY DateTime
				"
			);
			while($row = mysqli_fetch_array($x)){
				$month = strtotime($row['YEAR(DateTime)']."-".$row['MONTH(DateTime)']."-15");
				$temporaryData[$month][] = convertR($row['max(R)']);
			}
			foreach($temporaryData as $key=>$thisData){
				$date1 = array(date('Y',$key),date('m',$key),date('d',$key),date('H',$key),date('i',$key));
				$result1 = array();
				$result2 = array();
				$thisRain = array_sum($thisData);
				$cumulativeRain += $thisRain;
				$result1[] = $date1;
				$result1[] = $thisRain;
				$result['data'][] = $result1;
				$result2[] = $date1;
				$result2[] = $cumulativeRain;
				$result['data2'][] = $result2;
			}
		}
	}
	
	print json_encode($result);
?>