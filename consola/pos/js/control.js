jQuery(function($) {

	// QWERTY Password
	// ********************
	$('#username').keyboard({
		openOn   : null,
		stayOpen : true,
		layout   : 'qwerty'
	});
	$('#user-opener').click(function(){
		var kb = $('#username').getkeyboard();
		// close the keyboard if the keyboard is visible and the button is clicked a second time
		if ( kb.isOpen ) {
			kb.close();
		} else {
			kb.reveal();
		}
	});


	// QWERTY Password
	// ********************
	$('#password').keyboard({
		openOn   : null,
		stayOpen : true,
		layout   : 'qwerty'
	});
	$('#password-opener').click(function(){
		var kb = $('#password').getkeyboard();
		// close the keyboard if the keyboard is visible and the button is clicked a second time
		if ( kb.isOpen ) {
			kb.close();
		} else {
			kb.reveal();
		}
	});



});
