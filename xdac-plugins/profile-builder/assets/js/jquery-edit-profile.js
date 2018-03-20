function confirmDelete( nonceField, currentUser, customFieldID, customFieldName, returnTo, ajaxurl, what, fileName, text ) {
  if (confirm(text)) {
	jQuery.post( ajaxurl ,  { action:"hook_wppb_delete", currentUser:currentUser, customFieldID:customFieldID, customFieldName:customFieldName, what:what, _ajax_nonce:nonceField }, function( response ) {
		if( jQuery.trim(response)=="done" ){
			if ( what == 'avatar' ){
				alert( ep.avatar );
			}else{
				alert( ep.attachment +' '+ fileName );
			}
			window.location=returnTo;
		}else{
			alert(jQuery.trim(response));
		}
	});			
  }
}