/**
 * Ipandlanguageredirect functions
 *
 * @class IpandlanguageredirectFrontend
 */
function IpandlanguageredirectFrontend() {
	'use strict';

	/**
	 * @type {IpandlanguageredirectFrontend}
	 */
	var that = this;

	/**
	 * Container id
	 *
	 * @type {string}
	 */
	var containerId = 'ipandlanguageredirect_container';

	/**
	 * @type {string}
	 */
	var showSuggestClassname = 'fadein';

	/**
	 * @type {{r: number}}
	 */
	var alreadyRedirectedParameter = {r: 1};

	/**
	 * If cookieMode is different to "permanent", cookie will be deleted with browserclose.
	 * "permanent" will add a cookie with 1 year livetime.
	 * And because of the fast moving internet world, 1 year is rather permanent :)
	 *
	 * @type {string}
	 */
	var cookieMode = 'temp';

	/**
	 * Show redirect URI instead of redirecting
	 *
	 * @type {boolean}
	 */
	var debugMode = false;

	/**
	 * Initialize
	 *
	 * @returns {void}
	 */
	this.initialize = function() {
		setDebug();
		addHideMessageListener();
		if (isActivated()) {
			ajaxConnection(getAjaxUri(), getParametersForAjaxCall());
		}
	};

	/**
	 * Set debug value if &formselectiondebug=1
	 *
	 * @returns {void}
	 */
	var setDebug = function() {
		if (window.location.search.indexOf('ipandlanguagedebug=1') !== -1) {
			debugMode = true;
			console.log('ipandlanguageredirect debug activated');
		}
	};

	/**
	 * Add listener to hide message function
	 *
	 * @returns {void}
	 */
	var addHideMessageListener = function() {
		addHideMessageListenerFromDataAttribute();
		addHideMessageListenerFromGetParameter();
	};

	/**
	 * Add listener to hide message function
	 * if an element with data-ipandlanguageredirect-action="hideMessage" was clicked
	 *
	 * @returns {void}
	 */
	var addHideMessageListenerFromDataAttribute = function() {
		var elements = getContainersByDataAttribute('data-ipandlanguageredirect-action', 'hideMessage');
		for (var key in elements) {
			if (elements.hasOwnProperty(key)) {
				var element = elements[key];
				element.onclick = function() {
					setHideMessageCookie();
					hideSuggestContainer();
				}
			}
		}
	};

	/**
	 * Add listener to hide message function
	 * if the GET parameter &h=1 is set
	 *
	 * @returns {void}
	 */
	var addHideMessageListenerFromGetParameter = function() {
		if (getGetParameterByName('h') === '1') {
			setHideMessageCookie();
			hideSuggestContainer();
		}
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
				if (this.readyState === 4 && this.status === 200) {
					var jsonObject = JSON.parse(this.responseText);
					doAction(jsonObject);
				}
			};
			xhttp.open('POST', mergeUriWithParameters(uri, parameters), true);
			xhttp.send();
		} else {
			console.log('No ajax URI given!');
		}
	};

	/**
	 * Decide what to do after AJAX received JSON object
	 *
	 * @param jsonObject
	 */
	var doAction = function(jsonObject) {
		if (jsonObject.activated && Array.isArray(jsonObject.events)) {
			// iterate through events
			for (var key in jsonObject.events) {
				if (jsonObject.events.hasOwnProperty(key)) {
					that[jsonObject.events[key] + 'Event'](jsonObject);
				}
			}
		}
	};

	/**
	 * This function is triggered when AJAX says the browser should be redirected
	 *
	 * @param jsonObject
	 */
	this.redirectEvent = function(jsonObject) {
		var uri = buildRedirectUri(jsonObject.redirectUri);
		if (debugMode) {
			console.log('Redirect to following URI:');
			console.log(uri);
		} else {
			window.location = uri;
		}
	};

	/**
	 * This function is triggered when AJAX says something should be suggested
	 *
	 * @param jsonObject
	 */
	this.suggestEvent = function(jsonObject) {
		if (!isHideMessageCookieSet()) {
			var uri = buildRedirectUri(jsonObject.redirectUri);
			if (debugMode) {
				console.log('Suggest the following URI:');
				console.log(uri);
			} else {
				updateRedirectUriInSuggestContainer(jsonObject);
				showSuggestContainer();
			}
		}
	};

	/**
	 * @param jsonObject
	 */
	var updateRedirectUriInSuggestContainer = function(jsonObject) {
		var linkContainer = getFirstContainerByDataAttribute('data-ipandlanguageredirect-container', 'link');
		linkContainer.setAttribute('href', jsonObject.redirectUri);
	};

	/**
	 * Show suggest container
	 *
	 * @returns {void}
	 */
	var showSuggestContainer = function() {
		var suggestContainer = getFirstContainerByDataAttribute('data-ipandlanguageredirect-container', 'suggest');
		suggestContainer.classList.add(showSuggestClassname);
	};

	/**
	 * Hide suggest container
	 *
	 * @returns {void}
	 */
	var hideSuggestContainer = function() {
		var suggestContainer = getFirstContainerByDataAttribute('data-ipandlanguageredirect-container', 'suggest');
		suggestContainer.classList.remove(showSuggestClassname);
	};

	/**
	 * Build redirect URI with parameter that indicates, that
	 * @param {string} basicUri
	 * @returns {string}
	 */
	var buildRedirectUri = function(basicUri) {
		return mergeUriWithParameters(basicUri, alreadyRedirectedParameter);
	};

	/**
	 * @returns {string}
	 */
	var getAjaxUri = function() {
		var container = getContainer();
		if (container !== null) {
			return container.getAttribute('data-ipandlanguageredirect-ajaxuri');
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
	 * Get the first container by its data-attribute name and value
	 * 		"data-x", "suggest" delivers element with data-x="suggest"
	 *
	 * @param {string} dataKey
	 * @param {string} value
	 * @returns {object|null}
	 */
	var getFirstContainerByDataAttribute = function(dataKey, value) {
		var elements = getContainersByDataAttribute(dataKey, value);
		for (var key in elements) {
			if (elements.hasOwnProperty(key)) {
				if (elements[key].getAttribute(dataKey) === value) {
					return elements[key];
				}
			}
		}
		return null;
	};

	/**
	 * Get all containers by its data-attribute name and value
	 * 		"data-x", "suggest" delivers element with data-x="suggest"
	 *
	 * @param {string} dataKey
	 * @param {string} value
	 * @returns {NodeList}
	 */
	var getContainersByDataAttribute = function(dataKey, value) {
		return document.querySelectorAll('[' + dataKey + '="' + value + '"]');
	};

	/**
	 * Get parameters for ajax call
	 *
	 * @returns {object}
	 */
	var getParametersForAjaxCall = function() {
		return {
			'tx_ipandlanguageredirect_pi1[browserLanguage]': getBrowserLanguage(),
			'tx_ipandlanguageredirect_pi1[ipAddress]': getIpAddress(),
			'tx_ipandlanguageredirect_pi1[referrer]': getReferrer(),
			'tx_ipandlanguageredirect_pi1[languageUid]': getLanguageUid(),
			'tx_ipandlanguageredirect_pi1[rootpageUid]': getRootpageUid(),
			'tx_ipandlanguageredirect_pi1[countryCode]': getCountryCode()
		};
	};

	/**
	 * Get Browserlanguage (get "de" from "de" or from "de-DE")
	 * Overload browserlanguage from data-ipandlanguageredirect-browserlanguage="de"
	 *
	 * @returns {string}
	 */
	var getBrowserLanguage = function() {
		var browserLanguage = null;
		var container = getContainer();

		if (container !== null) {
			browserLanguage = container.getAttribute('data-ipandlanguageredirect-browserlanguage');
		}

		if (browserLanguage === null) {
			var userLang = navigator.language || navigator.userLanguage;
			var parts = userLang.split('-');
			browserLanguage = parts[0];
		}

		return browserLanguage;
	};

	/**
	 * Get ipAddress for testing only - otherwise let it empty
	 * use from data-ipandlanguageredirect-ipaddress="1.1.1.1"
	 *
	 * @returns {string}
	 */
	var getIpAddress = function() {
		var ipAddress = '';
		var container = getContainer();
		if (container !== null) {
			ipAddress = container.getAttribute('data-ipandlanguageredirect-ipaddress');
		}
		return ipAddress;
	};

	/**
	 * Get referrer
	 * Overload it from data-ipandlanguageredirect-referrer="www.google.de"
	 *
	 * @returns {string}
	 */
	var getReferrer = function() {
		var referrer = null;
		var container = getContainer();

		if (container !== null) {
			referrer = container.getAttribute('data-ipandlanguageredirect-referrer');
		}

		if (referrer === null) {
			referrer = document.referrer;
		}

		return referrer;
	};

	/**
	 * @returns {int}
	 */
	var getLanguageUid = function() {
		var container = getContainer();
		if (container !== null) {
			var uid = container.getAttribute('data-ipandlanguageredirect-languageuid');
			return parseInt(uid);
		}
		return 0;
	};

	/**
	 * @returns {int}
	 */
	var getRootpageUid = function() {
		var container = getContainer();
		if (container !== null) {
			var uid = container.getAttribute('data-ipandlanguageredirect-rootpageuid');
			return parseInt(uid);
		}
		return 1;
	};

	/**
	 * Get countrycode for testing only
	 * Get it from data-ipandlanguageredirect-countrycode="us"
	 *
	 * @returns {string}
	 */
	var getCountryCode = function() {
		var container = getContainer();
		if (container !== null) {
			var countryCode = container.getAttribute('data-ipandlanguageredirect-countrycode');
			if (countryCode !== null) {
				return countryCode;
			}
		}
		return '';
	};

	/**
	 * Check if we should send an AJAX request
	 * 		- only if container is in DOM
	 * 		- only if already redirected parameter is not set
	 *
	 * @returns {boolean}
	 */
	var isActivated = function() {
		var container = getContainer();
		return container !== null && getGetParameterByName('r') !== '1';
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

	/**
	 * Get a GET parameter by given key
	 * 		for index.php?id=123&r=x&y=z:
	 * 		getGetParameterByName('r') will return "x"
	 *
	 * @param {string} parameterName
	 * @returns {string|null}
	 */
	var getGetParameterByName = function(parameterName) {
		var result = null, tmp = [];
		window.location.search
			.substr(1)
			.split('&')
			.forEach(function (item) {
				tmp = item.split('=');
				if (tmp[0] === parameterName) {
					result = decodeURIComponent(tmp[1]);
				}
			});
		return result;
	};

	/**
	 * @returns {boolean}
	 */
	var isHideMessageCookieSet = function() {
		return getCookieByName('ipandlanguageredirect_hidemessage') === '1';
	};

	/**
	 * Set hide message cookie
	 *
	 * @returns {void}
	 */
	var setHideMessageCookie = function() {
		if (cookieMode === 'permanent') {
			var now = new Date();
			var time = now.getTime();
			time += 3600 * 24 * 365 * 1000; // 1 year from now
			now.setTime(time);
			document.cookie = 'ipandlanguageredirect_hidemessage=1; expires=' + now.toUTCString() + '; path=/';
		} else {
			document.cookie = 'ipandlanguageredirect_hidemessage=1; path=/';
		}
	};

	/**
	 * Get cookie value by its name
	 *
	 * @param cookieName
	 * @returns {string}
	 */
	var getCookieByName = function(cookieName) {
		var name = cookieName + '=';
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) === 0) {
				return c.substring(name.length, c.length);
			}
		}
		return '';
	};
}

var Ipandlanguageredirect = new window.IpandlanguageredirectFrontend();
Ipandlanguageredirect.initialize();
