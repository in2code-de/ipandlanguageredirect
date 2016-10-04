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
		ajaxConnection(getAjaxUri(), getParametersForAjaxCall());
	};

	/**
	 * @params {string} uri
	 * @params {object} parameters
	 * @returns void
	 */
	var ajaxConnection = function(uri, parameters) {
		if (uri) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var returnObject = JSON.parse(this.responseText);
					console.log(returnObject);
				}
			};
			xhttp.open('POST', mergeUriWithParameters(uri, parameters), true);
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

	/**
	 * Get parameters for ajax call
	 *
	 * @returns {object}
	 */
	var getParametersForAjaxCall = function() {
		return {
			'tx_ipandlanguageredirect_pi1[browserLanguage]': getBrowserLanguage(),
			'tx_ipandlanguageredirect_pi1[ip]': '',
			'tx_ipandlanguageredirect_pi1[referrer]': getReferrer()
		};
	};

	/**
	 * Get Browserlanguage (get "de" from "de" or from "de-DE")
	 *
	 * @returns {string}
	 */
	var getBrowserLanguage = function() {
		var userLang = navigator.language || navigator.userLanguage;
		var parts = userLang.split('-');
		return parts[0];
	};

	/**
	 * @returns {string}
	 */
	var getReferrer = function() {
		return document.referrer;
	};

	/**
	 * Build an uri string for an ajax call together with params from an object
	 * 		{
	 * 			'x': 123,
	 * 			'y': 'abc'
	 * 		}
	 *
	 * 		=>
	 *
	 * 		"?x=123&y=abc"
	 *
	 * @params {string} uri
	 * @params {object} parameters
	 * @returns {string} e.g. "index.php?id=123&type=123&x=123&y=abc"
	 */
	var mergeUriWithParameters = function(uri, parameters) {
		for (var key in parameters) {
			if (parameters.hasOwnProperty(key)) {
				if (uri.indexOf('?') !== -1) {
					uri += '&';
				} else {
					uri += '?';
				}
				uri += key + '=' + parameters[key];
			}
		}
		return uri;
	};
}

var Ipandlanguageredirect = new window.IpandlanguageredirectFrontend();
Ipandlanguageredirect.initialize();
