
var xhrgetTT = null;
var resultsTimergetTT = null;

function getTT()
{		
	clearTimeout( resultsTimergetTT );
	
	if (xhrgetTT)
	{
		xhrgetTT.abort();
	} 
 
	resultsTimergetTT = setTimeout(
		function(){
                        $("#resultTable").html("<center><br><br><br><br><img src='http://www.feeditout.com/SoftwareTesting/big.gif' /></center>");
			xhrgetTT = $.get("http://www.feeditout.com/ULTimeTable/tt.php", 
			{ 
				id: $('#idNumber').val()
				}, 
				function(data)
				{
					$('#resultTable').html(data);
                                        $('#idRight').text("");
		   		}
			);
		},
		500
	);	
}




$(document).ready(function()
{
    $('#idNumber').keyup( function()
    {  
       if( /^\d+$/.test( $('#idNumber').val() ) )
       {
           var id = $('#idNumber').val();
           if( id.length > 6  && id.length < 9 )
           {
               $('#idRight').text("standy");
               getTT();
           }
           else
           {
               $('#idRight').text("Invalid ID");
           }
       }
       else
       {
           $('#idRight').text("ID not numeric");
       }
    });    

});

