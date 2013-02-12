<?php /**/ ?><?php

include("/home/proxykillah/db.php");

if( isset($_GET['searchTerm']) )
{

	if( strlen($_GET['searchTerm']) ==0)
	{
		exit;
	}
	else
	{
		$term = mysql_real_escape_string($_GET['searchTerm']);
		$term2 = mysql_real_escape_string($_GET['searchTerm2']);
		$term = preg_replace( "/\(/" , "\\(" , $term );
	 	$term = preg_replace( "/\)/" , "\\)" , $term );
		$term2 = preg_replace( "/\(/" , "\\(" , $term2 );
		$term2 = preg_replace( "/\)/" , "\\)" , $term2 );

		$exp = $_GET['expr'];
		if($exp != "1" && $exp != "2")
			$exp = 1;

		$exp = ($exp == 1) ? "and" : "or";
	
		if(strlen($_GET['searchTerm2']) > 0)
		{
			if($exp == "and")
			{
				$query = "SELECT a.id, a.term, a.descript FROM SoftwareTesting a INNER JOIN SoftwareTesting b ON a.id = b.id WHERE (a.term LIKE '%$term%' AND a.descript LIKE '%$term%') AND (b.term LIKE '%$term2%' AND b.descript LIKE '%$term2%')";
				$results = $db->query($query,"array");
			}	
			else
			{
				$query = "select * from SoftwareTesting where (term like '%$term%' or descript like '%$term%') UNION select * from SoftwareTesting where (term like '%$term2%' or descript like '%$term2%') order by term ASC";
				$results = $db->query($query,"array");
			}
		}
		else
		{		
			$query = "select * from SoftwareTesting where term like '%$term%' or descript like '%$term%' order by term ASC";	
			$results = $db->query($query,"array");
		}

		print "<table class='results'>";

		$last = "";

		foreach( $results as $result )
		{
			$letter = ucfirst(substr(trim($result[1]),0,1));
			
			if( $letter != $last )
			{
				
				print "<tr><td><br /></td></tr><tr class='row'><td class='h3'> <h2 class='letter'>&nbsp;".$letter."</h2></td></tr><tr><td><br /></td></tr>";
				$last = $letter;
			}

			$syn = (substr(trim($result[2]),0,3) == "See") ? "class='dsynonyms'" : "";
			$covered = ($result[3]=="1") ? "<img src='http://www.feeditout.com/SoftwareTesting/star.png' />" : "" ;

			$title = preg_replace( "/($term)/i" , "<span class='matchh'>$term</span>" , ucfirst( $result[1] ) );

			if(substr(trim($result[2]),0,3) == "See")
			{
				$link = $result[2];	
				$link = substr( $link , 4 , strlen($link) );	
				$link = preg_match("/([a-zA-Z0-9 \-]*)/", $link, $match);
				$link = preg_replace( "/\(/" , "\\(" , $match[0] );
				$link = preg_replace( "/\)/" , "\\)" , $link );
				$def = @preg_replace( "/$link/" , "<a class='otherlink' href=\"javascript:searchExtra('$link')\">$link</a>" , $result[2] );
				$def = preg_replace( "/\\\/" , "" , $def );

				if(strlen($_GET['searchTerm2']) > 0)
				{
					$title = preg_replace("/($term2)/i","<span class='matchh'>$term2</span>",$title);
				}
			}
			else
			{	
				if ( stristr( $result[2] , "See also" ) )	
				{
					$temp = explode( "See also" , $result[2] );

					$temp[0] = preg_replace("/($term)/i","<span class='match'>$term</span>",$temp[0]);

					if(strlen($_GET['searchTerm2']) > 0)
					{
						$title = preg_replace("/($term2)/i","<span class='match'>$term2</span>",$title);
						$temp[0] = preg_replace("/($term2)/i","<span class='match'>$term2</span>",$temp[0]);
					}
					
					$def = implode( "See also" , $temp );
				}	
				else
				{
					$def = preg_replace("/($term)/i","<span class='match'>$term</span>",$result[2]);

					if(strlen($_GET['searchTerm2']) > 0)
					{
						$title = preg_replace("/($term2)/i","<span class='match'>$term2</span>",$title);
						$def = preg_replace("/($term2)/i","<span class='match'>$term2</span>",$def);
					}
				}			
			}

			if ( stristr( $def , "See also" ) )
			{						
				$seealso = explode( "See also" , $def );
				$seealso[1] = eregi_replace( "\n" , "" , $seealso[1] );

				if( stristr( $seealso[1] , "," ) )
				{				
					$alsomore = explode( "," , $seealso[1] );
			
					for( $r = 0; $r < count( $alsomore ); $r++ )
					{					
						$temp = trim($alsomore[$r]);
						$temp =  stristr( $temp , "." ) ? substr( $temp , 0 , strlen($temp) -1 ) : $temp;
						$temp = preg_replace( "/\(/" , "\\(" , $temp );
						$temp = preg_replace( "/\)/" , "\\)" , $temp );
						$alsomore[$r] = @preg_replace( "/$temp/" , "<a class='otherlink' href=\"javascript:searchExtra('$temp')\">$temp</a>" , $alsomore[$r] );
						$malso[] = preg_replace( "/\\\/" , "" , $alsomore[$r] );
					}

					$seealso[1] = implode( "," , $malso );
				}
				else
				{
					$temp = trim( $seealso[1] );
					$temp =  stristr( $temp , "." ) ? substr( $temp , 0 , strlen($temp) -1 ) : $temp;
					$temp = preg_replace( "/\(/" , "\(" , $temp );
					$temp = preg_replace( "/\)/" , "\\)" , $temp );
					$seealso[1] = @preg_replace( "/$temp/" , "<a class='otherlink' href=\"javascript:searchExtra('$temp')\">$temp</a>" ,$seealso[1] );
					$seealso[1] = preg_replace( "/\\\/" , "" , $seealso[1] );			
				}

				$seealso = implode( "<br/><br/>See also" , $seealso );
				$def = $seealso;
			}				
			
			$title = preg_replace( "/\\\/" , "" , $title );
			print "<tr $syn><td><h3>$covered $title</h3><p>".nl2br($def)."</p><br/></td></tr>";
		}

		print "</table>";

		exit;
	}
}

if( isset($_GET['group']) )
{
	$term = mysql_real_escape_string($_GET['group']);
	$query = "select * from SoftwareTesting where term like '$term%' order by term ASC";	
	$results = $db->query($query,"array");

	print "<br /><br /><table class='results'>";

	foreach( $results as $result )
	{
		$title = ucfirst($result[1]);
		$def = $result[2];	
		$covered = ($result[3]=="1") ? "<img src='http://www.feeditout.com/SoftwareTesting/star.png' />" : "" ;
                $syn = (substr(trim($def),0,3) == "See") ? "class='dsynonyms'" : "";

		if(substr(trim($result[2]),0,3) == "See")
		{
			$link = $result[2];	
			$link = substr( $link , 4 , strlen($link) );
			$link = preg_match("/([a-zA-Z0-9 \-]*)/", $link, $match);	
			$link = preg_replace( "/\(/" , "\(" , $match[0] );
			$link = preg_replace( "/\)/" , "\)" , $link );						
			$def = @preg_replace( "/$link/" , "<a class='otherlink' href=\"javascript:searchExtra('$link')\">$link</a>" , $result[2] );
			$def = preg_replace( "/\\\/" , "" , $def );
			
		}	
		
		if ( stristr( $def , "See also" ) )
		{						
			$seealso = explode( "See also" , $def );
			$seealso[1] = eregi_replace( "\n" , "" , $seealso[1] );

			if( stristr( $seealso[1] , "," ) )
			{				
				$alsomore = explode( "," , $seealso[1] );
				
				for( $r = 0; $r < count( $alsomore ); $r++ )
				{					
					$temp = trim($alsomore[$r]);
					$temp =  stristr( $temp , "." ) ? substr( $temp , 0 , strlen($temp) -1 ) : $temp;
					$temp = preg_replace( "/\(/" , "\(" , $temp );
					$temp = preg_replace( "/\)/" , "\)" , $temp );
					$alsomore[$r] = @preg_replace( "/$temp/" , "<a class='otherlink' href=\"javascript:searchExtra('$temp')\">$temp</a>" , $alsomore[$r] );
					$malso[] = preg_replace( "/\\\/" , "" , $alsomore[$r] );
				}

				$seealso[1] = implode( "," , $malso );
			}
			else
			{
				$temp = trim( $seealso[1] );
				$temp =  stristr( $temp , "." ) ? substr( $temp , 0 , strlen($temp) -1 ) : $temp;
				$temp = preg_replace( "/\(/" , "\(" , $temp );
				$temp = preg_replace( "/\)/" , "\)" , $temp );
				$seealso[1] = @preg_replace( "/$temp/" , "<a class='otherlink' href=\"javascript:searchExtra('$temp')\">$temp</a>" ,$seealso[1] );
				$seealso[1] = preg_replace( "/\\\/" , "" , $seealso[1] );			
			}

			$seealso = implode( "<br/><br/>See also" , $seealso );
			$def = $seealso;
		}	

		print "<tr $syn><td class='data'><h3>$covered $title</h3><p>".nl2br($def)."</p><br/></td></tr>";
	}

	print "</table>";
	
	exit;
}

if( isset($_GET['all']) )
{
	$query = "select * from SoftwareTesting order by term ASC";
	$results = $db->query( $query , "array" );

	$termlist = array();

	$last = "";

	foreach( $results as $result )
	{
		$letter = ucfirst(substr(trim($result[1]),0,1));

		if( $letter != $last )
		{			
			$termlist[] = "<td class='h3'><h3>&nbsp;$letter</h3></td>";
			$last = $letter;
		}
		
		$syn = (substr(trim($result[2]),0,3) == "See") ? "class='synonyms'" : "";
		$syni = (substr(trim($result[2]),0,3) == "See") ? "class='isynonyms'" : "";
		$covered = ($result[3]=="1") ? "<img $syni src='http://www.feeditout.com/SoftwareTesting/star.png' />" : "" ;
		$termlist[] = "<td width='290' class='min'>$covered <a $syn href=\"javascript:search('".$result[1]."')\">".$result[1]."</a></td>";
		
	}

	$mid = ceil(count($termlist) / 4);

	print "<table class='tterms'>";

	$last = "";

	for($t=0; $t < $mid; $t++ )
	{		
		print "<tr>";
		print $termlist[$t];
		print $termlist[$mid + $t];
		print $termlist[$mid + $mid + $t];
		print $termlist[$mid + $mid + $mid + $t];
		print "</tr>";
	}

	print "</table>";
	
	exit;
}

if( isset( $_GET['testing'] ) )
{
	print "<table class='tterms'>";

	$query = "select * from SoftwareTesting where term LIKE '%testing' order by term ASC";
	$results = $db->query($query,"array");

	$termlist = array();

	$last = "";

	foreach( $results as $result )
	{
		$letter = ucfirst(substr(trim($result[1]),0,1));

		if( $letter != $last )
		{			
			$termlist[] = "<td class='h3'><h3>&nbsp;$letter</h3></td>";			
			$last = $letter;
		}
		
		$syn = (substr(trim($result[2]),0,3) == "See") ? " class='synonyms' " : " ";
		$syni = (substr(trim($result[2]),0,3) == "See") ? " class='isynonyms' " : " ";
		$covered = ($result[3]=="1") ? "<img $syni src='http://www.feeditout.com/SoftwareTesting/star.png' /> " : "" ;
		$termlist[] = "<td width='220' class='min'>$covered <a $syn href=\"javascript:searchExtra('".$result[1]."')\">".$result[1]."</a></td>";
		
	}

	$y = 0;
	$mid = ceil(count($termlist) / 2) -1;

	for($t=0; $t < $mid; $t++ )
	{
		print "<tr>";
		print $termlist[$t];
		print $termlist[$mid + $t];
		print "</tr>";
		$y += 2;
	}

	for( $o = $y; $o < count($termlist); $o++)
	{
		print "<tr>";
		print "<td></td>";
		print $termlist[$o];
		print "</tr>";
	}

	print "</table>";

	exit;
}

?>
<html>
<head>
<title>Software Testing terms</title>
<link rel="stylesheet" href="st.css" type="text/css" media="screen" />
<script type="text/javascript" src="jquery.min.js"></script>
<script language='javascript' src="http://www.feeditout.com/SoftwareTesting/st.js" type='text/javascript'></script>
</head>
<body>
<table class='table'>
	<tr>
		<td colspan='2' style="padding-left:5px;padding-top:10px;padding-bottom:10px;font-size:10pt; vertical-align:middle; background-color:#4E7FC8; color:#ffffff"><a name='top'>&nbsp;</a> <a href='javascript:hback()' style='padding-right:10px;'><img src='back-icon.png' /></a>
		Search for definition : <input type='text' name='searchTerm' id='searchTerm' size='30' class='searchbox'> <span id='countdown1'><img src='ncountdown.gif' /></span>  <select name='exp' id='exp'> <option value='1'>and</option><option value='2'>or</option></select> <input type='text' name='searchTerm2' id='searchTerm2' size='30' class='searchbox'> <span id='countdown2' style='margin-right:20px'><img src='ncountdown.gif' /></span>
		<a href='javascript:hideSynonyms()' id='SynonymsLink' style='color:#ffffff;text-decoration:underline'>Hide Synonyms</a> &nbsp;&nbsp; <a href="javascript:showAll()" id='showAll' style='color:#ffffff;text-decoration:underline'>Complete Listing</a> &nbsp;&nbsp; <a href="download.php" style='color:#ffffff;text-decoration:underline'>Download Source / SQL</a>	
		</td>
	</tr>
	<tr>
		<td valign='top' class='col' id='resultCol' style="border-left: 5px solid #4E7FC8;">
			<div class='minheight' id='content'>
				<div style="padding-left:25px;padding-top:10px" id='letters'>
					<a class='let' href="javascript:showGroup('a')">A</a> 
					<a class='let' href="javascript:showGroup('b')">B</a> 
					<a class='let' href="javascript:showGroup('c')">C</a> 
					<a class='let' href="javascript:showGroup('d')">D</a> 
					<a class='let' href="javascript:showGroup('e')">E</a> 
					<a class='let' href="javascript:showGroup('f')">F</a> 
					<a class='let' href="javascript:showGroup('g')">G</a> 
					<a class='let' href="javascript:showGroup('h')">H</a> 
					<a class='let' href="javascript:showGroup('i')">I</a> 
					<a class='let' href="javascript:showGroup('j')">J</a> 
					<a class='let' href="javascript:showGroup('k')">K</a> 
					<a class='let' href="javascript:showGroup('l')">L</a> 
					<a class='let' href="javascript:showGroup('m')">M</a> 
					<a class='let' href="javascript:showGroup('n')">N</a> 
					<a class='let' href="javascript:showGroup('o')">O</a> 
					<a class='let' href="javascript:showGroup('p')">P</a> 
					<a class='let' href="javascript:showGroup('q')">Q</a> 
					<a class='let' href="javascript:showGroup('r')">R</a> 
					<a class='let' href="javascript:showGroup('s')">S</a> 
					<a class='let' href="javascript:showGroup('t')">T</a> 
					<a class='let' href="javascript:showGroup('u')">U</a> 
					<a class='let' href="javascript:showGroup('v')">V</a> 
					<a class='let' href="javascript:showGroup('w')">W</a>
					<a class='let' href="javascript:showGroup('x')">X</a> 
					<a class='let' href="javascript:showGroup('y')">Y</a> 
					<a class='let' href="javascript:showGroup('z')">Z</a> 
				</div>
				<div style='width:740px' id='result'>

				</div>
			</div>
		</td>
		<td valign='top' style="border-left: 3px solid #4E7FC8; border-right: 5px solid #4E7FC8; padding:5px;">				
			<div class='minheight' id='termsList'>
				
			</div>
			<br />
		</td>
	</tr>
	<tr>
		<td colspan='2' style="padding-left:30px;padding-top:10px;padding-bottom:10px;font-size:10pt; vertical-align:middle; background-color:#4E7FC8; color:#ffffff">
		<a href='#top' style='color:#ffffff;text-decoration:underline'>Top</a>
		</td>
	</tr>
</table>
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

