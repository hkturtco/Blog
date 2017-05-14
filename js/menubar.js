var toggledim = false;

function dimming(){
	if(toggledim){
		$(".dimming").css("opacity", "1");
		$("#searchprompt").html("");
		$("#searchprompt2").html("");
		toggledim = false;
	} else {
		$(".dimming").css("opacity", "0.2");
		searchprompt();
		toggledim = true;
	}
}
function navbar(){
	var togglemenu = document.getElementById("blogmenubar");
	if(togglemenu.className === 'menubar'){
		togglemenu.className += ' responsive';
	} else {
		togglemenu.className = 'menubar';
	}
}

function searchprompt(){
	var searchtext = $("#searchtextbox").val();

	$("#searchprompt").html("Your searching keyword is: <br /> &nbsp;&nbsp;&nbsp;&nbsp;"+searchtext);

	$.post("../searchsuggest.php",
	{
		suggestw: searchtext
	},
	function(data){
		$("#searchprompt2").html("&#128269; Searching keyword Suggestion: <span style='color:#CCAA44;'>" +data +"</span>");
	});
}