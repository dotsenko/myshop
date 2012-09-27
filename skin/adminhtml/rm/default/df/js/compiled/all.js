/*
selectivizr v1.0.1 - (c) Keith Clark, freely distributable under the terms 
of the MIT license.

selectivizr.com
*/
/* 
  
Notes about this source
-----------------------

 * The #DEBUG_START and #DEBUG_END comments are used to mark blocks of code
   that will be removed prior to building a final release version (using a
   pre-compression script)
  
  
References:
-----------
 
 * CSS Syntax          : http://www.w3.org/TR/2003/WD-css3-syntax-20030813/#style
 * Selectors           : http://www.w3.org/TR/css3-selectors/#selectors
 * IE Compatability    : http://msdn.microsoft.com/en-us/library/cc351024(VS.85).aspx
 * W3C Selector Tests  : http://www.w3.org/Style/CSS/Test/CSS3/Selectors/current/html/tests/
 
*/

/**
 * Обратите внимание, что без начального «;»
 * стандартное слияние файлов JavaScript в Magento создаёт сбойный файл
 */

;(function(win) {

	// If browser isn't IE, then stop execution! This handles the script 
	// being loaded by non IE browsers because the developer didn't use 
	// conditional comments.
	if (/*@cc_on!@*/true) return;

	// =========================== Init Objects ============================

	var doc = document;
	var root = doc.documentElement;
	var xhr = getXHRObject();
	var ieVersion = /MSIE ([\d])/.exec(navigator.userAgent)[1];
	
	// If were not in standards mode, IE is too old / new or we can't create
	// an XMLHttpRequest object then we should get out now.
	if (doc.compatMode != 'CSS1Compat' || ieVersion<6 || ieVersion>8 || !xhr) {
		return
	}
	
	
	// ========================= Common Objects ============================

	// Compatiable selector engines in order of CSS3 support. Note: '*' is
	// a placholder for the object key name. (basically, crude compression)
	var selectorEngines = {
		"NW"								: "*.Dom.select",
		"DOMAssistant"						: "*.$", 
		"Prototype"							: "$$",
		"YAHOO"								: "*.util.Selector.query",
		"MooTools"							: "$$",
		"Sizzle"							: "*", 
		"jQuery"							: "*",
		"dojo"								: "*.query"
	};
	
	var selectorMethod;
	var enabledWatchers 					= [];     // array of :enabled/:dsiabled elements to poll
	var ie6PatchID 							= 0;      // used to solve ie6's multiple class bug
	var patchIE6MultipleClasses				= true;   // if true adds class bloat to ie6
	var namespace 							= "slvzr";
	var domReadyScriptID					= namespace + "DOMReady";
	
	// Stylesheet parsing regexp's
	var RE_COMMENT							= /(\/\*[^*]*\*+([^\/][^*]*\*+)*\/)\s*/g;
	var RE_IMPORT							= /@import\s*(?:(?:(?:url\(\s*(['"]?)(.*)\1)\s*\))|(?:(['"])(.*)\3))[^;]*;/g;
	var RE_ASSET_URL 						= /\burl\(\s*(["']?)([^"')]+)\1\s*\)/g;
	var RE_PSEUDO_STRUCTURAL				= /^:(empty|(first|last|only|nth(-last)?)-(child|of-type))$/;
	var RE_PSEUDO_ELEMENTS					= /:(:first-(?:line|letter))/g;
	var RE_SELECTOR_GROUP					= /(^|})\s*([^\{]*?[\[:][^{]+)/g;
	var RE_SELECTOR_PARSE					= /([ +~>])|(:[a-z-]+(?:\(.*?\)+)?)|(\[.*?\])/g; 
	var RE_LIBRARY_INCOMPATIBLE_PSEUDOS		= /(:not\()?:(hover|enabled|disabled|focus|checked|target|active|visited|first-line|first-letter)\)?/g;
	var RE_PATCH_CLASS_NAME_REPLACE			= /[^\w-]/g;
	
	// HTML UI element regexp's
	var RE_INPUT_ELEMENTS					= /^(INPUT|SELECT|TEXTAREA|BUTTON)$/;
	var RE_INPUT_CHECKABLE_TYPES			= /^(checkbox|radio)$/;

	// Broken attribute selector implementations (IE7/8 native [^=""], [$=""] and [*=""])
	var BROKEN_ATTR_IMPLEMENTATIONS			= ieVersion>6 ? /[\$\^*]=(['"])\1/ : null;

	// Whitespace normalization regexp's
	var RE_TIDY_TRAILING_WHITESPACE			= /([(\[+~])\s+/g;
	var RE_TIDY_LEADING_WHITESPACE			= /\s+([)\]+~])/g;
	var RE_TIDY_CONSECUTIVE_WHITESPACE		= /\s+/g;
	var RE_TIDY_TRIM_WHITESPACE				= /^\s*((?:[\S\s]*\S)?)\s*$/;
	
	// String constants
	var EMPTY_STRING						= "";
	var SPACE_STRING						= " ";
	var PLACEHOLDER_STRING					= "$1";

	// =========================== Patching ================================

	// --[ patchStyleSheet() ]----------------------------------------------
	// Scans the passed cssText for selectors that require emulation and
	// creates one or more patches for each matched selector.
	function patchStyleSheet( cssText ) {
		return cssText.replace(RE_PSEUDO_ELEMENTS, PLACEHOLDER_STRING)
			.replace(RE_SELECTOR_GROUP, function(m, prefix, selectorText) {	
			var selectorGroups = selectorText.split(",");
			for (var c=0, cs=selectorGroups.length; c<cs; c++) {
				var selector = normalizeSelectorWhitespace(selectorGroups[c]) + SPACE_STRING;
				var patches = [];
				selectorGroups[c] = selector.replace(RE_SELECTOR_PARSE, 
					function(match, combinator, pseudo, attribute, index) {
						if (combinator) {
							if (patches.length>0) {
								applyPatches( selector.substring(0, index), patches )
								patches = []
							}
							return combinator
						}		
						else {
							var patch = (pseudo) ? patchPseudoClass( pseudo ) : patchAttribute( attribute );
							if (patch) {
								patches.push(patch);
								return "." + patch.className
							}
							return match
						}
					}
				)
			}
			return prefix + selectorGroups.join(",");
		})
	}

	// --[ patchAttribute() ]-----------------------------------------------
	// returns a patch for an attribute selector.
	function patchAttribute( attr ) {
		return (!BROKEN_ATTR_IMPLEMENTATIONS || BROKEN_ATTR_IMPLEMENTATIONS.test(attr)) ? 
			{ className: createClassName(attr), applyClass: true } : null
	}


	// --[ patchPseudoClass() ]---------------------------------------------
	// returns a patch for a pseudo-class
	function patchPseudoClass( pseudo ) {

		var applyClass = true;
		var className = createClassName(pseudo.slice(1));
		var isNegated = pseudo.substring(0, 5)==":not(";
		var activateEventName;
		var deactivateEventName;

		// if negated, remove :not() 
		if (isNegated) {
			pseudo = pseudo.slice(5, -1)
		}
		
		// bracket contents are irrelevant - remove them
		var bracketIndex = pseudo.indexOf("(");
		if (bracketIndex>-1) {
			pseudo = pseudo.substring(0, bracketIndex)
		}		
		
		// check we're still dealing with a pseudo-class
		if (pseudo.charAt(0)==":") {
			switch (pseudo.slice(1)) {

				case "root":
					applyClass = function(e) {
						return isNegated ? e!=root : e==root
					};
					break;

				case "target":
					// :target is only supported in IE8
					if (ieVersion == 8) {
						applyClass = function(e) {
							var handler = function() { 
								var hash = location.hash;
								var hashID = hash.slice(1);
								return isNegated ? (hash=="" || e.id != hashID) : (hash!="" && e.id == hashID)
							};
							addEvent( win, "hashchange", function() {
								toggleElementClass(e, className, handler());
							});
							return handler()
						};
						break;
					}
					return false;
				
				case "checked":
					applyClass = function(e) { 
						if (RE_INPUT_CHECKABLE_TYPES.test(e.type)) {
							addEvent( e, "propertychange", function() {
								if (event.propertyName == "checked") {
									toggleElementClass( e, className, e.checked !== isNegated )
								} 							
							})
						}
						return e.checked !== isNegated
					}
					break;
					
				case "disabled":
					isNegated = !isNegated

				case "enabled":
					applyClass = function(e) { 
						if (RE_INPUT_ELEMENTS.test(e.tagName)) {
							addEvent( e, "propertychange", function() {
								if (event.propertyName == "$disabled") {
									toggleElementClass( e, className, e.$disabled === isNegated )
								} 
							});
							enabledWatchers.push(e);
							e.$disabled = e.disabled;
							return e.disabled === isNegated;
						}
						return pseudo==":enabled" ? isNegated : !isNegated;
					};
					break;
					
				case "focus":
					activateEventName = "focus";
					deactivateEventName = "blur";
								
				case "hover":
					if (!activateEventName) {
						activateEventName = "mouseenter";
						deactivateEventName = "mouseleave"
					}
					applyClass = function(e) {
						addEvent( e, isNegated ? deactivateEventName : activateEventName, function() {
							toggleElementClass( e, className, true )
						});
						addEvent( e, isNegated ? activateEventName : deactivateEventName, function() {
							toggleElementClass( e, className, false )
						});
						return isNegated
					} ;
					break;
					
				// everything else
				default:
					// If we don't support this pseudo-class don't create 
					// a patch for it
					if (!RE_PSEUDO_STRUCTURAL.test(pseudo)) {
						return false
					}
					break;
			}
		}
		return { className: className, applyClass: applyClass }
	}

	// --[ applyPatches() ]-------------------------------------------------
	// uses the passed selector text to find DOM nodes and patch them	
	function applyPatches(selectorText, patches) {
		var elms;
		
		// Although some selector libraries can find :checked :enabled etc. 
		// we need to find all elements that could have that state because 
		// it can be changed by the user.
		var domSelectorText = selectorText.replace(RE_LIBRARY_INCOMPATIBLE_PSEUDOS, EMPTY_STRING);
		
		// If the dom selector equates to an empty string or ends with 
		// whitespace then we need to append a universal selector (*) to it.
		if (domSelectorText == EMPTY_STRING || domSelectorText.charAt(domSelectorText.length-1) == SPACE_STRING) {
			domSelectorText += "*"
		}
		
		// Ensure we catch errors from the selector library
		try {
			elms = selectorMethod( domSelectorText )
		} catch (ex) {
			// #DEBUG_START
			log( "Selector '" + selectorText + "' threw exception '"+ ex +"'" );
			// #DEBUG_END
		}


		if (elms) {
			for (var d=0, dl=elms.length; d<dl; d++) {	
				var elm = elms[d];
				var cssClasses = elm.className;
				for (var f=0, fl=patches.length; f<fl; f++) {
					var patch = patches[f];
					
					if (!hasPatch(elm, patch)) {
						if (patch.applyClass && (patch.applyClass===true || patch.applyClass(elm)===true)) {
							cssClasses = toggleClass(cssClasses, patch.className, true )
						}
					}
				}
				elm.className = cssClasses
			}
		}
	}


	// --[ hasPatch() ]-----------------------------------------------------
	// checks for the exsistence of a patch on an element
	function hasPatch( elm, patch ) {
		return new RegExp("(^|\\s)" + patch.className + "(\\s|$)").test(elm.className)
	}
	
	
	// =========================== Utilitiy ================================
	
	function createClassName( className ) {
		return namespace + "-" + ((ieVersion==6 && patchIE6MultipleClasses) ?
			ie6PatchID++
		:
			className.replace(RE_PATCH_CLASS_NAME_REPLACE, function(a){return a.charCodeAt(0)}))
	}

	// --[ log() ]----------------------------------------------------------
	// #DEBUG_START
	function log( message ) {
		if (win.console) {
			win.console.log(message)
		}
	}
	// #DEBUG_END

	// --[ trim() ]---------------------------------------------------------
	// removes leading, trailing whitespace from a string
	function trim( text ) {
		return text.replace(RE_TIDY_TRIM_WHITESPACE, PLACEHOLDER_STRING)
	}

	// --[ normalizeWhitespace() ]------------------------------------------
	// removes leading, trailing and consecutive whitespace from a string
	function normalizeWhitespace( text ) {
		return trim(text).replace(RE_TIDY_CONSECUTIVE_WHITESPACE, SPACE_STRING)
	}

	// --[ normalizeSelectorWhitespace() ]----------------------------------
	// tidys whitespace around selector brackets and combinators
	function normalizeSelectorWhitespace( selectorText ) {
		return normalizeWhitespace(selectorText
			.replace(RE_TIDY_TRAILING_WHITESPACE, PLACEHOLDER_STRING)
			.replace(RE_TIDY_LEADING_WHITESPACE, PLACEHOLDER_STRING)
		)
	}

	// --[ toggleElementClass() ]-------------------------------------------
	// toggles a single className on an element
	function toggleElementClass( elm, className, on ) {
		var oldClassName = elm.className;
		var newClassName = toggleClass(oldClassName, className, on);
		if (newClassName != oldClassName) {
			elm.className = newClassName;
			elm.parentNode.className += EMPTY_STRING
		}
	}

	// --[ toggleClass() ]--------------------------------------------------
	// adds / removes a className from a string of classNames. Used to 
	// manage multiple class changes without forcing a DOM redraw
	function toggleClass( classList, className, on ) {
		var re = RegExp("(^|\\s)" + className + "(\\s|$)");
		var classExists = re.test(classList);
		if (on) {
			return classExists ? classList : classList + SPACE_STRING + className;
		} else {
			return classExists ? trim(classList.replace(re, PLACEHOLDER_STRING)) : classList
		}
	}
	
	// --[ addEvent() ]-----------------------------------------------------
	function addEvent(elm, eventName, eventHandler) {
		elm.attachEvent("on"+eventName, eventHandler)
	}


	// --[ getXHRObject() ]-------------------------------------------------
	function getXHRObject()
	{
		if (win.XMLHttpRequest) {
			return new XMLHttpRequest;
		}
		try	{ 
			return new ActiveXObject('Microsoft.XMLHTTP') ;
		} catch(e) { 
			return null;
		}
	}

	// --[ loadStyleSheet() ]-----------------------------------------------
	function loadStyleSheet( url ) {
		xhr.open("GET", url, false);
		xhr.send();
		return (xhr.status==200) ? xhr.responseText : EMPTY_STRING;	
	}	
	
	// --[ resolveUrl() ]---------------------------------------------------
	// Converts a URL fragment to a fully qualified URL using the specified
	// context URL. Returns null if same-origin policy is broken
	function resolveUrl( url, contextUrl ) {
	
		function getProtocolAndHost( url ) {
			return url.substring(0, url.indexOf("/", 8))
		}
		
		// absolute path
		if (/^https?:\/\//i.test(url)) {
			return getProtocolAndHost(contextUrl) == getProtocolAndHost(url) ? url : null 
		}
		
		// root-relative path
		if (url.charAt(0)=="/")	{
			return getProtocolAndHost(contextUrl) + url
		}

		// relative path
		var contextUrlPath = contextUrl.split("?")[0]; // ignore query string in the contextUrl	
		if (url.charAt(0)!="?" && contextUrlPath.charAt(contextUrlPath.length-1) != "/") {
			contextUrlPath = contextUrlPath.substring(0, contextUrlPath.lastIndexOf("/") + 1)
		}
		
		return contextUrlPath + url
	}
	
	// --[ parseStyleSheet() ]----------------------------------------------
	// Downloads the stylesheet specified by the URL, removes it's comments
	// and recursivly replaces @import rules with their contents, ultimately
	// returning the full cssText.
	function parseStyleSheet( url ) {
	
		
		if (url) {
			return loadStyleSheet(url).replace(RE_COMMENT, EMPTY_STRING)
			.replace(RE_IMPORT, function( match, quoteChar, importUrl, quoteChar2, importUrl2 ) { 
				return parseStyleSheet(resolveUrl(importUrl || importUrl2, url)) 
			})
			.replace(RE_ASSET_URL, function( match, quoteChar, assetUrl ) { 
				quoteChar = quoteChar || "";
				return " url(" + quoteChar + resolveUrl(assetUrl, url) + quoteChar + ") "; 
			});
		}
		return EMPTY_STRING
	}
	
	// --[ init() ]---------------------------------------------------------
	function init() {
		// honour the <base> tag
		var url, stylesheet;
		var baseTags = doc.getElementsByTagName("BASE");
		var baseUrl = (baseTags.length>0) ? baseTags[0].href : doc.location.href;
		
		/* Note: This code prevents IE from freezing / crashing when using 
		@font-face .eot files but it modifies the <head> tag and could
		trigger the IE stylesheet limit. It will also cause FOUC issues.
		If you choose to use it, make sure you comment out the for loop 
		directly below this comment.

		var head = doc.getElementsByTagName("head")[0];
		for (var c=doc.styleSheets.length-1; c>=0; c--) {
			stylesheet = doc.styleSheets[c]
			head.appendChild(doc.createElement("style"))
			var patchedStylesheet = doc.styleSheets[doc.styleSheets.length-1];
			
			if (stylesheet.href != EMPTY_STRING) {
				url = resolveUrl(stylesheet.href, baseUrl)
				if (url) {
					patchedStylesheet.cssText = patchStyleSheet( parseStyleSheet( url ) )
					stylesheet.disabled = true
					setTimeout( function () {
						stylesheet.owningElement.parentNode.removeChild(stylesheet.owningElement)
					})
				}
			}
		}
		*/
		
		for (var c=0; c<doc.styleSheets.length; c++) {
			stylesheet = doc.styleSheets[c];
			if (stylesheet.href != EMPTY_STRING) {
				url = resolveUrl(stylesheet.href, baseUrl);
				if (url) {
					stylesheet.cssText = patchStyleSheet( parseStyleSheet( url ) )
				}
			}
		}
		
		// :enabled & :disabled polling script (since we can't hook 
		// onpropertychange event when an element is disabled) 
		if (enabledWatchers.length>0) {
			setInterval( function() {
				for (var c=0, cl=enabledWatchers.length; c<cl; c++) {
					var e = enabledWatchers[c];
					if (e.disabled !== e.$disabled) {
						if (e.disabled) {
							e.disabled = false;
							e.$disabled = true;
							e.disabled = true
						}
						else {
							e.$disabled=e.disabled
						}
					}
				}
			},250)
		}
	}
	
	// --[ determineSelectorMethod() ]--------------------------------------
	// walks through the selectorEngines object testing for an suitable
	// selector engine.
	function determineSelectorMethod() {
		var method;
		for (var engine in selectorEngines) {
			if (win[engine] && (method = eval(selectorEngines[engine].replace("*",engine)))) {
				return method
			}
		}
		return false
	}
	
	// Emulate DOMReady event (Dean Edwards)
	doc.write("<script id="+domReadyScriptID+" defer src='//:'><\/script>");
	doc.getElementById(domReadyScriptID).onreadystatechange = function() {
		if (this.readyState=='complete') {				
			selectorMethod = determineSelectorMethod();
			if (selectorMethod) {
				init();
				this.parentNode.removeChild(this)
			}
		}
	}
})(this);
/**
  * CMS JS Function
  */

function previewAction(formId, formObj, url){
    var formElem = $(formId);
    var previewWindowName = 'cms-page-preview-' + $('page_page_id').value;

    formElem.writeAttribute('target', previewWindowName);
    formObj.submit(url);
    formElem.writeAttribute('target', '');
}

function publishAction(publishUrl){
    setLocation(publishUrl);
}

function saveAndPublishAction(formObj, saveUrl){
    formObj.submit(saveUrl + 'back/publish/');
}

function dataChanged() {
    var buttonSaveAndPublish = $('save_publish_button');
    if (buttonSaveAndPublish && buttonSaveAndPublish.hasClassName('no-display')) {
        var buttonPublish = $('publish_button');
        if (buttonPublish) {
            buttonPublish.hide();
        }
        buttonSaveAndPublish.removeClassName('no-display');
    }
}

varienGlobalEvents.attachEventHandler('tinymceChange', dataChanged);
(function ($) { $(function () {


	$('.cms-hierarchy .fieldset')
		.each (
			function () {
				df.admin.configForm.Fieldset
					.construct (
						{
							element: $(this)
						}
					)
				;
			}
		)
	;


	$('.cms-hierarchy .fieldset .df-field')
		.change (
			function () {
				if ('undefined' !== typeof hierarchyNodes) {
					hierarchyNodes.nodeChanged()
				}
			}
		)
	;



}); })(jQuery);
(function ($) {

	df.namespace ('df.admin.configForm');


	/**
	 * @param {jQuery} HTMLElement
	 */
	df.admin.configForm.Field = {

		construct: function (_config) { var _this = {


			init: function () {

				if (this.getMasterField()) {

					this.getMasterField().getElement()
						.change (
							function () {
								_this.updateVisibilityByMasterField ();
							}
						)
					;


					this.updateVisibilityByMasterField ();


					$(window)
						.bind (
							'df.admin.cms.hierarchy.node.formUpdated'
							,
							function () {
								_this.updateVisibilityByMasterField ();
							}
						)
					;

				}

			}




			,
			/**
			 * @private
			 * @returns {String[]}
			 */
			getCssClasses: function () {

				if ('undefined' === typeof this._cssClasses) {

					/** @type {?String} */
					var cssClassesAsString = this.getElement().attr('class');

					this._cssClasses =
							('undefined' === typeof cssClassesAsString)
						?
							[]
						:
							cssClassesAsString.split(/\s+/)
					;

				}

				return this._cssClasses;
			}





			,
			/**
			 * @private
			 * @returns {jQuery} HTMLTableRowElement
			 */
			getContainer: function () {

				if ('undefined' === typeof this._container) {

					this._container = this.getElement().closest ('tr');

				}

				return this._container;
			}







			,
			/**
			 * @private
			 * @returns {?df.admin.configForm.Field}
			 */
			getMasterField: function () {

				if ('undefined' === typeof this._masterField) {


					/**
					 * @type {?df.admin.configForm.Field}
					 */
					this._masterField = null;

					$.each (this.getCssClasses(), function () {

						/** @type {String} */
						var _class = this;


						/** @type {RegExp} */
						var pattern = /df\-depends\-\-(.*)/;


						/** @type {?String[]} */
						var matches = _class.match (pattern);

						if (null !== matches) {

							/** @type {String} */
							var masterElementId = matches[1];


							/** @type {HTMLElement} */
							var masterElement = document.getElementById (masterElementId);


							if (masterElement) {

								/**
								 * @type {?df.admin.configForm.Field}
								 */
								_this._masterField =
									df.admin.configForm.Field
										.construct (
											{
												element: $(masterElement)
											}
										)
								;

								return false;

							}

						}
					});

				}

				return this._masterField;
			}





			,
			/**
			 * @private
			 * @returns {df.admin.configForm.Field}
			 */
			updateVisibilityByMasterField: function () {

				if (this.getMasterField()) {
					if ('0' === this.getMasterField().getElement().val().toString()) {
						this.getContainer().hide ();
					}
					else {
						this.getContainer().show ();
					}

				}

				return this;
			}





			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getElement: function () {
				return _config.element;
			}


		}; _this.init (); return _this; }


	};





})(jQuery);(function ($) {

	df.namespace ('df.admin.configForm');


	/**
	 * @param {jQuery} HTMLElement
	 */
	df.admin.configForm.Fieldset = {

		construct: function (_config) { var _this = {


			init: function () {

				$(':input', this.getElement())
					.each (
						function () {
							df.admin.configForm.Field
								.construct (
									{
										element: $(this)
									}
								)
							;

						}
					)
				;


			}


			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getElement: function () {
				return _config.element;
			}


		}; _this.init (); return _this; }


	};





})(jQuery);(function ($) {

	$(function () {

		/** @type {jQuery} HTMLSelectElement */
		var $locale = $('#interface_locale');

		/**
		 * Применяем заплатки перевода только для русскоязычного административного интерфейса
		 */
		if ('ru_RU' === $locale.val()) {

			$(window)
				.bind (
					'bundle.product.edit.bundle.option.selection'
					,
					function () {
						if ('undefined' !== typeof bSelection) {

							if ('undefined' !== typeof bSelection.templateBox) {

								/** @type {jQuery}  */
								var $template =
									$('<div/>')
										.append (
											$(bSelection.templateBox)
										)
								;


								(function () {
									/** @type {jQuery} HTMLElement[] */
									var $headers = $('th.type-price', $template);

									$headers.each (function () {
										/** @type {jQuery} HTMLElement*/
										var $this = $(this);

										if ('Цена' === $this.text()) {
											$this.text ('наценка');
										}
									});
								})();


								(function () {
									/** @type {jQuery} HTMLElement[] */
									var $headers = $('th', $template);

									$headers.each (function () {
										/** @type {jQuery} HTMLElement*/
										var $this = $(this);
										$this.text ($this.text().toLowerCase());
									});
								})();

								bSelection.templateBox = $template.html();
							}


							if ('undefined' !== typeof bSelection.templateRow) {
								bSelection.templateRow =
									bSelection.templateRow.replace ('конкретно указанный', 'абсолютная')
								;
							}
						}
					}
				)
			;
		}
	});

})(jQuery);
(function ($) { $(function () {

	df.namespace ('df.localization.verification');


	df.localization.verification.FileList = {

		itemSelected: 'df.localization.verification.fileList.itemSelected'

		,

		construct: function (_config) { var _this = {


			init:
				function () {

					this.getFileElements().filter(':odd').addClass ('df-file-odd');

					this.getFileElements().filter(':even').addClass ('df-file-even');


					this.getElement()
						.css (
							'max-height'
							,
							Math.round (0.5 * screen.height) + "px"
						)
					;



					this.getFileElements()
						.hover (
							function () {
								$(this).addClass ('df-file-hovered');
							}
							,
							function () {
								$(this).removeClass ('df-file-hovered');
							}
						)


						.click (

							function () {

								var $this = $(this);


								$this.addClass ('df-file-selected');
								$this.siblings().removeClass ('df-file-selected');


								/** @type {String} */
								var fileName =
									$.trim (
										$('.df-name', $this).text()
									)
								;

								$(window)
									.trigger (
										{

											/** @type {String} */
											type: df.localization.verification.FileList.itemSelected


											,
											/** @type {Object} */
											file: {

												/** @type {String} */
												name: fileName
											}
										}
									)
								;
							}

						)
					;

				}



			,
			getFileElements:
				/**
				 * @type {jQuery} HTMLLIElement[]
				 */
				function () {
					if ('undefined' == typeof this._fileElements) {

						/** @type {jQuery} HTMLLIElement[] */
						var result =
							$('.df-file', this.getElement())
						;

						this._fileElements = result;

					}

					return this._fileElements;
				}


			,
			getElementSelector:
				function () {
					return _config.elementSelector;
				}


			,
			getElement:
				function () {

					if ('undefined' == typeof this._element) {

						/** @type {jQuery} HTMLElement */
						var result =
							$(this.getElementSelector ())
						;

						this._element = result;

					}

					return this._element;
				}
		}; _this.init (); return _this; }


	};





}); })(jQuery);(function ($) { $(function () {

	df.namespace ('df.localization.verification');


	df.localization.verification.FileWorkspace = {

		construct: function (_config) { var _this = {


			init:
				function () {

					$(window)
						.bind (
							df.localization.verification.FileList.itemSelected
							,
							/**
							 * Отображаем подробную информацию о текущем файле
							 *
							 * @param {jQuery.Event} event
							 */
							function (event) {

								_this.getElement().removeClass ('df-hidden');

								_this.showFileDetails (event.file.name);

							}
						)
					;

				}


			,
			showFileDetails:
				/**
				 * Отображаем подробную информацию о текущем файле
				 *
				 * @param {String} fileName
				 */
				function (fileName) {

					this.getTitleElement().text (fileName);


					this.getUntranslatedListElement().empty ();
					this.getAbsentListElement().empty ();



					/**
					 * @type {?Object}
					 */
					var details =
						df.localization.verification.details [fileName]
					;



					if (details) {
						this
							.fillList (
								this.getUntranslatedListElement()
								,
								details.untranslatedEntries
								,
								'df-untranslatedItem'
							)

							.fillList (
								this.getAbsentListElement()
								,
								details.absentEntries
								,
								'df-absentItem'
							)
						;
					}


					return this;

				}




			,
			fillList:
				/**
				 * @param {jQuery(HTMLOListElement)} list
				 * @param {Array} items
				 * @param {String} itemClass
				 */
				function (list, items, itemClass) {

					if ($.isArray (items)) {

						if (0 === items.length) {
							list.parent().addClass ('df-hidden');
						}
						else {
							list.parent().removeClass ('df-hidden');
						}

						$
							.each (
								items
								,
								function (index, item) {

									list
										.append (
											$('<li/>')
												.addClass (itemClass)
												.text (item)
										)
									;


								}
							)
						;

					}


					return this;

				}




			,
			getUntranslatedListElement:
				/**
				 * @returns {jQuery} HTMLElement
				 */
				function () {

					if ('undefined' == typeof this._untranslatedListElement) {

						/** @type {jQuery} HTMLElement */
						var result =
							$('.df-untranslatedItems', this.getElement ())
						;

						this._untranslatedListElement = result;

					}

					return this._untranslatedListElement;
				}




			,
			getAbsentListElement:
				/**
				 * @returns {jQuery} HTMLElement
				 */
				function () {

					if ('undefined' == typeof this._absentListElement) {

						/** @type {jQuery} HTMLElement */
						var result =
							$('.df-absentItems', this.getElement ())
						;

						this._absentListElement = result;

					}

					return this._absentListElement;
				}





			,
			getTitleElement:
				/**
				 * @returns {jQuery} HTMLElement
				 */
				function () {

					if ('undefined' == typeof this._titleElement) {

						/** @type {jQuery} HTMLElement */
						var result =
							$('h2', this.getElement ())
						;

						this._titleElement = result;

					}

					return this._titleElement;
				}



			,
			getElementSelector:
				/**
				 * @returns {String}
				 */
				function () {
					return _config.elementSelector;
				}


			,
			getElement:
				/**
				 * @returns {jQuery} HTMLElement
				 */
				function () {

					if ('undefined' == typeof this._element) {

						/** @type {jQuery} HTMLElement */
						var result =
							$(this.getElementSelector ())
						;

						this._element = result;

					}

					return this._element;
				}

		}; _this.init (); return _this; }


	};





}); })(jQuery);/**
 * Программный код,
 * который надо выполнить сразу после загрузки страницы
 */

(function ($) { $(function () {

	df.localization.verification.FileList
		.construct (
			{
				elementSelector: '.df .df-localization .df-verification .df-files .df-files-body'
			}
		)
	;


	df.localization.verification.FileWorkspace
		.construct (
			{
				elementSelector: '.df .df-localization .df-verification .df-fileDetails'
			}
		)
	;


}); })(jQuery);(function ($) { $(function () {


	/** @type {jQuery} HTMLLIElement[] */
	var $products = $('.df .df-sales-admin-widget-grid-column-renderer-products .df-product');

	$products.filter(':odd').addClass ('df-product-odd');
	$products.filter(':even').addClass ('df-product-even');

}); })(jQuery);if ('undefined' !== typeof RegionUpdater) {
	RegionUpdater.prototype.update =
		function () {
			if (this.regions[this.countryEl.value]) {
	//            if (!this.regionSelectEl) {
	//                Element.insert(this.regionTextEl, {after : this.tpl.evaluate(this._regionSelectEl)});
	//                this.regionSelectEl = $(this._regionSelectEl.id);
	//            }
				if (this.lastCountryId!=this.countryEl.value) {
					var i, option, region, def;

					if (this.regionTextEl) {
						def = this.regionTextEl.value.toLowerCase();
						this.regionTextEl.value = '';
					}
					if (!def) {
						def = this.regionSelectEl.getAttribute('defaultValue');
					}

					this.regionSelectEl.options.length = 1;
					for (regionId in this.regions[this.countryEl.value]) {
						region = this.regions[this.countryEl.value][regionId];


						/**
						 * BEGIN PATCH
						 */
						regionId = region.id;

						if ('undefined' === typeof regionId) {
							continue;
						}
						/**
						 * END PATCH
						 */

						option = document.createElement('OPTION');
						option.value = regionId;
						option.text = region.name;

						if (this.regionSelectEl.options.add) {
							this.regionSelectEl.options.add(option);
						} else {
							this.regionSelectEl.appendChild(option);
						}

						if (regionId==def || region.name.toLowerCase()==def || region.code.toLowerCase()==def) {
							this.regionSelectEl.value = regionId;
						}
					}
				}

				if (this.disableAction=='hide') {
					if (this.regionTextEl) {
						this.regionTextEl.style.display = 'none';
						this.regionTextEl.style.disabled = true;
					}
					this.regionSelectEl.style.display = '';
					this.regionSelectEl.disabled = false;
				} else if (this.disableAction=='disable') {
					if (this.regionTextEl) {
						this.regionTextEl.disabled = true;
					}
					this.regionSelectEl.disabled = false;
				}
				this.setMarkDisplay(this.regionSelectEl, true);

				this.lastCountryId = this.countryEl.value;
			} else {
				if (this.disableAction=='hide') {
					if (this.regionTextEl) {
						this.regionTextEl.style.display = '';
						this.regionTextEl.style.disabled = false;
					}
					this.regionSelectEl.style.display = 'none';
					this.regionSelectEl.disabled = true;
				} else if (this.disableAction=='disable') {
					if (this.regionTextEl) {
						this.regionTextEl.disabled = false;
					}
					this.regionSelectEl.disabled = true;
					if (this.clearRegionValueOnDisable) {
						this.regionSelectEl.value = '';
					}
				} else if (this.disableAction=='nullify') {
					this.regionSelectEl.options.length = 1;
					this.regionSelectEl.value = '';
					this.regionSelectEl.selectedIndex = 0;
					this.lastCountryId = '';
				}
				this.setMarkDisplay(this.regionSelectEl, false);

	//            // clone required stuff from select element and then remove it
	//            this._regionSelectEl.className = this.regionSelectEl.className;
	//            this._regionSelectEl.name      = this.regionSelectEl.name;
	//            this._regionSelectEl.id        = this.regionSelectEl.id;
	//            this._regionSelectEl.innerHTML = this.regionSelectEl.innerHTML;
	//            Element.remove(this.regionSelectEl);
	//            this.regionSelectEl = null;
			}
			varienGlobalEvents.fireEvent("address_country_changed", this.countryEl);
		}
	;
}