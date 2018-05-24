(function () {
    "use strict";
    'use strict';

    var app = angular.module('viewCustom', ['angularLoad']);

/* Begin prmMainMenuAfter */
	app.component('prmMainMenuAfter', {
		bindings: { parentCtrl: '<' },
		controller: function($scope) {
	/* Add in Warning Note script */
			setTimeout(function() {
				var y = document.getElementsByTagName("script")[0];
				var z = document.createElement("script");
				z.type = "text/javascript";
				z.async = true;
				z.src = "https://yourdomain.edu/path/to/warningnote/warningnote.js.php"; // your url here!
				y.parentNode.insertBefore(z, y);
			}, 3000);	// add note 3 seconds after main menu loads - modify to suit your environment
	/* End Warning Note */
		}
	});
/* End prmMainMenuAfter */

})();