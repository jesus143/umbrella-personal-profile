/*
 * SimpleModal Confirm Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: confirm.js 254 2010-07-23 05:14:44Z emartin24 $
 */

jQuery(function ($) {
	$('#confirmbutton').click(function (e) {
		e.preventDefault();
		alert('Oh Noh!');
		// example of calling the confirm function
		// you must use a callback function to perform the "yes" action
		//confirmz("Continue to the SimpleModal Project page?", function () {
		//	window.location.href = 'http://www.ericmmartin.com/projects/simplemodal/';
		//});
	});
});

function confirmz(message, callback) {
	jQuery('#confirms').modal({
		closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
		position: ["20%",],
		overlayId: 'confirm-overlay',
		containerId: 'confirm-container', 
		onShow: function (dialog) {
			var modal = this;

			$('.message', dialog.data[0]).append(message);

			// if the user clicks "yes"
			$('.yes', dialog.data[0]).click(function () {
				// call the callback
				if ($.isFunction(callback)) {
					callback.apply();
				}
				// close the dialog
				modal.close(); // or $.modal.close();
			});
		}
	});
}