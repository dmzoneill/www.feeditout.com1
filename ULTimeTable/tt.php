<?php 

if(!ob_start("ob_gzhandler")) {ob_start();}

class csisTimeTable
{
	private $studentId;
	private $timeTable;
	private $moduleCount;
	private $modules;
	private $moduleNames;

	public function __construct( $id )
	{
		$this->studentId = $id;
		$this->fetchTimeTable();
	}	

	public function getTimeTable()
	{
		return $this->createTable();
	}

	private function getModuleNames()
	{
		$this->ch = curl_init();

		for( $t = 1; $t < count( $this->modules ) + 1; $t++)
		{
		
			curl_setopt( $this->ch , CURLOPT_URL ,"http://www.timetable.ul.ie/tt_moduledetails_res.asp" );
			curl_setopt ( $this->ch, CURLOPT_POST, 1);
			curl_setopt( $this->ch, CURLOPT_POSTFIELDS, "T1=" . $this->modules[$t]);
			curl_setopt( $this->ch , CURLOPT_USERAGENT , "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.4pre) Gecko/20090829 Ubuntu/9.04 (jaunty) Shiretoko/3.5.4pre" );
			curl_setopt( $this->ch , CURLOPT_TIMEOUT , '10' );
			curl_setopt( $this->ch , CURLOPT_FOLLOWLOCATION , 1 );
			curl_setopt( $this->ch , CURLOPT_RETURNTRANSFER , 1 );
			$this->store = curl_exec( $this->ch );

			preg_match_all( "#<font[^>]*>(.*?)<\/font>#sm" , $this->store , $match );
			$this->moduleNames[$t] = trim($match[1][3]);
		}

		curl_close( $this->ch );
	}

	private function fetchTimeTable()
	{
		$this->ch = curl_init();
		curl_setopt( $this->ch , CURLOPT_URL ,"http://www.timetable.ul.ie/tt2.asp" );
		curl_setopt ( $this->ch, CURLOPT_POST, 1);
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS, "T1=" . $this->studentId);
		curl_setopt( $this->ch , CURLOPT_USERAGENT , "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.4pre) Gecko/20090829 Ubuntu/9.04 (jaunty) Shiretoko/3.5.4pre" );
		curl_setopt( $this->ch , CURLOPT_TIMEOUT , '10' );
		curl_setopt( $this->ch , CURLOPT_FOLLOWLOCATION , 1 );
		curl_setopt( $this->ch , CURLOPT_RETURNTRANSFER , 1 );
		$this->store = curl_exec( $this->ch );
		curl_close( $this->ch );
	
		preg_match( "#<table[^>]*>(.*?)<\/table>#sm" , $this->store , $table );
		preg_match_all( "#<td[^>]*>(.*?)<\/td>#sm" , $table[0] , $match );	

		$days = array();
		$days[1][0] = $match[0][6];
		$days[2][0] = $match[0][7];
		$days[3][0] = $match[0][8];
		$days[4][0] = $match[0][9];
		$days[5][0] = $match[0][10];	
		$days[6][0] = $match[0][11];	
		
				
		$type = "";

		for( $i = 1 ; $i < count( $days ) + 1; $i++ )
		{
			$classNum = 1;
			preg_match_all( "#<p[^>]*>(.*?)<\/p>#sm" , $days[$i][0] , $classes );
			foreach( $classes[0] as $class )
			{					
				if( preg_match( "/LAB/" , $class ) )
				{
					$split = explode( "LAB" , $class );
					$type = "LAB";
				}
				else if( preg_match( "/TUT/" , $class ) )
				{
					$split = explode( "TUT" , $class );
					$type = "TUT";
				}
				else if( preg_match( "/LEC/" , $class ) )
				{
					$split = explode( "LEC" , $class );
					$type = "LEC";
				}
				else
				{
					exit;
				}

				preg_match_all( "/[0-9][0-9]:[0-9][0-9]/" , $split[0] , $times );
				preg_match( "/[A-Z]{2,3}[0-9]{3,4}/" , $split[0] , $module );
				preg_match( "/\b[0-9][A-Za-z]\b/" , $split[1] , $group );
				preg_match( "/[a-zA-Z]{1,2}[0-9]{2,3}/" , $split[1] , $room );	
				preg_match( "/\bWks.*\b/" , $split[1], $weeks );

				$group = count( $group ) > 0 ? $group[0] : "";	
				$weeks = count( $weeks ) > 0 ? $weeks[0] : "";	

				$classInfo = array();
				$classInfo['starttime'] = $times[0][0];	
				$classInfo['endtime'] = $times[0][1];	
				$classInfo['module'] = $module[0];
				$classInfo['type'] = $type;	
				$classInfo['group'] = $group;
				$classInfo['room'] = $room[0];
				$classInfo['weeks'] = $weeks;
				$days[$i][$classNum] = $classInfo;
				$classNum++;						
			}
			unset($days[$i][0]);			
		}
	
		$this->timeTable = $days; 			
	}	

	
	private function createTable()
	{
		$timetable = $this->timeTable;
		$output = "<br><table width='700px' class='table'>\n";
		$output .= "<thead><tr><th class='nostyle'></th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th></tr></thead><tbody>\n";

		$modules = array();
		$moduleIndex = 1;
		$previous = array();

		for( $hour = 9; $hour < 18; $hour++)
		{
			$output .= "<tr>\n";
			$cHour = ( $hour == 9 ) ? "09:00" : $hour . ":00";
			$output .= "<th class='time'>$cHour</th>\n";

	

			for( $i = 1; $i < 6; $i++ )
			{			
				for( $t = 1; $t < count( $timetable[$i] ) +1; $t++ )
				{					
					if( $cHour == $timetable[$i][$t]['starttime'] )	
					{
						$data = $timetable[$i][$t];	
						break;	
					}				
				}	
		
				if( isset( $data ) )	
				{
					$starttime = $data['starttime'];	
					$endtime = $data['endtime'];	
					$module = $data['module'];
					$type = $data['type'];
					$group = $data['group'];
					$room = $data['room'];
					$weeks = $data['weeks'];

					$moduleExists = array_search( "$module" , $modules );

					if( $moduleExists == null )
					{
						$modules[$moduleIndex] = $module;
						$moduleExists = $moduleIndex;	
						$moduleIndex++;			
					}

					$roundedStart = "<div class='module$moduleExists'>\n <div class=\"spiffyfg$moduleExists\">\n";

					$roundedEnd = "
						</div>\n
						</div>";

					$previous[$hour][$i] = (substr( $endtime , 0 , 2 ) - substr( $starttime , 0 , 2 )) > 1 ? "true" : "false";

					$output .= "<td class='module'". ((substr( $endtime , 0 , 2 ) - substr( $starttime , 0 , 2 )) > 1 ? " rowspan='2' " : "") .">\n$roundedStart";
					$output .= "<b class='moduleName$moduleExists'>$module</b><br />\n";
					$output .= "$type<br />\n";
					$output .= "$group<br />\n";
					$output .= "$room<br />\n";
					$output .= "$weeks";
					$output .= ((substr( $endtime , 0 , 2 ) - substr( $starttime , 0 , 2 )) > 1 ? "\n$roundedEnd\n" : "$roundedEnd\n</td>\n");		
	
			
					unset( $data );		
				}
				else
				{
					$previous[$hour][$i] = "false";
					if( $hour > 9 )
					{
						if( $previous[$hour -1][$i] == "false" )
						{
							$output .= "<td class='module'>&nbsp;</td>\n";	
						}
					}
					else
					{
						$output .= "<td class='module'>&nbsp;</td>\n";	
					}
				}			
			}
	
			$output .= "</tr>\n";
		}
		$output .= "</tbody></table>\n";
		
		$this->moduleCount = count( $modules );
		$this->modules = $modules;
		$this->getModuleNames();

		return $output;
	}	

	
	public function getControls()
	{
		if( !( $this->moduleCount > 0 ) )
		{
			return;
		}
		else
		{
			$output .= "<script language='javascript' type='text/javascript'>\n";
			$output .= "var modules = new Array();\n";
			$output .= "var moduleNames = new Array();\n";
			$output .= "var labs = true;\n";
			$output .= "var tuts = true;\n";
			$output .= "var lecs = true;\n";
			$output .= "var names = false;\n";
			
			$output .= "function toggleType( type , value )
			{			
				for( var i = 1; i < 7; i++ )
				{ 
					\$( \".spiffyfg\" + i ).each(function() 
					{											
						var text = \$(this).html();
						var result = text.indexOf( type );
						
						if( value == false )	
						{			
							if( result != -1 )
							{
		    						\$(this).parent().fadeTo('slow', 0.1 );
			    				}		    				
			    			}
			    			else
			    			{
			    				if( result != -1 )
							{
			    					\$(this).parent().fadeTo('slow', 1.0 );
			    				}
			    			}
		  			});
  				}
			}";


			$output .= "function toggleName()
			{			
				for( var i = 1; i < 7; i++ )
				{ 
					var temp = moduleNames[i];
					var start = 0;
					\$( \".moduleName\" + i ).each(function() 
					{	
						if( start == 0 )
						{					
							\$(\".controlName\" + i).text( temp );	
							moduleNames[i] = \$(this).text();
							start++;
						}
						\$(this).text( temp );				    			
		  			});					
  				}
			}";
			
			for( $t = 1; $t < $this->moduleCount + 1; $t++ )
			{
				$output .= "modules[$t] = true;\n";
				$output .= "moduleNames[$t] = \"".trim($this->moduleNames[$t])."\";\n";				
			}

			$output .= "
			\$(document).ready(function()\n
			{			
				for( var i = 1; i < modules.length; i++ )
				{
					\$(\"#control\" + i).click( function()
					{									
						var name = \$(this).attr(\"id\").split('l');
						var id = parseInt(name[1]);						
						
						if( modules[id] == true)
						{					
							\$(\".module\" + id).fadeTo('slow', 0.1 );
							\$(this).fadeTo('slow', 0.5 );		
						}	
						else
						{
							\$(\".module\" + id).fadeTo('slow', 1.0 );
							\$(this).fadeTo('slow', 1.0 );
						}
						
						modules[id] = !modules[id];
					});

					\$(\".spiffyfg\" + i).click( function()
					{                        
						if( \$(this).parent().attr( 'class' ) == undefined )
						{
							return;
						}
						else
						{
							var name = \$(this).attr(\"class\").split('g');
							var id = parseInt(name[1]);
							
							if( modules[id] == true)
							{							
								\$(\".module\" + id).fadeTo('slow', 0.1 );
								\$(\"#control\" + id).fadeTo('slow', 0.5 );		
							}	
							else
							{
								\$(\".module\" + id).fadeTo('slow', 1.0 );
								\$(\"#control\" + id).fadeTo('slow', 1.0 );
							}
							
							modules[id] = !modules[id];
						}
					});
				}	

				\$( \"#lab\" ).click( function()
				{
					if( labs == true )
					{
						\$(this).fadeTo('slow', 0.5 );
						toggleType( 'LAB' , false );
						labs = false;
					}
					else
					{
						\$(this).fadeTo('slow', 1.0 );
						toggleType( 'LAB' , true );
						labs = true;
					}
				});
				
				\$( \"#tut\" ).click( function()
				{
					if( tuts == true )
					{
						\$(this).fadeTo('slow', 0.5 );	
						toggleType( 'TUT' , false );
						tuts = false;
					}
					else
					{
						\$(this).fadeTo('slow', 1.0 );	
						toggleType( 'TUT' , true );
						tuts = true;
					}
				});
				
				\$( \"#lec\" ).click( function()
				{
					if( lecs == true )
					{
						\$(this).fadeTo('slow', 0.5 );	
						toggleType( 'LEC' , false );
						lecs = false;
					}
					else
					{
						\$(this).fadeTo('slow', 1.0 );
						toggleType( 'LEC' , true );
						lecs = true;
					}
				});

				\$( \"#Names\" ).click( function()
				{
					if( names == true )
					{
						\$(this).fadeTo('slow', 0.5 );	
						toggleName();
						names = false;
					}
					else
					{
						\$(this).fadeTo('slow', 1.0 );
						toggleName();
						names = true;
					}
				});

				\$( \"#Names\" ).fadeTo('slow', 0.5 );
				
			});";
			
			$output .= "</script>\n";
			
			$output .= "<table width='700px'  class='controls'><tr>\n";
			
			for( $t = 1; $t < $this->moduleCount + 1; $t++ )
			{
				$roundedStart = "<div id='control$t'>\n <div class=\"spiffyfg$t\">\n";

				$roundedEnd = "</div>\n </div>";

				$output .= "<td> $roundedStart <b class='controlName$t'>" . $this->modules[$t] . "</b> $roundedEnd </td>";
			}
			
			
			
			$output .= "</tr><tr><td></td>";			
			
			
			$roundedStart = "<div id='lab'>\n <div class=\"spiffyOtherfg\">\n";
			$roundedEnd = "</div>\n </div>";			
			$output .= "<td> $roundedStart <b>Labs</b> $roundedEnd </td>";
						
			
			$roundedStart = "<div id='tut'>\n <div class=\"spiffyOtherfg\">\n";
			$roundedEnd = "</div>\n</div>";			
			$output .= "<td> $roundedStart <b>Tutorials</b> $roundedEnd </td>";
			
						
			$roundedStart = "<div id='lec'><div class=\"spiffyOtherfg\">\n";
			$roundedEnd = "</div>\n</div>";			
			$output .= "<td> $roundedStart <b>Lectures</b> $roundedEnd </td>";

			
			$roundedStart = "<div id='Names'>\n<div class=\"spiffyOtherfg\">\n";
			$roundedEnd = "</div>\n</div>";			
			$output .= "<td> $roundedStart <b>Module Names</b> $roundedEnd </td>";									
			$output .= "</tr></table>\n";

			return $output;
		}
	}
}

if( !isset($_GET['id'] ) )
{
	exit;
}
else
{
	if( preg_match( "/[0-9]{7,8}/" , $_GET['id'] ) )
	{
		include("/home/proxykillah/db.php");
		
		$sql = "INSERT INTO studentids values('" . mysql_real_escape_string( $_GET['id'] ) . "')";
		$db->query( $sql , "one" );
		//print $sql;
		$timetable = new csisTimeTable( $_GET['id'] );
		$table = $timetable->getTimeTable();
		$controls = $timetable->getControls();
		print $controls;
		print $table;
	}
	else
	{
		exit;
	}
}

?>
