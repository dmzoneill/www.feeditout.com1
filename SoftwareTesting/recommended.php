<?php

include("/home/proxykillah/db.php");

if( isset($_GET['rec']) )
{
	$rec = mysql_real_escape_string($_GET['rec']);
	$val = mysql_real_escape_string($_GET['set']);
	$db->query("update SoftwareTesting set important ='$val' where id='$rec'","one");
	print "$val";
	exit;
}

?>
<html>
<head>
<title>Software Testing terms</title>
<style>
body
{
	margin: 5px 5px 5px 5px;
	padding: 5px 5px 5px 5px;
}

a
{
	text-decoration:none;
	color: #000000;
}

.covered
{
	text-decoration:none;
	color: #dd0000;
}

.covered:before
{
	padding-right: 5px;
	content: url(star.png);
}

.notcovered
{
	text-decoration:none;
	color: #000000;
}

.notcovered:before
{
	padding-right: 5px;
	content: url(unstar.png);
}

.let
{
	text-decoration:none;
	color: #4E7FC8;
}

.letter
{
	font-family: verdana, arial;
	font-size:11pt;
	color: #ff6666;
}

h2
{
	font-family: verdana, arial;
	font-size:11pt;
	color: #3666BB;
	margin-bottom: -5px;
}

h3
{
	font-family: verdana, arial;
	font-size:10pt;
	color: #4E7FC8;
	margin-bottom: -5px;
}

p
{
	margin-bottom: -10px;
	margin-left: 30px;
	font-family: verdana, arial;
	font-size:9pt;
	color: #222222;
	
}

.min
{
	font-family: verdana, arial;
	font-size:8pt;
	color: #222222;
	padding-left:5px;
}

.match
{
	font-family: verdana, arial;
	background-color:#FEF1B5;
	color: #222222;
}

.results
{
	width:740px;
	margin-left:20px;
}

.data
{
	
}

.table
{
	width: 100%;
	color: #ffffff;
	background-color: #4E7FC8;
	font-family: verdana, arial;
	border: 0px;
	margin: 0px 0px 0px 0px;
	border-spacing: 0px;
}

.col
{
	background-color: #ffffff;
	font-family: verdana, arial;
	color: #FFFFFF;
	border: 0px;
	margin: 0px 0px 0px 0px;
	border-spacing: 0px;
	padding: 0px 0px 0px 0px;
	
}
</style>
<script type="text/javascript" src="jquery.min.js"></script>
<script language='javascript' type='text/javascript'>

function covered( val )
{
	var update = 0;

	if($("#" + val).css("color")=="rgb(0, 0, 0)")
	{
		update = 1;
	}
	else
	{
		update = 0;
	}

	$.get("recommended.php", 
	{ 
		ajax: "true", 
		rec: val,
		set: update
		}, 
		function(data)
		{
			if(data=="1")
			{	
				$("#" + val).removeClass('notcovered');		
				$("#" + val).addClass('covered');		    	   
			}
			else
			{
				$("#" + val).removeClass('covered');		
				$("#" + val).addClass('notcovered');
			}
   		}
	);
}

</script>
</head>
<body>

<?php


$query = "select * from SoftwareTesting order by term ASC";
$results = $db->query($query,"array");

$mid = ceil(count($results) / 4);

print "<h2>Covered in module CS4004</h2><br /><table>";

for($t=0; $t < $mid; $t++ )
{
	$l1 = $results[$t][1];
	$l1id = $results[$t][0];
	$l2 = $results[$mid + $t][1];
	$l2id = $results[$mid + $t][0];
	$l3 = $results[$mid + $mid + $t][1];
	$l3id = $results[$mid + $mid + $t][0];
	$l4 = $results[$mid + $mid + $mid + $t][1];
	$l4id = $results[$mid + $mid + $mid + $t][0];

	$l1cov = ($results[$t][3]=="1") ? "class='covered'": "class='notcovered'";
	$l2cov = ($results[$mid + $t][3]=="1") ? "class='covered'": "class='notcovered'";
	$l3cov = ($results[$mid + $mid + $t][3]=="1") ? "class='covered'": "class='notcovered'";
	$l4cov = ($results[$mid + $mid + $mid + $t][3]=="1") ? "class='covered'": "class='notcovered'";

	$syn1 = (substr(trim($results[$t][2]),0,3) == "See") ? "class='synonyms'" : "";
	$syn2 = (substr(trim($results[$mid + $t][2]),0,3) == "See") ? "class='synonyms'" : "";
	$syn3 = (substr(trim($results[$mid + $mid + $t][2]),0,3) == "See") ? "class='synonyms'" : "";
	$syn4 = (substr(trim($results[$mid + $mid + $mid + $t][2]),0,3) == "See") ? "class='synonyms'" : "";

	print "<tr>";
	print "<td class='min'><a href=\"javascript:covered('$l1id')\" $l1cov id='$l1id'>$l1</a></td>";
	print "<td class='min'><a href=\"javascript:covered('$l2id')\" $l2cov id='$l2id'>$l2</a></td>";
	print "<td class='min'><a href=\"javascript:covered('$l3id')\" $l3cov id='$l3id'>$l3</a></td>";
	print "<td class='min'><a href=\"javascript:covered('$l4id')\" $l4cov id='$l4id'>$l4</a></td>";
	print "</tr>";
}

print "</table>";

?>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-1014155-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>

