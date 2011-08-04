(function ($) {
  // All your code here  
  $(document).ready(function() {
    
    // Expand Panel
  	$("#open").click(function(){
  		$(".block-webform .block-inner").slideDown("fast");

  	});	

  	// Collapse Panel
  	$("#close").click(function(){
  		$(".block-webform .block-inner").slideUp("fast");	
  	});		

  	// Switch buttons from "Log In | Register" to "Close Panel" on click
  	$("#toggle a").click(function () {
  		$("#toggle a").toggle();
  	});
  	
// end document.ready
  });
})(jQuery);