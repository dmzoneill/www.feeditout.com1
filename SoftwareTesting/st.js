
function set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure )
{
	var cookie_string = name + "=" + escape ( value );
	if ( exp_y )
	{
		var expires = new Date ( exp_y, exp_m, exp_d );
		cookie_string += "; expires=" + expires.toGMTString();
	}
	if ( path )
		cookie_string += "; path=" + escape ( path );

	if ( domain )
		cookie_string += "; domain=" + escape ( domain );

	if ( secure )
		cookie_string += "; secure";

	document.cookie = cookie_string;
}

function getCookie(c_name)
{
	if (document.cookie.length>0)
	{
		c_start=document.cookie.indexOf(c_name + "=");
		if (c_start!=-1)
		{
			c_start=c_start + c_name.length+1;
			c_end=document.cookie.indexOf(";",c_start);
			if (c_end==-1) c_end=document.cookie.length;
	
			return unescape(document.cookie.substring(c_start,c_end));
		}
	}
	return "";
}


set_cookie('syn','false');

var xhr = null;
var resultsTimer = null;
var all = false;
var hist = new Array();
var histcount = -1;
var histbool = false;


function hhistory( val )
{
	histcount++; 
	hist[histcount] = val;
	
}


function hback()
{	
	if(histcount == 0)
		return;
	
	histcount--;
	histbool = true;

	var where =  hist[histcount].split('|');
	$( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );
	
	if(where[0]=="get")
	{
		$("#searchTerm").val(where[1]);
		$("#searchTerm2").val(where[2]);
		get();
	}
	else
	{
		showGroup( where[1] );
	}
}


function search( val )
{
	$( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );
	$("#searchTerm").val(val);
	$("#searchTerm2").val("");
	showAll();
	get();		
}


function searchExtra( val )
{
	$( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );
	$("#searchTerm").val(val);
	$("#searchTerm2").val("");
	get();		
}



function get()
{	
	$("#countdown1").html("<img src='http://www.feeditout.com/SoftwareTesting/countdown.gif' />");
	$("#countdown2").html("<img src='http://www.feeditout.com/SoftwareTesting/countdown.gif' />");

	clearTimeout( resultsTimer );
	
	if (xhr)
	{
		xhr.abort();
	} 

	resultsTimer = setTimeout(
		function(){
			$("#countdown1").html("<img src='ncountdown.gif' />");
			$("#countdown2").html("<img src='ncountdown.gif' />");
			$("#result").html("<div align='center'><br /><br /><br /><br /><br /><br /><img src='http://www.feeditout.com/SoftwareTesting/big.gif' /><br /><br /><br /><br /><br /><br /></div>");
			xhr = $.get("http://www.feeditout.com/SoftwareTesting/index.php", 
			{ 
				ajax: "true", 
				searchTerm: $("#searchTerm").val(),
				searchTerm2: $("#searchTerm2").val(),
				expr: $("#exp").val()
				}, 
				function(data)
				{
					if(data!="true")
					{			
						$("#result").html(data);
						hideSynonymsUpdate();	
						if( histbool == false )
						{
							hhistory( "get|" + $("#searchTerm").val() + "|" + $("#searchTerm2").val() );		    	   
						}
						else
						{
						    	histbool = false;
						}
					}
					else
					{
						$("#result").html("fail");
					}
		   		}
			);
		},
		1000
	);	
}


function showGroup( val )
{
	$("#result").html("<div align='center'><br /><br /><br /><br /><br /><br /><img src='http://www.feeditout.com/SoftwareTesting/big.gif' /><br /><br /><br /><br /><br /><br /></div>");
	$( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );
	$.get("http://www.feeditout.com/SoftwareTesting/index.php", 
	{ 
		ajax: "true", 
		group: val
		}, 
		function(data)
		{
			if(data!="true")
			{			
				$("#result").html(data);	
				if( histbool == false )
				{
					hhistory( "showGroup|" + val);
				}
				else
				{
				    	histbool = false;
				}
				hideSynonymsUpdate();		    	   
			}
			else
			{
				$("#result").html("fail");
			}
   		}
	);
}



function showAll()
{	
	
	if(all == false)
	{
		all = true;

		$.get("http://www.feeditout.com/SoftwareTesting/index.php", 
		{ 
			ajax: "true", 
			testing: "true"
			}, 
			function(data)
			{
				if(data!="true")
				{									
					$("#content").show(2000, function () 
				        {
						$("#showAll").text("Complete Listing");
						$("#termsList").html(data);					
						hideSynonymsUpdate();	
				        });    	   
				}
				else
				{
					$("#result").html("fail");
				}
	   		}
		);
	}
	else
	{
		all = false;		
		
		$.get("http://www.feeditout.com/SoftwareTesting/index.php", 
		{ 
			ajax: "true", 
			all: "true"
			}, 
			function(data)
			{
				if(data!="true")
				{		
					$("#termsList").html(data);
					$("#content").hide(2000, function () 
				        {
						$("#showAll").text("Hide Complete Listing");							
						hideSynonymsUpdate();	
				        });
				        									    	   
				}
				else
				{
					$("#result").html("fail");
				}
	   		}
		);		
	}
}



function hideSynonyms()
{
	var cook = getCookie('syn');

	if ( cook == "false" )
	{
		set_cookie('syn','true');
		hideSynonymsUpdate();
	}
	else
	{
		set_cookie('syn','false');
		hideSynonymsUpdate();
	}
}


function hideSynonymsUpdate()
{
	var cook = getCookie('syn');
	if ( cook == "true" )
	{
		$("#SynonymsLink").text("Show Synonyms");
		$(".synonyms").css("color","#ffffff");
                $(".isynonyms").hide();
                $(".dsynonyms").hide();
	}
	else
	{
		$("#SynonymsLink").text("Hide Synonyms");
		$(".synonyms").css("color","#222222");
                $(".isynonyms").show();
                $(".dsynonyms").show();
	}
}


$(document).ready(function()
{		
	$("#searchTerm").keyup(get);	
	$("#searchTerm2").keyup(get);	

	if( $("#searchTerm").length )
	{
	    showAll();	
	    showGroup('a');
        }	
});
