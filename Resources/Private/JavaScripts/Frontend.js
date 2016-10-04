/**
 * Ipandlanguageredirect functions
 *
 * @class IpandlanguageredirectFrontend
 */
function IpandlanguageredirectFrontend() {
	'use strict';

	/**
	 * Container id
	 *
	 * @type {string}
	 */
	var containerId = 'ipandlanguageredirect_container';

	/**
	 * Initialize
	 *
	 * @returns {void}
	 */
	this.initialize = function() {
		ajaxConnection(getAjaxUri());
	};

	/**
	 * @params {string}
	 * @returns void
	 */
	var ajaxConnection = function(uri) {
		if (uri) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var returnObject = JSON.parse(this.responseText);
					console.log(returnObject);
				}
			};
			xhttp.open('POST', uri, true);
			xhttp.send();
		} else {
			console.log('No ajax URI given!');
		}
	};

	/**
	 * @returns {string}
	 */
	var getAjaxUri = function() {
		var container = getContainer();
		if (container !== null) {
			return container.getAttribute('data-redirect-uri');
		}
		return '';
	};

	/**
	 * @returns {object}
	 */
	var getContainer = function() {
		return document.getElementById(containerId);
	};
}

var Ipandlanguageredirect = new window.IpandlanguageredirectFrontend();
Ipandlanguageredirect.initialize();
