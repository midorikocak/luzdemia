;
IWD ={};
IWD.notification={
		markReadUrl: null,
		countNoRead: 0,
		removeUrl: null
};
jQuery(document).ready(function($){
	
	$('#mark-all-read').click(function(){
		$.post(IWD.notification.markReadUrl, {"form_key": FORM_KEY}, function(){},'json');
	});
	
	$('#mark-all-remove').click(function(){
		$.post(IWD.notification.removeUrl, {"form_key": FORM_KEY}, function(){},'json');
	});
});