/**
 * Обратите внимание,
 * что имя файла намеренно начинается с символа подчёркивания.
 * Благодаря этому, сборщик (компилятор) помещает функции этого файла до других
 * (он размещает их в алфавитном порядке).
 *
 * Содержит общеупотребительные функции,
 * которые разумно использовать не только в данном модуле,
 * но и в других.
 */

/**
 * Обратите внимание, что без начального «;»
 * стандартное слияние файлов JavaScript в Magento создаёт сбойный файл
 */
;(function ($) {

	$.extend (true, window,{
		df: {

			namespace:

				/**
				 * Создаёт иерархическое объектное пространство имён.
				 * Пример применения:
				 *
				 * df.namespace ('df.catalog.showcase');
				 *
				 * df.catalog.showcase.product = {
				 * 		<...>
				 * };
				 *
				 */
				function () {
					var a=arguments, o=null, i, j, d;
					for (i=0; i<a.length; i+=1) {
						d=a[i].split(".");
						o=window;
						for (j=0; j<d.length; j+=1) {
							o[d[j]]=o[d[j]] || {};
							o=o[d[j]];
						}
					}
					return o;
				}

			,
			format: {
				/**
				 * Форматирует число number как строку из length цифр,
				 * добавляя, при необходимости, нули в начале строки
				 *
				 * @param {Number} number
				 * @param {Number} length
				 * @returns {String}
				 */
				pad: function (number, length) {

					var result = number.toString();

					if (result.length < length) {
						result =
							('0000000000' + result)
								.slice(-length)
						;
					}

					return result;

				}



				,
				date: {

					russianLong:
						/**
						 * Преобразовывает объект-дату
						 * в строку-дату российском формате
						 * (дд.мм.ГГГГ)
						 *
						 * @param {Date} date
						 * @returns {String}
						 */
						function (date) {

							/** @type {String} */
							var result =
								[
									atalan.format.pad (date.getDate(), 2)
									,
									atalan.format.pad (1 + date.getMonth(), 2)
									,
									date.getFullYear()

								].join ('.')
							;

							return result;

						}
				}
			}



			,
			dom: {

				getChildrenTextNodes:

					function (node, includeWhitespaceNodes) {

						var textNodes = [], whitespace = /^\s*$/;

						function getTextNodes(node) {
							if (3 == node.nodeType) {
								if (includeWhitespaceNodes || !whitespace.test(node.nodeValue)) {
									textNodes.push(node);
								}
							}
							else {
								for (var i = 0, len = node.childNodes.length; i < len; ++i) {
									getTextNodes(node.childNodes[i]);
								}
							}
						}

						getTextNodes(node);

						return textNodes;
					}
			}


			,
			reduce: function (array, fnReduce, valueInitial) {
				$.each (array, function (index, value) {
					valueInitial = fnReduce.call (value, valueInitial, value, index, array);
				});
				return valueInitial;
			}
		}
	});






})(jQuery);




/** @const */
var DF_T_EMPTY = '';



/**
 * @function
 * @param {Boolean} value
 * @returns {Boolean}
 */
var df_validate_boolean = function (value) {

	/** @type {Boolean} */
	var result =
			(true === value)
		||
			(false === value)
	;

	return result;

};





/**
 * @function
 * @param {Boolean} condition
 * @param {*} message
 * @returns void
 */
var df_assert = function (condition, message) {

	if (!df_validate_boolean (condition)) {
		df_error ('Условие должно быть значением логического типа');
	}

	if (false === condition) {
		df_error (message);
	}

};





/**
 * @function
 * @param {number|string} expected
 * @param {number|string} given
 * @returns void
 */
var df_assert_equals = function (expected, given) {

	df_assert_boolean (
		(expected == given)
		,
		'Ожидалось: %expected%, получено: %given%'
			.replace ('%expected%', expected)
			.replace ('%given%', given)
	)
	;

};







/**
 * @function
 * @param {String} message
 * @returns void
 */
var df_error = function (message) {

	throw new Error (message);

};







/**
 * @param {Boolean} value
 * @param {string=} message
 * @returns void
 */
var df_assert_boolean = function (value, message) {

	message = !df_is_defined (message) ? 'Требуется значение логического типа.' : message;

	df_assert (
		df_validate_boolean (value)
		,
		message
	)
	;

};










/**
 * @function
 * @param {String} value
 * @returns {Boolean}
 */
var df_validate_string = function (value) {

	/** @type {Boolean} */
	var result = ('string' === typeof (value));

	df_assert_boolean (result);

	return result;

};




/**
 * @function
 * @param {String} value
 * @returns void
 */
var df_assert_string = function (value) {

	df_assert (
		df_validate_string (value)
		,
		'Требуется строка.'
	)
	;

};









/**
 * @function
 * @param {Object} value
 * @returns {Boolean}
 */
var df_validate_object = function (value) {

	/** @type {Boolean} */
	var result =
			!(value instanceof Array)
		&&
			(null !== value)
		&&
			('object' === typeof value)
	;


	df_assert_boolean (result);

	return result;

};




/**
 * @function
 * @param {Object} value
 * @returns void
 */
var df_assert_object = function (value) {

	df_assert (
		df_validate_object (value)
		,
		'Требуется объект.'
	)
	;

};




/**
 * @param value
 * @returns {Boolean}
 */
var df_is_defined = function (value) {

	/** @type {Boolean} */
	var result = ('undefined' !== typeof value);

	return result;
};







/**
 * @function
 * @param {Object} object
 * @returns {String}
 *
 * @url http://webreflection.blogspot.com/2007/01/javascript-getclass-and-isa-functions.html
 */
var get_class = function (object) {


	/**
	 * @function
	 * @param {String} object
	 * @returns {String}
	 */
	var getClassInternal = function (object) {


		/**
		 * @type {String}
		 */
		var code = "".concat(object);



		/**
		 * @type {String}
		 */
		var name =
			code
				.replace(
					/^.*function\s+([^\s]*|[^\(]*)\([^\x00]+$/
					,
					"$1"
				)
		;


		/**
		 * @type {String}
		 */
		var result = code || "anonymous";


		return result;

	};



	/**
	 * @type {String}
	 */
	var result = "";



	if (null === object) {
		result = "null";
	}
	else if ('undefined' == typeof object) {
		result = "undefined";
	}
	else {
		result = getClassInternal (object.constructor);
		if (
				("Object" === result)
			&&
				object.constructor.prototype
		) {

			for (result in this) {
				if (
						("function" === typeof(this[result]))
					&&
						(object instanceof this[result])
				) {
					result = getClassInternal(this[result]);
					break;
				}
			}
		}
	}

	return result;
};



(function () {

	if ('undefined' === typeof Validation) {
		alert ('Стандартный класс Validation ещё не загружен. Сообщите об этом разработчику!');
	}
	else {


		Object.extend(Validation, {


			/**
			 * @param {Element} elm
			 * @return {Boolean}
			 */
			dfIsVisibleAndNotEmpty : function (elm) {

				/** @type {Boolean} */
				var result =
						Validation.df.parent.isVisible(elm)
					&&
						/**
						 * Временно считаем пустые поля "невидимыми",
						 * чтобы стандарный класс не считал их неправильно заполненными
						 */
						('' !== $F(elm))
				;

				return result;
			}

			,
			/**
			 * Данный метод проверяет корректность заполнения формы
			 * так же, как и стандартный метод test(),
			 * но не выводит диагностических сообщений.
			 *
			 * Это используется при Быстром оформлении заказа
			 *
			 * @function
			 * @param {String} name
			 * @param {Element} elm
			 * @param {Boolean} useTitle
			 * @return {Boolean}
			 */
			dfTestSilent: function (name, elm, useTitle) {

				/** @type {Boolean} */
				var result = false;

				/** @type {Validator} */
				var validator = Validation.get(name);

				try {
					result = (!Validation.isVisible(elm) || validator.test($F(elm), elm));
				}
				catch (e) {
					alert ("exception: " + e.message);
					alert (e.stack.toString ());
					console.log (e.message);
					console.log (e.stack.toString ());
					throw(e);
				}

				return result;

			}

		});



		Object.extend(Validation.prototype, {


			/**
			 * Это используется при Быстром оформлении заказа
			 *
			 * @function
			 * @return {Boolean}
			 */
			dfValidateFilledFieldsOnly: function () {

				/** @type {Boolean} */
				var result = false;

				df.namespace ('Validation.df.parent');

				Validation.df.parent.isVisible = Validation.isVisible;

				try {
					Validation.isVisible = Validation.dfIsVisibleAndNotEmpty;
					result = this.validate ();
				}
				finally {
					Validation.isVisible = Validation.df.parent.isVisible;
				}

				return result;

			}


			,
			/**
			 * Данный метод проверяет корректность заполнения формы
			 * так же, как и стандартный метод validate(),
			 * но не выводит диагностических сообщений.
			 *
			 * Это используется при Быстром оформлении заказа
			 *
			 * @function
			 * @return {Boolean}
			 */
			dfValidateSilent: function () {

				/** @type {Boolean} */
				var result = false;

				/** @function */
				var standardMethod = Validation.test;

				try {
					Validation.test = Validation.dfTestSilent;
					result = this.validate ();
				}
				finally {
					Validation.test = standardMethod;
				}

				return result;

			}

		});


	}


})();



(function () {

	if ('undefined' != typeof Validation) {

		Validation
			.addAllThese (
				[


					[
						'df.validate.firstName'
						,
						'Имя должно состоять только из букв.'
						,


						/**
						 * @param {String} value
						 * @returns {Boolean}
						 */
						function (value) {

							/** @type {Boolean} */
							var result =
								/^[a-zA-Zа-яА-ЯёЁ]*$/.test(value)
							;

							return result;

						}
					]



					,
					[
						'df.validate.lastName'
						,
						'Фамилия должна состоять только из буквы, дефиса («-») и пробелов'
						,


						/**
						 * @param {String} value
						 * @returns {Boolean}
						 */
						function (value) {

							/** @type {Boolean} */
							var result =
								/^[a-zA-Zа-яА-ЯёЁ\-\s]*$/.test(value)
							;

							return result;

						}
					]



					,
					[
						'df.validate.patronymic'
						,
						'Отчество должно состоять только из буквы, дефиса («-») и пробелов'
						,


						/**
						 * @param {String} value
						 * @returns {Boolean}
						 */
						function (value) {

							/** @type {Boolean} */
							var result =
								/^[a-zA-Zа-яА-ЯёЁ\-\s]*$/.test(value)
							;

							return result;

						}
					]


					,
					[
						'df.validate.postalCode'
						,
						'Данное поле должно содержать 6 цифр.'
						,

						/**
						 * @param {String} value
						 * @returns {Boolean}
						 */
						function (value) {

							/** @type {Boolean} */
							var result =
									Validation.get('IsEmpty').test(value)
								||
									/^[\d]{6}$/.test(value)
							;

							return result;

						}
					]




					,
					[
						'df.validate.phone'
						,
						'Укажите действующий телефонный номер'
						,


						/**
						 * @param {String} value
						 * @returns {Boolean}
						 */
						function (value) {

							/** @type {Boolean} */
							var result =
									Validation.get('IsEmpty').test(value)
								||
									/^[\d\-\(\)\+\s]{5,20}$/.test(value)
							;

							return result;

						}
					]




					,
					[
						'df.validate.city'
						,
						'Название города должно состоять только из букв, дефиса («-») и пробелов'
						,


						/**
						 * @param {String} value
						 * @returns {Boolean}
						 */
						function (value) {

							/** @type {Boolean} */
							var result =
									Validation.get('IsEmpty').test(value)
								||
									/^[a-zA-Zа-яА-ЯёЁ\-\s]*$/.test(value)
							;

							return result;

						}
					]


					,
					[
						'df.validate.region.text'
						,
						'Название области должно состоять только из букв, дефиса («-») и пробелов'
						,


						/**
						 * @param {String} value
						 * @returns {Boolean}
						 */
						function (value) {

							/** @type {Boolean} */
							var result =
									Validation.get('IsEmpty').test(value)
								||
									/^[a-zA-Zа-яА-ЯёЁ\-\s]+$/.test(value)
							;

							return result;

						}
					]



					,
					[
						'df.validate.urlKey'
						,
						'Уникальная часть веб-адреса должна начинаться с буквы или цифры, '
						+ 'затем допустимы буквы, цифры, символ пути («/»), дефис («-»), символ подчёркивания («_»). '
						+ 'В конце допустимо расширение, как у имён файлов: '
						+ 'точка («.») и после неё: буквы, цифры, дефис («-») и символ подчёркивания («_») .'
						,


						/**
						 * @param {String} value
						 * @returns {Boolean}
						 */
						function (value) {

							/** @type {Boolean} */
							var result =
									Validation.get('IsEmpty').test(value)
								||
									/^[a-zа-яА-ЯёЁ0-9][a-zа-яА-ЯёЁ0-9_\/-]+(\.[a-zа-яА-ЯёЁ0-9_-]+)?$/.test(value)
							;

							return result;

						}
					]


				]
			)
		;

	}

})();

/**
* DD_belatedPNG: Adds IE6 support: PNG images for CSS background-image and HTML <IMG/>.
* Author: Drew Diller
* Email: drew.diller@gmail.com
* URL: http://www.dillerdesign.com/experiment/DD_belatedPNG/
* Version: 0.0.8a
* Licensed under the MIT License: http://dillerdesign.com/experiment/DD_belatedPNG/#license
*
* Example usage:
* DD_belatedPNG.fix('.png_bg'); // argument is a CSS selector
* DD_belatedPNG.fixPng( someNode ); // argument is an HTMLDomElement
**/

/*
PLEASE READ:
Absolutely everything in this script is SILLY.  I know this.  IE's rendering of certain pixels doesn't make sense, so neither does this code!
*/


if (jQuery.browser.msie && (6 == jQuery.browser.version)) {



	var DD_belatedPNG = {
		ns: 'DD_belatedPNG',
		imgSize: {},
		delay: 10,
		nodesFixed: 0,
		createVmlNameSpace: function () { /* enable VML */
			if (document.namespaces && !document.namespaces[this.ns]) {
				document.namespaces.add(this.ns, 'urn:schemas-microsoft-com:vml');
			}
		},
		createVmlStyleSheet: function () { /* style VML, enable behaviors */
			/*
				Just in case lots of other developers have added
				lots of other stylesheets using document.createStyleSheet
				and hit the 31-limit mark, let's not use that method!
				further reading: http://msdn.microsoft.com/en-us/library/ms531194(VS.85).aspx
			*/
			var screenStyleSheet, printStyleSheet;
			screenStyleSheet = document.createElement('style');
			screenStyleSheet.setAttribute('media', 'screen');
			document.documentElement.firstChild.insertBefore(screenStyleSheet, document.documentElement.firstChild.firstChild);
			if (screenStyleSheet.styleSheet) {
				screenStyleSheet = screenStyleSheet.styleSheet;
				screenStyleSheet.addRule(this.ns + '\\:*', '{behavior:url(#default#VML)}');
				screenStyleSheet.addRule(this.ns + '\\:shape', 'position:absolute;');
				screenStyleSheet.addRule('img.' + this.ns + '_sizeFinder', 'behavior:none; border:none; position:absolute; z-index:-1; top:-10000px; visibility:hidden;'); /* large negative top value for avoiding vertical scrollbars for large images, suggested by James O'Brien, http://www.thanatopsic.org/hendrik/ */
				this.screenStyleSheet = screenStyleSheet;

				/* Add a print-media stylesheet, for preventing VML artifacts from showing up in print (including preview). */
				/* Thanks to R�mi Pr�vost for automating this! */
				printStyleSheet = document.createElement('style');
				printStyleSheet.setAttribute('media', 'print');
				document.documentElement.firstChild.insertBefore(printStyleSheet, document.documentElement.firstChild.firstChild);
				printStyleSheet = printStyleSheet.styleSheet;
				printStyleSheet.addRule(this.ns + '\\:*', '{display: none !important;}');
				printStyleSheet.addRule('img.' + this.ns + '_sizeFinder', '{display: none !important;}');
			}
		},
		readPropertyChange: function () {
			var el, display, v;
			el = event.srcElement;
			if (!el.vmlInitiated) {
				return;
			}
			if (event.propertyName.search('background') != -1 || event.propertyName.search('border') != -1) {
				DD_belatedPNG.applyVML(el);
			}
			if (event.propertyName == 'style.display') {
				display = (el.currentStyle.display == 'none') ? 'none' : 'block';
				for (v in el.vml) {
					if (el.vml.hasOwnProperty(v)) {
						el.vml[v].shape.style.display = display;
					}
				}
			}
			if (event.propertyName.search('filter') != -1) {
				DD_belatedPNG.vmlOpacity(el);
			}
		},
		vmlOpacity: function (el) {
			if (el.currentStyle.filter.search('lpha') != -1) {
				var trans = el.currentStyle.filter;
				trans = parseInt(trans.substring(trans.lastIndexOf('=')+1, trans.lastIndexOf(')')), 10)/100;
				el.vml.color.shape.style.filter = el.currentStyle.filter; /* complete guesswork */
				el.vml.image.fill.opacity = trans; /* complete guesswork */
			}
		},
		handlePseudoHover: function (el) {
			setTimeout(function () { /* wouldn't work as intended without setTimeout */
				DD_belatedPNG.applyVML(el);
			}, 1);
		},
		/**
		* This is the method to use in a document.
		* @param {String} selector - REQUIRED - a CSS selector, such as '#doc .container'
		**/
		fix: function (selector) {
			if (this.screenStyleSheet) {
				var selectors, i;
				selectors = selector.split(','); /* multiple selectors supported, no need for multiple calls to this anymore */
				for (i=0; i<selectors.length; i++) {
					this.screenStyleSheet.addRule(selectors[i], 'behavior:expression(DD_belatedPNG.fixPng(this))'); /* seems to execute the function without adding it to the stylesheet - interesting... */
				}
			}
		},
		applyVML: function (el) {
			el.runtimeStyle.cssText = '';
			this.vmlFill(el);
			this.vmlOffsets(el);
			this.vmlOpacity(el);
			if (el.isImg) {
				this.copyImageBorders(el);
			}
		},
		attachHandlers: function (el) {
			var self, handlers, handler, moreForAs, a, h;
			self = this;
			handlers = {resize: 'vmlOffsets', move: 'vmlOffsets'};
			if (el.nodeName == 'A') {
				moreForAs = {mouseleave: 'handlePseudoHover', mouseenter: 'handlePseudoHover', focus: 'handlePseudoHover', blur: 'handlePseudoHover'};
				for (a in moreForAs) {
					if (moreForAs.hasOwnProperty(a)) {
						handlers[a] = moreForAs[a];
					}
				}
			}
			for (h in handlers) {
				if (handlers.hasOwnProperty(h)) {
					handler = function () {
						self[handlers[h]](el);
					};
					el.attachEvent('on' + h, handler);
				}
			}
			el.attachEvent('onpropertychange', this.readPropertyChange);
		},
		giveLayout: function (el) {
			el.style.zoom = 1;
			if (el.currentStyle.position == 'static') {
				el.style.position = 'relative';
			}
		},
		copyImageBorders: function (el) {
			var styles, s;
			styles = {'borderStyle':true, 'borderWidth':true, 'borderColor':true};
			for (s in styles) {
				if (styles.hasOwnProperty(s)) {
					el.vml.color.shape.style[s] = el.currentStyle[s];
				}
			}
		},
		vmlFill: function (el) {
			if (!el.currentStyle) {
				return;
			} else {
				var elStyle, noImg, lib, v, img, imgLoaded;
				elStyle = el.currentStyle;
			}
			for (v in el.vml) {
				if (el.vml.hasOwnProperty(v)) {
					el.vml[v].shape.style.zIndex = elStyle.zIndex;
				}
			}
			el.runtimeStyle.backgroundColor = '';
			el.runtimeStyle.backgroundImage = '';
			noImg = true;
			if (elStyle.backgroundImage != 'none' || el.isImg) {
				if (!el.isImg) {
					el.vmlBg = elStyle.backgroundImage;
					el.vmlBg = el.vmlBg.substr(5, el.vmlBg.lastIndexOf('")')-5);
				}
				else {
					el.vmlBg = el.src;
				}
				lib = this;
				if (!lib.imgSize[el.vmlBg]) { /* determine size of loaded image */
					img = document.createElement('img');
					lib.imgSize[el.vmlBg] = img;
					img.className = lib.ns + '_sizeFinder';
					img.runtimeStyle.cssText = 'behavior:none; position:absolute; left:-10000px; top:-10000px; border:none; margin:0; padding:0;'; /* make sure to set behavior to none to prevent accidental matching of the helper elements! */
					imgLoaded = function () {
						this.width = this.offsetWidth; /* weird cache-busting requirement! */
						this.height = this.offsetHeight;
						lib.vmlOffsets(el);
					};
					img.attachEvent('onload', imgLoaded);
					img.src = el.vmlBg;
					img.removeAttribute('width');
					img.removeAttribute('height');
					document.body.insertBefore(img, document.body.firstChild);
				}
				el.vml.image.fill.src = el.vmlBg;
				noImg = false;
			}
			el.vml.image.fill.on = !noImg;
			el.vml.image.fill.color = 'none';
			el.vml.color.shape.style.backgroundColor = elStyle.backgroundColor;
			el.runtimeStyle.backgroundImage = 'none';
			el.runtimeStyle.backgroundColor = 'transparent';
		},
		/* IE can't figure out what do when the offsetLeft and the clientLeft add up to 1, and the VML ends up getting fuzzy... so we have to push/enlarge things by 1 pixel and then clip off the excess */
		vmlOffsets: function (el) {
			var thisStyle, size, fudge, makeVisible, bg, bgR, dC, altC, b, c, v;
			thisStyle = el.currentStyle;
			size = {'W':el.clientWidth+1, 'H':el.clientHeight+1, 'w':this.imgSize[el.vmlBg].width, 'h':this.imgSize[el.vmlBg].height, 'L':el.offsetLeft, 'T':el.offsetTop, 'bLW':el.clientLeft, 'bTW':el.clientTop};
			fudge = (size.L + size.bLW == 1) ? 1 : 0;
			/* vml shape, left, top, width, height, origin */
			makeVisible = function (vml, l, t, w, h, o) {
				vml.coordsize = w+','+h;
				vml.coordorigin = o+','+o;
				vml.path = 'm0,0l'+w+',0l'+w+','+h+'l0,'+h+' xe';
				vml.style.width = w + 'px';
				vml.style.height = h + 'px';
				vml.style.left = l + 'px';
				vml.style.top = t + 'px';
			};
			makeVisible(el.vml.color.shape, (size.L + (el.isImg ? 0 : size.bLW)), (size.T + (el.isImg ? 0 : size.bTW)), (size.W-1), (size.H-1), 0);
			makeVisible(el.vml.image.shape, (size.L + size.bLW), (size.T + size.bTW), (size.W), (size.H), 1 );
			bg = {'X':0, 'Y':0};
			if (el.isImg) {
				bg.X = parseInt(thisStyle.paddingLeft, 10) + 1;
				bg.Y = parseInt(thisStyle.paddingTop, 10) + 1;
			}
			else {
				for (b in bg) {
					if (bg.hasOwnProperty(b)) {
						this.figurePercentage(bg, size, b, thisStyle['backgroundPosition'+b]);
					}
				}
			}
			el.vml.image.fill.position = (bg.X/size.W) + ',' + (bg.Y/size.H);
			bgR = thisStyle.backgroundRepeat;
			dC = {'T':1, 'R':size.W+fudge, 'B':size.H, 'L':1+fudge}; /* these are defaults for repeat of any kind */
			altC = { 'X': {'b1': 'L', 'b2': 'R', 'd': 'W'}, 'Y': {'b1': 'T', 'b2': 'B', 'd': 'H'} };
			if (bgR != 'repeat' || el.isImg) {
				c = {'T':(bg.Y), 'R':(bg.X+size.w), 'B':(bg.Y+size.h), 'L':(bg.X)}; /* these are defaults for no-repeat - clips down to the image location */
				if (bgR.search('repeat-') != -1) { /* now let's revert to dC for repeat-x or repeat-y */
					v = bgR.split('repeat-')[1].toUpperCase();
					c[altC[v].b1] = 1;
					c[altC[v].b2] = size[altC[v].d];
				}
				if (c.B > size.H) {
					c.B = size.H;
				}
				el.vml.image.shape.style.clip = 'rect('+c.T+'px '+(c.R+fudge)+'px '+c.B+'px '+(c.L+fudge)+'px)';
			}
			else {
				el.vml.image.shape.style.clip = 'rect('+dC.T+'px '+dC.R+'px '+dC.B+'px '+dC.L+'px)';
			}
		},
		figurePercentage: function (bg, size, axis, position) {
			var horizontal, fraction;
			fraction = true;
			horizontal = (axis == 'X');
			switch(position) {
				case 'left':
				case 'top':
					bg[axis] = 0;
					break;
				case 'center':
					bg[axis] = 0.5;
					break;
				case 'right':
				case 'bottom':
					bg[axis] = 1;
					break;
				default:
					if (position.search('%') != -1) {
						bg[axis] = parseInt(position, 10) / 100;
					}
					else {
						fraction = false;
					}
			}
			bg[axis] = Math.ceil(  fraction ? ( (size[horizontal?'W': 'H'] * bg[axis]) - (size[horizontal?'w': 'h'] * bg[axis]) ) : parseInt(position, 10)  );
			if (bg[axis] % 2 === 0) {
				bg[axis]++;
			}
			return bg[axis];
		},
		fixPng: function (el) {
			el.style.behavior = 'none';
			var lib, els, nodeStr, v, e;
			if (el.nodeName == 'BODY' || el.nodeName == 'TD' || el.nodeName == 'TR') { /* elements not supported yet */
				return;
			}
			el.isImg = false;
			if (el.nodeName == 'IMG') {
				if(el.src.toLowerCase().search(/\.png$/) != -1) {
					el.isImg = true;
					el.style.visibility = 'hidden';
				}
				else {
					return;
				}
			}
			else if (el.currentStyle.backgroundImage.toLowerCase().search('.png') == -1) {
				return;
			}
			lib = DD_belatedPNG;
			el.vml = {color: {}, image: {}};
			els = {shape: {}, fill: {}};
			for (v in el.vml) {
				if (el.vml.hasOwnProperty(v)) {
					for (e in els) {
						if (els.hasOwnProperty(e)) {
							nodeStr = lib.ns + ':' + e;
							el.vml[v][e] = document.createElement(nodeStr);
						}
					}
					el.vml[v].shape.stroked = false;
					el.vml[v].shape.appendChild(el.vml[v].fill);
					el.parentNode.insertBefore(el.vml[v].shape, el);
				}
			}
			el.vml.image.shape.fillcolor = 'none'; /* Don't show blank white shapeangle when waiting for image to load. */
			el.vml.image.fill.type = 'tile'; /* Makes image show up. */
			el.vml.color.fill.on = false; /* Actually going to apply vml element's style.backgroundColor, so hide the whiteness. */
			lib.attachHandlers(el);
			lib.giveLayout(el);
			lib.giveLayout(el.offsetParent);
			el.vmlInitiated = true;
			lib.applyVML(el); /* Render! */
		}
	};
	try {
		document.execCommand("BackgroundImageCache", false, true); /* TredoSoft Multiple IE doesn't like this, so try{} it */
	} catch(r) {}
	DD_belatedPNG.createVmlNameSpace();
	DD_belatedPNG.createVmlStyleSheet();


}
/*
 * Date Format 1.2.3
 * (c) 2007-2009 Steven Levithan <stevenlevithan.com>
 * MIT license
 *
 * Includes enhancements by Scott Trenda <scott.trenda.net>
 * and Kris Kowal <cixar.com/~kris.kowal/>
 *
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */

var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (val, len) {
			val = String(val);
			len = len || 2;
			while (val.length < len) val = "0" + val;
			return val;
		};

	// Regexes and supporting functions are cached through closure
	return function (date, mask, utc) {
		var dF = dateFormat;

		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}

		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");

		mask = String(dF.masks[mask] || mask || dF.masks["default"]);

		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),
			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
		"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	],
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};

if (!jQuery.browser.msie) {


	// Domain Public by Eric Wendelin http://eriwen.com/ (2008)
	//                  Luke Smith http://lucassmith.name/ (2008)
	//                  Loic Dachary <loic@dachary.org> (2008)
	//                  Johan Euphrosine <proppy@aminche.com> (2008)
	//                  Øyvind Sean Kinsey http://kinsey.no/blog (2010)
	//
	// Information and discussions
	// http://jspoker.pokersource.info/skin/test-printstacktrace.html
	// http://eriwen.com/javascript/js-stack-trace/
	// http://eriwen.com/javascript/stacktrace-update/
	// http://pastie.org/253058
	//
	// guessFunctionNameFromLines comes from firebug
	//
	// Software License Agreement (BSD License)
	//
	// Copyright (c) 2007, Parakey Inc.
	// All rights reserved.
	//
	// Redistribution and use of this software in source and binary forms, with or without modification,
	// are permitted provided that the following conditions are met:
	//
	// * Redistributions of source code must retain the above
	//   copyright notice, this list of conditions and the
	//   following disclaimer.
	//
	// * Redistributions in binary form must reproduce the above
	//   copyright notice, this list of conditions and the
	//   following disclaimer in the documentation and/or other
	//   materials provided with the distribution.
	//
	// * Neither the name of Parakey Inc. nor the names of its
	//   contributors may be used to endorse or promote products
	//   derived from this software without specific prior
	//   written permission of Parakey Inc.
	//
	// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR
	// IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
	// FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
	// CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
	// DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
	// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER
	// IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
	// OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

	/**
	 * Main function giving a function stack trace with a forced or passed in Error
	 *
	 * @cfg {Error} e The error to create a stacktrace from (optional)
	 * @cfg {Boolean} guess If we should try to resolve the names of anonymous functions
	 * @return {Array} of Strings with functions, lines, files, and arguments where possible
	 */
	function printStackTrace(options) {
		var ex = (options && options.e) ? options.e : null;
		var guess = options ? !!options.guess : true;

		var p = new printStackTrace.implementation();
		var result = p.run(ex);
		return (guess) ? p.guessFunctions(result) : result;
	}

	printStackTrace.implementation = function() {};

	printStackTrace.implementation.prototype = {
		run: function(ex) {
			ex = ex ||
				(function() {
					try {
						var _err = __undef__ << 1;
					} catch (e) {
						return e;
					}
				})();
			// Use either the stored mode, or resolve it
			var mode = this._mode || this.mode(ex);
			if (mode === 'other') {
				return this.other(arguments.callee);
			} else {
				return this[mode](ex);
			}
		},

		/**
		 * @return {String} mode of operation for the environment in question.
		 */
		mode: function(e) {
			if (e['arguments']) {
				return (this._mode = 'chrome');
			} else if (window.opera && e.stacktrace) {
				return (this._mode = 'opera10');
			} else if (e.stack) {
				return (this._mode = 'firefox');
			} else if (window.opera && !('stacktrace' in e)) { //Opera 9-
				return (this._mode = 'opera');
			}
			return (this._mode = 'other');
		},

		/**
		 * Given a context, function name, and callback function, overwrite it so that it calls
		 * printStackTrace() first with a callback and then runs the rest of the body.
		 *
		 * @param {Object} context of execution (e.g. window)
		 * @param {String} functionName to instrument
		 * @param {Function} function to call with a stack trace on invocation
		 */
		instrumentFunction: function(context, functionName, callback) {
			context = context || window;
			context['_old' + functionName] = context[functionName];
			context[functionName] = function() {
				callback.call(this, printStackTrace());
				return context['_old' + functionName].apply(this, arguments);
			};
			context[functionName]._instrumented = true;
		},

		/**
		 * Given a context and function name of a function that has been
		 * instrumented, revert the function to it's original (non-instrumented)
		 * state.
		 *
		 * @param {Object} context of execution (e.g. window)
		 * @param {String} functionName to de-instrument
		 */
		deinstrumentFunction: function(context, functionName) {
			if (context[functionName].constructor === Function &&
					context[functionName]._instrumented &&
					context['_old' + functionName].constructor === Function) {
				context[functionName] = context['_old' + functionName];
			}
		},

		/**
		 * Given an Error object, return a formatted Array based on Chrome's stack string.
		 *
		 * @param e - Error object to inspect
		 * @return Array<String> of function calls, files and line numbers
		 */
		chrome: function(e) {
			return e.stack.replace(/^[^\(]+?[\n$]/gm, '').replace(/^\s+at\s+/gm, '').replace(/^Object.<anonymous>\s*\(/gm, '{anonymous}()@').split('\n');
		},

		/**
		 * Given an Error object, return a formatted Array based on Firefox's stack string.
		 *
		 * @param e - Error object to inspect
		 * @return Array<String> of function calls, files and line numbers
		 */
		firefox: function(e) {
			return e.stack.replace(/(?:\n@:0)?\s+$/m, '').replace(/^\(/gm, '{anonymous}(').split('\n');
		},

		/**
		 * Given an Error object, return a formatted Array based on Opera 10's stacktrace string.
		 *
		 * @param e - Error object to inspect
		 * @return Array<String> of function calls, files and line numbers
		 */
		opera10: function(e) {
			var stack = e.stacktrace;
			var lines = stack.split('\n'), ANON = '{anonymous}',
				lineRE = /.*line (\d+), column (\d+) in ((<anonymous function\:?\s*(\S+))|([^\(]+)\([^\)]*\))(?: in )?(.*)\s*$/i, i, j, len;
			for (i = 2, j = 0, len = lines.length; i < len - 2; i++) {
				if (lineRE.test(lines[i])) {
					var location = RegExp.$6 + ':' + RegExp.$1 + ':' + RegExp.$2;
					var fnName = RegExp.$3;
					fnName = fnName.replace(/<anonymous function\:?\s?(\S+)?>/g, ANON);
					lines[j++] = fnName + '@' + location;
				}
			}

			lines.splice(j, lines.length - j);
			return lines;
		},

		// Opera 7.x-9.x only!
		opera: function(e) {
			var lines = e.message.split('\n'), ANON = '{anonymous}',
				lineRE = /Line\s+(\d+).*script\s+(http\S+)(?:.*in\s+function\s+(\S+))?/i,
				i, j, len;

			for (i = 4, j = 0, len = lines.length; i < len; i += 2) {
				//TODO: RegExp.exec() would probably be cleaner here
				if (lineRE.test(lines[i])) {
					lines[j++] = (RegExp.$3 ? RegExp.$3 + '()@' + RegExp.$2 + RegExp.$1 : ANON + '()@' + RegExp.$2 + ':' + RegExp.$1) + ' -- ' + lines[i + 1].replace(/^\s+/, '');
				}
			}

			lines.splice(j, lines.length - j);
			return lines;
		},

		// Safari, IE, and others
		other: function(curr) {
			var ANON = '{anonymous}', fnRE = /function\s*([\w\-$]+)?\s*\(/i,
				stack = [], j = 0, fn, args;

			var maxStackSize = 10;
			while (curr && stack.length < maxStackSize) {
				fn = fnRE.test(curr.toString()) ? RegExp.$1 || ANON : ANON;
				args = Array.prototype.slice.call(curr['arguments']);
				stack[j++] = fn + '(' + this.stringifyArguments(args) + ')';
				curr = curr.caller;
			}
			return stack;
		},

		/**
		 * Given arguments array as a String, subsituting type names for non-string types.
		 *
		 * @param {Arguments} object
		 * @return {Array} of Strings with stringified arguments
		 */
		stringifyArguments: function(args) {
			for (var i = 0; i < args.length; ++i) {
				var arg = args[i];
				if (arg === undefined) {
					args[i] = 'undefined';
				} else if (arg === null) {
					args[i] = 'null';
				} else if (arg.constructor) {
					if (arg.constructor === Array) {
						if (arg.length < 3) {
							args[i] = '[' + this.stringifyArguments(arg) + ']';
						} else {
							args[i] = '[' + this.stringifyArguments(Array.prototype.slice.call(arg, 0, 1)) + '...' + this.stringifyArguments(Array.prototype.slice.call(arg, -1)) + ']';
						}
					} else if (arg.constructor === Object) {
						args[i] = '#object';
					} else if (arg.constructor === Function) {
						args[i] = '#function';
					} else if (arg.constructor === String) {
						args[i] = '"' + arg + '"';
					}
				}
			}
			return args.join(',');
		},

		sourceCache: {},

		/**
		 * @return the text from a given URL.
		 */
		ajax: function(url) {
			var req = this.createXMLHTTPObject();
			if (!req) {
				return;
			}
			req.open('GET', url, false);
			req.setRequestHeader('User-Agent', 'XMLHTTP/1.0');
			req.send('');
			return req.responseText;
		},

		/**
		 * Try XHR methods in order and store XHR factory.
		 *
		 * @return <Function> XHR function or equivalent
		 */
		createXMLHTTPObject: function() {
			var xmlhttp, XMLHttpFactories = [
				function() {
					return new XMLHttpRequest();
				}, function() {
					return new ActiveXObject('Msxml2.XMLHTTP');
				}, function() {
					return new ActiveXObject('Msxml3.XMLHTTP');
				}, function() {
					return new ActiveXObject('Microsoft.XMLHTTP');
				}
			];
			for (var i = 0; i < XMLHttpFactories.length; i++) {
				try {
					xmlhttp = XMLHttpFactories[i]();
					// Use memoization to cache the factory
					this.createXMLHTTPObject = XMLHttpFactories[i];
					return xmlhttp;
				} catch (e) {}
			}
		},

		/**
		 * Given a URL, check if it is in the same domain (so we can get the source
		 * via Ajax).
		 *
		 * @param url <String> source url
		 * @return False if we need a cross-domain request
		 */
		isSameDomain: function(url) {
			return url.indexOf(location.hostname) !== -1;
		},

		/**
		 * Get source code from given URL if in the same domain.
		 *
		 * @param url <String> JS source URL
		 * @return <String> Source code
		 */
		getSource: function(url) {
			if (!(url in this.sourceCache)) {
				this.sourceCache[url] = this.ajax(url).split('\n');
			}
			return this.sourceCache[url];
		},

		guessFunctions: function(stack) {
			for (var i = 0; i < stack.length; ++i) {
				var reStack = /\{anonymous\}\(.*\)@(\w+:\/\/([\-\w\.]+)+(:\d+)?[^:]+):(\d+):?(\d+)?/;
				var frame = stack[i], m = reStack.exec(frame);
				if (m) {
					var file = m[1], lineno = m[4]; //m[7] is character position in Chrome
					if (file && this.isSameDomain(file) && lineno) {
						var functionName = this.guessFunctionName(file, lineno);
						stack[i] = frame.replace('{anonymous}', functionName);
					}
				}
			}
			return stack;
		},

		guessFunctionName: function(url, lineNo) {
			try {
				return this.guessFunctionNameFromLines(lineNo, this.getSource(url));
			} catch (e) {
				return 'getSource failed with url: ' + url + ', exception: ' + e.toString();
			}
		},

		guessFunctionNameFromLines: function(lineNo, source) {
			var reFunctionArgNames = /function ([^(]*)\(([^)]*)\)/;
			var reGuessFunction = /['"]?([0-9A-Za-z_]+)['"]?\s*[:=]\s*(function|eval|new Function)/;
			// Walk backwards from the first line in the function until we find the line which
			// matches the pattern above, which is the function definition
			var line = "", maxLines = 10;
			for (var i = 0; i < maxLines; ++i) {
				line = source[lineNo - i] + line;
				if (line !== undefined) {
					var m = reGuessFunction.exec(line);
					if (m && m[1]) {
						return m[1];
					} else {
						m = reFunctionArgNames.exec(line);
						if (m && m[1]) {
							return m[1];
						}
					}
				}
			}
			return '(?)';
		}
	};


}


(function($){
    $.fn.alignBottom = function() 
    {

        var defaults = {
            outerHight: 0,
            elementHeight: 0
        };

        var options = $.extend(defaults, options);
        var bpHeight = 0; // Border + padding

        return this.each(function() 
        {
            options.outerHight = $(this).parent().outerHeight();
            bpHeight = options.outerHight - $(this).parent().height();
            options.elementHeight = $(this).outerHeight(true) + bpHeight;


            $(this).css({'position':'relative','top':options.outerHight-(options.elementHeight)+'px'});
        });
    };
})(jQuery); /*!
 * fancyBox - jQuery Plugin
 * version: 2.0.4 (12/12/2011)
 * @requires jQuery v1.6 or later
 *
 * Examples at http://fancyapps.com/fancybox/
 * License: www.fancyapps.com/fancybox/#license
 *
 * Copyright 2011 Janis Skarnelis - janis@fancyapps.com
 *
 */
(function (window, document, $) {
	var W = $(window),
		D = $(document),
		F = $.fancybox = function () {
			F.open.apply( this, arguments );
		},
		didResize = false,
		resizeTimer = null;

	$.extend(F, {
		// The current version of fancyBox
		version: '2.0.4',

		defaults: {
			padding: 15,
			margin: 20,

			width: 800,
			height: 600,
			minWidth: 200,
			minHeight: 200,
			maxWidth: 9999,
			maxHeight: 9999,

			autoSize: true,
			fitToView: true,
			aspectRatio: false,
			topRatio: 0.5,

			fixed: !$.browser.msie || $.browser.version > 6 || !document.documentElement.hasOwnProperty('ontouchstart'),
			scrolling: 'auto', // 'auto', 'yes' or 'no'
			wrapCSS: 'fancybox-default',

			arrows: true,
			closeBtn: true,
			closeClick: false,
			nextClick : false,
			mouseWheel: true,
			autoPlay: false,
			playSpeed: 3000,

			modal: false,
			loop: true,
			ajax: {},
			keys: {
				next: [13, 32, 34, 39, 40], // enter, space, page down, right arrow, down arrow
				prev: [8, 33, 37, 38], // backspace, page up, left arrow, up arrow
				close: [27] // escape key
			},

			// Override some properties
			index: 0,
			type: null,
			href: null,
			content: null,
			title: null,

			// HTML templates
			tpl: {
				wrap: '<div class="fancybox-wrap"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div>',
				image: '<img class="fancybox-image" src="{href}" alt="" />',
				iframe: '<iframe class="fancybox-iframe" name="fancybox-frame{rnd}" frameborder="0" hspace="0" ' + ($.browser.msie ? 'allowtransparency="true""' : '') + ' scrolling="{scrolling}" src="{href}"></iframe>',
				swf: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="100%" height="100%"><param name="wmode" value="transparent" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{href}" /><embed src="{href}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="100%" height="100%" wmode="transparent"></embed></object>',
				error: '<p class="fancybox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
				closeBtn: '<div title="Close" class="fancybox-item fancybox-close"></div>',
				next: '<a title="Next" class="fancybox-item fancybox-next"><span></span></a>',
				prev: '<a title="Previous" class="fancybox-item fancybox-prev"><span></span></a>'
			},

			// Properties for each animation type
			// Opening fancyBox
			openEffect: 'fade', // 'elastic', 'fade' or 'none'
			openSpeed: 250,
			openEasing: 'swing',
			openOpacity: true,
			openMethod: 'zoomIn',

			// Closing fancyBox
			closeEffect: 'fade', // 'elastic', 'fade' or 'none'
			closeSpeed: 250,
			closeEasing: 'swing',
			closeOpacity: true,
			closeMethod: 'zoomOut',

			// Changing next gallery item
			nextEffect: 'elastic', // 'elastic', 'fade' or 'none'
			nextSpeed: 300,
			nextEasing: 'swing',
			nextMethod: 'changeIn',

			// Changing previous gallery item
			prevEffect: 'elastic', // 'elastic', 'fade' or 'none'
			prevSpeed: 300,
			prevEasing: 'swing',
			prevMethod: 'changeOut',

			// Enabled helpers
			helpers: {
				overlay: {
					speedIn: 0,
					speedOut: 300,
					opacity: 0.8,
					css: {
						cursor: 'pointer'
					},
					closeClick: true
				},
				title: {
					type: 'float' // 'float', 'inside', 'outside' or 'over'
				}
			},

			// Callbacks
			onCancel: $.noop, // If canceling
			beforeLoad: $.noop, // Before loading
			afterLoad: $.noop, // After loading
			beforeShow: $.noop, // Before changing in current item
			afterShow: $.noop, // After opening
			beforeClose: $.noop, // Before closing
			afterClose: $.noop // After closing
		},

		//Current state
		group: {}, // Selected group
		opts: {}, // Group options
		coming: null, // Element being loaded
		current: null, // Currently loaded element
		isOpen: false, // Is currently open
		isOpened: false, // Have been fully opened at least once
		wrap: null,
		outer: null,
		inner: null,

		player: {
			timer: null,
			isActive: false
		},

		// Loaders
		ajaxLoad: null,
		imgPreload: null,

		// Some collections
		transitions: {},
		helpers: {},

		/*
		 *	Static methods
		 */

		open: function (group, opts) {
			// Normalize group
			if (!$.isArray(group)) {
				group = [group];
			}

			if (!group.length) {
				return;
			}

			//Kill existing instances
			F.close(true);

			//Extend the defaults
			F.opts = $.extend(true, {}, F.defaults, opts);
			F.group = group;

			F._start(F.opts.index || 0);
		},

		cancel: function () {
			if (F.coming && false === F.trigger('onCancel')) {
				return;
			}

			F.coming = null;

			F.hideLoading();

			if (F.ajaxLoad) {
				F.ajaxLoad.abort();
			}

			F.ajaxLoad = null;

			if (F.imgPreload) {
				F.imgPreload.onload = F.imgPreload.onabort = F.imgPreload.onerror = null;
			}
		},

		close: function (a) {
			F.cancel();

			if (!F.current || false === F.trigger('beforeClose')) {
				return;
			}

			F.unbindEvents();

			//If forced or is still opening then remove immediately
			if (!F.isOpen || (a && a[0] === true)) {
				$(".fancybox-wrap").stop().trigger('onReset').remove();

				F._afterZoomOut();

			} else {
				F.isOpen = F.isOpened = false;

				$(".fancybox-item").remove();

				F.wrap.stop(true).removeClass('fancybox-opened');
				F.inner.css('overflow', 'hidden');

				F.transitions[F.current.closeMethod]();
			}
		},

		// Start/stop slideshow
		play: function (a) {
			var clear = function () {
					clearTimeout(F.player.timer);
				},
				set = function () {
					clear();

					if (F.current && F.player.isActive) {
						F.player.timer = setTimeout(F.next, F.current.playSpeed);
					}
				},
				stop = function () {
					clear();

					$('body').unbind('.player');

					F.player.isActive = false;

					F.trigger('onPlayEnd');
				},
				start = function () {
					if (F.current && (F.current.loop || F.current.index < F.group.length - 1)) {
						F.player.isActive = true;

						$('body').bind({
							'afterShow.player onUpdate.player': set,
							'onCancel.player beforeClose.player': stop,
							'beforeLoad.player': clear
						});

						set();

						F.trigger('onPlayStart');
					}
				};

			if (F.player.isActive || (a && a[0] === false)) {
				stop();
			} else {
				start();
			}
		},

		next: function () {
			if (F.current) {
				F.jumpto(F.current.index + 1);
			}
		},

		prev: function () {
			if (F.current) {
				F.jumpto(F.current.index - 1);
			}
		},

		jumpto: function (index) {
			if (!F.current) {
				return;
			}

			index = parseInt(index, 10);

			if (F.group.length > 1 && F.current.loop) {
				if (index >= F.group.length) {
					index = 0;

				} else if (index < 0) {
					index = F.group.length - 1;
				}
			}

			if (typeof F.group[index] !== 'undefined') {
				F.cancel();

				F._start(index);
			}
		},

		reposition: function (a) {
			if (F.isOpen) {
				F.wrap.css(F._getPosition(a));
			}
		},

		update: function () {
			if (F.isOpen) {
				// It's a very bad idea to attach handlers to the window scroll event, run this code after a delay
				if (!didResize) {
					resizeTimer = setInterval(function () {
						if (didResize) {
							didResize = false;

							clearTimeout(resizeTimer);

							if (F.current) {
								if (F.current.autoSize) {
									F.inner.height('auto');
									F.current.height = F.inner.height();
								}

								F._setDimension();

								if (F.current.canGrow) {
									F.inner.height('auto');
								}

								F.reposition();

								F.trigger('onUpdate');
							}
						}
					}, 100);
				}

				didResize = true;
			}
		},

		toggle: function () {
			if (F.isOpen) {
				F.current.fitToView = !F.current.fitToView;

				F.update();
			}
		},

		hideLoading: function () {
			$("#fancybox-loading").remove();
		},

		showLoading: function () {
			F.hideLoading();

			$('<div id="fancybox-loading"></div>').click(F.cancel).appendTo('body');
		},

		getViewport: function () {
			return {
				x: W.scrollLeft(),
				y: W.scrollTop(),
				w: W.width(),
				h: W.height()
			};
		},

		// Unbind the keyboard / clicking actions
		unbindEvents: function () {
			if (F.wrap) {
				F.wrap.unbind('.fb');	
			}

			D.unbind('.fb');
			W.unbind('.fb');
		},

		bindEvents: function () {
			var current = F.current,
				keys = current.keys;

			if (!current) {
				return;
			}

			W.bind('resize.fb, orientationchange.fb', F.update);

			if (keys) {
				D.bind('keydown.fb', function (e) {
					var code;

					// Ignore key combinations and key events within form elements
					if (!e.ctrlKey && !e.altKey && !e.shiftKey && !e.metaKey && $.inArray(e.target.tagName.toLowerCase(), ['input', 'textarea', 'select', 'button']) < 0) {
						code = e.keyCode;

						if ($.inArray(code, keys.close) > -1) {
							F.close();
							e.preventDefault();

						} else if ($.inArray(code, keys.next) > -1) {
							F.next();
							e.preventDefault();

						} else if ($.inArray(code, keys.prev) > -1) {
							F.prev();
							e.preventDefault();
						}
					}
				});
			}

			if ($.fn.mousewheel && current.mouseWheel && F.group.length > 1) {
				F.wrap.bind('mousewheel.fb', function (e, delta) {
					var target = $(e.target).get(0);

					if (target.clientHeight === 0 || target.scrollHeight === target.clientHeight) {
						e.preventDefault();

						F[delta > 0 ? 'prev' : 'next']();
					}
				});
			}
		},

		trigger: function (event) {
			var ret, obj = F[ $.inArray(event, ['onCancel', 'beforeLoad', 'afterLoad']) > -1 ? 'coming' : 'current' ];

			if (!obj) {
				return;
			}

			if ($.isFunction( obj[event] )) {
				ret = obj[event].apply(obj, Array.prototype.slice.call(arguments, 1));
			}

			if (ret === false) {
				return false;
			}

			if (obj.helpers) {
				$.each(obj.helpers, function (helper, opts) {
					if (opts && typeof F.helpers[helper] !== 'undefined' && $.isFunction(F.helpers[helper][event])) {
						F.helpers[helper][event](opts, obj);
					}
				});
			}

			$.event.trigger(event + '.fb');
		},

		isImage: function (str) {
			return str && str.match(/\.(jpg|gif|png|bmp|jpeg)(.*)?$/i);
		},

		isSWF: function (str) {
			return str && str.match(/\.(swf)(.*)?$/i);
		},

		_start: function (index) {
			var coming = {},
				element = F.group[index] || null,
				isDom,
				href,
				type,
				rez;

			if (typeof element === 'object' && (element.nodeType || element instanceof $)) {
				isDom = true;

				if ($.metadata) {
					coming = $(element).metadata();
				}
			}

			coming = $.extend(true, {}, F.opts, {index : index, element : element}, ($.isPlainObject(element) ? element : coming));

			// Re-check overridable options
			$.each(['href', 'title', 'content', 'type'], function(i,v) {
				coming[v] = F.opts[ v ] || (isDom && $(element).attr( v )) || coming[ v ] || null;
			});

			// Convert margin property to array - top, right, bottom, left
			if (typeof coming.margin === 'number') {
				coming.margin = [coming.margin, coming.margin, coming.margin, coming.margin];
			}

			// 'modal' propery is just a shortcut
			if (coming.modal) {
				$.extend(true, coming, {
					closeBtn : false,
					closeClick: false,
					nextClick : false,
					arrows : false,
					mouseWheel : false,
					keys : null,
					helpers: {
						overlay : {
							css: {
								cursor : 'auto'
							},
							closeClick : false
						}
					}
				});
			}

			//Give a chance for callback or helpers to update coming item (type, title, etc)
			F.coming = coming;

			if (false === F.trigger('beforeLoad')) {
				F.coming = null;
				return;
			}

			type = coming.type;
			href = coming.href;

			///Check if content type is set, if not, try to get
			if (!type) {
				if (isDom) {
					rez = $(element).data('fancybox-type');

					if (!rez && element.className) {
						rez = element.className.match(/fancybox\.(\w+)/);
						type = rez ? rez[1] : null;
					}
				}

				if (!type && href) {
					if (F.isImage(href)) {
						type = 'image';

					} else if (F.isSWF(href)) {
						type = 'swf';

					} else if (href.match(/^#/)) {
						type = 'inline';
					}
				}

				// ...if not - display element itself
				if (!type) {
					type = isDom ? 'inline' : 'html';
				}

				coming.type = type;
			}

			// Check before try to load; 'inline' and 'html' types need content, others - href
			if (type === 'inline' || type === 'html') {
				coming.content = coming.content || (type === 'inline' && href ? $(href) : element);

				if (!coming.content.length) {
					type = null;
				}

			} else {
				coming.href = href || element;

				if (!coming.href) {
					type = null;
				}
			}

			/*
				Add reference to the group, so it`s possible to access from callbacks, example:

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}

			*/

			coming.group = F.group;

			if (type === 'image') {
				F._loadImage();

			} else if (type === 'ajax') {
				F._loadAjax();

			} else if (type) {
				F._afterLoad();

			} else {
				F._error( 'type' );
			}
		},

		_error: function ( type ) {
			$.extend(F.coming, {
				type : 'html',
				autoSize : true,
				minHeight : '0',
				hasError : type,
				content : F.coming.tpl.error
			});

			F._afterLoad();
		},

		_loadImage: function () {
			// Reset preload image so it is later possible to check "complete" property
			F.imgPreload = new Image();

			F.imgPreload.onload = function () {
				this.onload = this.onerror = null;

				F.coming.width = this.width;
				F.coming.height = this.height;

				F._afterLoad();
			};

			F.imgPreload.onerror = function () {
				this.onload = this.onerror = null;

				F._error( 'image' );
			};

			F.imgPreload.src = F.coming.href;

			if (!F.imgPreload.complete) {
				F.showLoading();
			}
		},

		_loadAjax: function () {
			F.showLoading();

			F.ajaxLoad = $.ajax($.extend({}, F.coming.ajax, {
				url: F.coming.href,
				error: function (jqXHR, textStatus) {
					if (textStatus !== 'abort') {
						F._error( 'ajax', jqXHR );

					} else {
						F.hideLoading();
					}
				},
				success: function (data, textStatus) {
					if (textStatus === 'success') {
						F.coming.content = data;

						F._afterLoad();
					}
				}
			}));
		},

		_preload : function() {
			var group = F.group,
				index = F.current.index,
				load = function(href) {
					if (href && F.isImage(href)) {
						new Image().src = href;
					}
				};

			if (group.length > 1) {
				load( $( group[ index + 1 ] || group[ 0 ] ).attr('href') );
				load( $( group[ index - 1 ] || group[ group.length - 1 ] ).attr('href') );
			}
		},

		_afterLoad: function () {
			F.hideLoading();

			if (!F.coming || false === F.trigger('afterLoad', F.current)) {
				F.coming = false;

				return;
			}

			if (F.isOpened) {
				$(".fancybox-item").remove();

				F.wrap.stop(true).removeClass('fancybox-opened');
				F.inner.css('overflow', 'hidden');

				F.transitions[F.current.prevMethod]();

			} else {
				$(".fancybox-wrap").stop().trigger('onReset').remove();

				F.trigger('afterClose');
			}

			F.unbindEvents();

			F.isOpen = false;
			F.current = F.coming;
			F.coming = false;

			//Build the neccessary markup
			F.wrap = $(F.current.tpl.wrap).addClass('fancybox-tmp ' + F.current.wrapCSS).appendTo('body');
			F.outer = $('.fancybox-outer', F.wrap).css('padding', F.current.padding + 'px');
			F.inner = $('.fancybox-inner', F.wrap);

			F._setContent();

			//Give a chance for helpers or callbacks to update elements
			F.trigger('beforeShow');

			//Set initial dimensions and hide
			F._setDimension();

			F.wrap.hide().removeClass('fancybox-tmp');

			F.bindEvents();
			F._preload();

			F.transitions[ F.isOpened ? F.current.nextMethod : F.current.openMethod ]();
		},

		_setContent: function () {
			var content, loadingBay, current = F.current,
				type = current.type;

			switch (type) {
				case 'inline':
				case 'ajax':
				case 'html':
					content = current.content;

					if (type === 'inline' && content instanceof $) {
						content = content.show().detach();

						if (content.parent().hasClass('fancybox-inner')) {
							content.parents('.fancybox-wrap').trigger('onReset').remove();
						}

						$(F.wrap).bind('onReset', function () {
							content.appendTo('body').hide();
						});
					}

					if (current.autoSize) {
						loadingBay = $('<div class="fancybox-tmp"></div>').appendTo($("body")).append(content);

						current.width = loadingBay.outerWidth();
						current.height = loadingBay.outerHeight(true);

						content = loadingBay.contents().detach();

						loadingBay.remove();
					}

				break;

				case 'image':
					content = current.tpl.image.replace('{href}', current.href);

					current.aspectRatio = true;
				break;

				case 'swf':
					content = current.tpl.swf.replace(/\{width\}/g, current.width).replace(/\{height\}/g, current.height).replace(/\{href\}/g, current.href);
				break;

				case 'iframe':
					content = current.tpl.iframe.replace('{href}', current.href).replace('{scrolling}', current.scrolling).replace('{rnd}', new Date().getTime());
				break;
			}

			if ($.inArray(type, ['image', 'swf', 'iframe']) > -1) {
				current.autoSize = false;
				current.scrolling = false;
			}

			F.inner.append(content);
		},

		_setDimension: function () {
			var wrap = F.wrap,
				outer = F.outer,
				inner = F.inner,
				current = F.current,
				viewport = F.getViewport(),
				margin = current.margin,
				padding2 = current.padding * 2,
				width = current.width + padding2,
				height = current.height + padding2,
				ratio = current.width / current.height,

				maxWidth = current.maxWidth,
				maxHeight = current.maxHeight,
				minWidth = current.minWidth,
				minHeight = current.minHeight,
				height_,
				space;

			viewport.w -= (margin[1] + margin[3]);
			viewport.h -= (margin[0] + margin[2]);

			if (width.toString().indexOf('%') > -1) {
				width = ((viewport.w * parseFloat(width)) / 100);
			}

			if (height.toString().indexOf('%') > -1) {
				height = ((viewport.h * parseFloat(height)) / 100);
			}

			if (current.fitToView) {
				maxWidth = Math.min(viewport.w, maxWidth);
				maxHeight = Math.min(viewport.h, maxHeight);
			}

			minWidth = Math.min(width, minWidth);
			minHeight = Math.min(width, minHeight);

			maxWidth = Math.max(minWidth, maxWidth);
			maxHeight = Math.max(minHeight, maxHeight);

			if (current.aspectRatio) {
				if (width > maxWidth) {
					width = maxWidth;
					height = ((width - padding2) / ratio) + padding2;
				}

				if (height > maxHeight) {
					height = maxHeight;
					width = ((height - padding2) * ratio) + padding2;
				}

				if (width < minWidth) {
					width = minWidth;
					height = ((width - padding2) / ratio) + padding2;
				}

				if (height < minHeight) {
					height = minHeight;
					width = ((height - padding2) * ratio) + padding2;
				}

			} else {
				width = Math.max(minWidth, Math.min(width, maxWidth));
				height = Math.max(minHeight, Math.min(height, maxHeight));
			}

			width = Math.round(width);
			height = Math.round(height);

			//Reset dimensions
			$(wrap.add(outer).add(inner)).width('auto').height('auto');

			inner.width(width - padding2).height(height - padding2);
			wrap.width(width);

			height_ = wrap.height(); // Real wrap height

			//Fit wrapper inside
			if (width > maxWidth || height_ > maxHeight) {
				while ((width > maxWidth || height_ > maxHeight) && width > minWidth && height_ > minHeight) {
					height = height - 10;

					if (current.aspectRatio) {
						width = Math.round(((height - padding2) * ratio) + padding2);

						if (width < minWidth) {
							width = minWidth;
							height = ((width - padding2) / ratio) + padding2;
						}

					} else {
						width = width - 10;
					}

					inner.width(width - padding2).height(height - padding2);
					wrap.width(width);

					height_ = wrap.height();
				}
			}

			current.dim = {
				width: width,
				height: height_
			};

			current.canGrow = current.autoSize && height > minHeight && height < maxHeight;
			current.canShrink = false;
			current.canExpand = false;

			if ((width - padding2) < current.width || (height - padding2) < current.height) {
				current.canExpand = true;

			} else if ((width > viewport.w || height_ > viewport.h) && width > minWidth && height > minHeight) {
				current.canShrink = true;
			}

			space = height_ - padding2;

			F.innerSpace = space - inner.height();
			F.outerSpace = space - outer.height();
		},

		_getPosition: function (a) {
			var current = F.current,
				viewport = F.getViewport(),
				margin = current.margin,
				width = F.wrap.width() + margin[1] + margin[3],
				height = F.wrap.height() + margin[0] + margin[2],
				rez = {
					position: 'absolute',
					top: margin[0] + viewport.y,
					left: margin[3] + viewport.x
				};

			if (current.fixed && (!a || a[0] === false) && height <= viewport.h && width <= viewport.w) {
				rez = {
					position: 'fixed',
					top: margin[0],
					left: margin[3]
				};
			}

			rez.top = Math.ceil(Math.max(rez.top, rez.top + ((viewport.h - height) * current.topRatio))) + 'px';
			rez.left = Math.ceil(Math.max(rez.left, rez.left + ((viewport.w - width) * 0.5))) + 'px';

			return rez;
		},

		_afterZoomIn: function () {
			var current = F.current;

			F.isOpen = F.isOpened = true;

			F.wrap.addClass('fancybox-opened').css('overflow', 'visible');

			F.update();

			F.inner.css('overflow', current.scrolling === 'auto' ? 'auto' : (current.scrolling === 'yes' ? 'scroll' : 'hidden'));

			//Assign a click event
			if (current.closeClick || current.nextClick) {
				F.inner.css('cursor', 'pointer').bind('click.fb', current.nextClick ? F.next : F.close);
			}

			//Create a close button
			if (current.closeBtn) {
				$(current.tpl.closeBtn).appendTo(F.wrap).bind('click.fb', F.close);
			}

			//Create navigation arrows
			if (current.arrows && F.group.length > 1) {
				if (current.loop || current.index > 0) {
					$(current.tpl.prev).appendTo(F.wrap).bind('click.fb', F.prev);
				}

				if (current.loop || current.index < F.group.length - 1) {
					$(current.tpl.next).appendTo(F.wrap).bind('click.fb', F.next);
				}
			}

			F.trigger('afterShow');

			if (F.opts.autoPlay && !F.player.isActive) {
				F.opts.autoPlay = false;

				F.play();
			}
		},

		_afterZoomOut: function () {
			F.trigger('afterClose');

			F.wrap.trigger('onReset').remove();

			$.extend(F, {
				group: {},
				opts: {},
				current: null,
				isOpened: false,
				isOpen: false,
				wrap: null,
				outer: null,
				inner: null
			});
		}
	});

	/*
	 *	Default transitions
	 */

	F.transitions = {
		getOrigPosition: function () {
			var element = F.current.element,
				pos = {},
				width = 50,
				height = 50,
				image, viewport;

			if (element && element.nodeName && $(element).is(':visible')) {
				image = $(element).find('img:first');

				if (image.length) {
					pos = image.offset();
					width = image.outerWidth();
					height = image.outerHeight();

				} else {
					pos = $(element).offset();
				}

			} else {
				viewport = F.getViewport();
				pos.top = viewport.y + (viewport.h - height) * 0.5;
				pos.left = viewport.x + (viewport.w - width) * 0.5;
			}

			pos = {
				top: Math.ceil(pos.top) + 'px',
				left: Math.ceil(pos.left) + 'px',
				width: Math.ceil(width) + 'px',
				height: Math.ceil(height) + 'px'
			};

			return pos;
		},

		step: function (now, fx) {
			var ratio, innerValue, outerValue;

			if (fx.prop === 'width' || fx.prop === 'height') {
				innerValue = outerValue = Math.ceil(now - (F.current.padding * 2));

				if (fx.prop === 'height') {
					ratio = (now - fx.start) / (fx.end - fx.start);

					if (fx.start > fx.end) {
						ratio = 1 - ratio;
					}

					innerValue -= F.innerSpace * ratio;
					outerValue -= F.outerSpace * ratio;
				}

				F.inner[fx.prop](innerValue);
				F.outer[fx.prop](outerValue);
			}
		},

		zoomIn: function () {
			var wrap = F.wrap,
				current = F.current,
				startPos,
				endPos,
				dim = current.dim;

			if (current.openEffect === 'elastic') {
				endPos = $.extend({}, dim, F._getPosition(true));

				//Remove "position" property
				delete endPos.position;

				startPos = this.getOrigPosition();

				if (current.openOpacity) {
					startPos.opacity = 0;
					endPos.opacity = 1;
				}

				wrap.css(startPos).show().animate(endPos, {
					duration: current.openSpeed,
					easing: current.openEasing,
					step: this.step,
					complete: F._afterZoomIn
				});

			} else {
				wrap.css($.extend({}, dim, F._getPosition()));

				if (current.openEffect === 'fade') {
					wrap.fadeIn(current.openSpeed, F._afterZoomIn);

				} else {
					wrap.show();
					F._afterZoomIn();
				}
			}
		},

		zoomOut: function () {
			var wrap = F.wrap,
				current = F.current,
				endPos;

			if (current.closeEffect === 'elastic') {
				if (wrap.css('position') === 'fixed') {
					wrap.css(F._getPosition(true));
				}

				endPos = this.getOrigPosition();

				if (current.closeOpacity) {
					endPos.opacity = 0;
				}

				wrap.animate(endPos, {
					duration: current.closeSpeed,
					easing: current.closeEasing,
					step: this.step,
					complete: F._afterZoomOut
				});

			} else {
				wrap.fadeOut(current.closeEffect === 'fade' ? current.closeSpeed : 0, F._afterZoomOut);
			}
		},

		changeIn: function () {
			var wrap = F.wrap,
				current = F.current,
				startPos;

			if (current.nextEffect === 'elastic') {
				startPos = F._getPosition(true);
				startPos.opacity = 0;
				startPos.top = (parseInt(startPos.top, 10) - 200) + 'px';

				wrap.css(startPos).show().animate({
					opacity: 1,
					top: '+=200px'
				}, {
					duration: current.nextSpeed,
					complete: F._afterZoomIn
				});

			} else {
				wrap.css(F._getPosition());

				if (current.nextEffect === 'fade') {
					wrap.hide().fadeIn(current.nextSpeed, F._afterZoomIn);

				} else {
					wrap.show();
					F._afterZoomIn();
				}
			}
		},

		changeOut: function () {
			var wrap = F.wrap,
				current = F.current,
				cleanUp = function () {
					$(this).trigger('onReset').remove();
				};

			wrap.removeClass('fancybox-opened');

			if (current.prevEffect === 'elastic') {
				wrap.animate({
					'opacity': 0,
					top: '+=200px'
				}, {
					duration: current.prevSpeed,
					complete: cleanUp
				});

			} else {
				wrap.fadeOut(current.prevEffect === 'fade' ? current.prevSpeed : 0, cleanUp);
			}
		}
	};

	/*
	 *	Overlay helper
	 */

	F.helpers.overlay = {
		overlay: null,

		update: function () {
			var width, scrollWidth, offsetWidth;

			//Reset width/height so it will not mess
			this.overlay.width(0).height(0);

			if ($.browser.msie) {
				scrollWidth = Math.max(document.documentElement.scrollWidth, document.body.scrollWidth);
				offsetWidth = Math.max(document.documentElement.offsetWidth, document.body.offsetWidth);

				width = scrollWidth < offsetWidth ? W.width() : scrollWidth;

			} else {
				width = D.width();
			}

			this.overlay.width(width).height(D.height());
		},

		beforeShow: function (opts) {
			if (this.overlay) {
				return;
			}

			this.overlay = $('<div id="fancybox-overlay"></div>').css(opts.css || {
				background: 'black'
			}).appendTo('body');

			this.update();

			if (opts.closeClick) {
				this.overlay.bind('click.fb', F.close);
			}

			W.bind("resize.fb", $.proxy(this.update, this));

			this.overlay.fadeTo(opts.speedIn || "fast", opts.opacity || 1);
		},

		onUpdate: function () {
			//Update as content may change document dimensions
			this.update();
		},

		afterClose: function (opts) {
			if (this.overlay) {
				this.overlay.fadeOut(opts.speedOut || "fast", function () {
					$(this).remove();
				});
			}

			this.overlay = null;
		}
	};

	/*
	 *	Title helper
	 */

	F.helpers.title = {
		beforeShow: function (opts) {
			var title, text = F.current.title;

			if (text) {
				title = $('<div class="fancybox-title fancybox-title-' + opts.type + '-wrap">' + text + '</div>').appendTo('body');

				if (opts.type === 'float') {
					//This helps for some browsers
					title.width(title.width());

					title.wrapInner('<span class="child"></span>');

					//Increase bottom margin so this title will also fit into viewport
					F.current.margin[2] += Math.abs(parseInt(title.css('margin-bottom'), 10));
				}

				title.appendTo(opts.type === 'over' ? F.inner : (opts.type === 'outside' ? F.wrap : F.outer));
			}
		}
	};

	// jQuery plugin initialization
	$.fn.fancybox = function (options) {
		var opts = options || {},
			selector = this.selector || '';

		function run(e) {
			var group = [], relType, relVal, rel = this.rel;

			if (!(e.ctrlKey || e.altKey || e.shiftKey || e.metaKey)) {
				e.preventDefault();

				relVal = $(this).data('fancybox-group');

				// Check if element has 'data-fancybox-group' attribute, if not - use 'rel'
				if (typeof relVal !== 'undefined') {
					relType = relVal ? 'data-fancybox-group' : false;

				} else if (rel && rel !== '' && rel !== 'nofollow') {
					relVal = rel;
					relType = 'rel';
				}

				if (relType) {
					group = selector.length ? $(selector).filter('[' + relType + '="' + relVal + '"]') : $('[' + relType + '="' + relVal + '"]');
				}

				if (group.length) {
					opts.index = group.index(this);

					F.open(group.get(), opts);

				} else {
					F.open(this, opts);
				}
			}
		}

		if (selector) {
			D.undelegate(selector, 'click.fb-start').delegate(selector, 'click.fb-start', run);

		} else {
			$(this).unbind('click.fb-start').bind('click.fb-start', run);
		}

		return this;
	};

}(window, document, jQuery)); /*!
 * Buttons helper for fancyBox
 * version: 1.0.2
 * @requires fancyBox v2.0 or later
 *
 * Usage: 
 *     $(".fancybox").fancybox({
 *         buttons: {
 *             position : 'top'
 *         }
 *     });
 * 
 * Options:
 *     tpl - HTML template
 *     position - 'top' or 'bottom'
 * 
 */
(function ($) {
	//Shortcut for fancyBox object
	var F = $.fancybox;

	//Add helper object
	F.helpers.buttons = {
		tpl: '<div id="fancybox-buttons"><ul><li><a class="btnPrev" title="Previous" href="javascript:;"></a></li><li><a class="btnPlay" title="Start slideshow" href="javascript:;"></a></li><li><a class="btnNext" title="Next" href="javascript:;"></a></li><li><a class="btnToggle" title="Toggle size" href="javascript:;"></a></li><li><a class="btnClose" title="Close" href="javascript:jQuery.fancybox.close();"></a></li></ul></div>',
		list: null,
		buttons: {},

		update: function () {
			var toggle = this.buttons.toggle.removeClass('btnDisabled btnToggleOn');

			//Size toggle button
			if (F.current.canShrink) {
				toggle.addClass('btnToggleOn');

			} else if (!F.current.canExpand) {
				toggle.addClass('btnDisabled');
			}
		},

		beforeLoad: function (opts) {
			//Remove self if gallery do not have at least two items
			if (F.group.length < 2) {
				F.coming.helpers.buttons = false;
				F.coming.closeBtn = true;

				return;
			}

			//Increase top margin to give space for buttons
			F.coming.margin[ opts.position === 'bottom' ? 2 : 0 ] += 30;
		},

		onPlayStart: function () {
			if (this.list) {
				this.buttons.play.attr('title', 'Pause slideshow').addClass('btnPlayOn');
			}
		},

		onPlayEnd: function () {
			if (this.list) {
				this.buttons.play.attr('title', 'Start slideshow').removeClass('btnPlayOn');
			}
		},

		afterShow: function (opts) {
			var buttons;

			if (!this.list) {
				this.list = $(opts.tpl || this.tpl).addClass(opts.position || 'top').appendTo('body');

				this.buttons = {
					prev : this.list.find('.btnPrev').click( F.prev ),
					next : this.list.find('.btnNext').click( F.next ),
					play : this.list.find('.btnPlay').click( F.play ),
					toggle : this.list.find('.btnToggle').click( F.toggle )
				}
			}

			buttons = this.buttons;

			//Prev
			if (F.current.index > 0 || F.current.loop) {
				buttons.prev.removeClass('btnDisabled');
			} else {
				buttons.prev.addClass('btnDisabled');
			}

			//Next / Play
			if (F.current.loop || F.current.index < F.group.length - 1) {
				buttons.next.removeClass('btnDisabled');
				buttons.play.removeClass('btnDisabled');

			} else {
				buttons.next.addClass('btnDisabled');
				buttons.play.addClass('btnDisabled');
			}

			this.update();
		},

		onUpdate: function () {
			this.update();
		},

		beforeClose: function () {
			if (this.list) {
				this.list.remove();
			}

			this.list = null;
			this.buttons = {};
		}
	};

}(jQuery)); /*!
 * Thumbnail helper for fancyBox
 * version: 1.0.2
 * @requires fancyBox v2.0 or later
 *
 * Usage: 
 *     $(".fancybox").fancybox({
 *         thumbs: {
 *             width	: 50,
 *             height	: 50
 *         }
 *     });
 * 
 * Options:
 *     width - thumbnail width
 *     height - thumbnail height
 *     source - function to obtain the URL of the thumbnail image
 *     position - 'top' or 'bottom'
 * 
 */
(function ($) {
	//Shortcut for fancyBox object
	var F = $.fancybox;

	//Add helper object
	F.helpers.thumbs = {
		wrap: null,
		list: null,
		width: 0,

		//Default function to obtain the URL of the thumbnail image
		source: function (el) {
			var img = $(el).find('img');

			return img.length ? img.attr('src') : el.href;
		},

		init: function (opts) {
			var that = this,
				list,
				thumbWidth = opts.width || 50,
				thumbHeight = opts.height || 50,
				thumbSource = opts.source || this.source;

			//Build list structure
			list = '';

			for (var n = 0; n < F.group.length; n++) {
				list += '<li><a style="width:' + thumbWidth + 'px;height:' + thumbHeight + 'px;" href="javascript:jQuery.fancybox.jumpto(' + n + ');"></a></li>';
			}

			this.wrap = $('<div id="fancybox-thumbs"></div>').addClass(opts.position || 'bottom').appendTo('body');
			this.list = $('<ul>' + list + '</ul>').appendTo(this.wrap);

			//Load each thumbnail
			$.each(F.group, function (i) {
				$("<img />").load(function () {
					var width = this.width,
						height = this.height,
						widthRatio, heightRatio, parent;

					if (!that.list || !width || !height) {
						return;
					}

					//Calculate thumbnail width/height and center it
					widthRatio = width / thumbWidth;
					heightRatio = height / thumbHeight;
					parent = that.list.children().eq(i).find('a');

					if (widthRatio >= 1 && heightRatio >= 1) {
						if (widthRatio > heightRatio) {
							width = Math.floor(width / heightRatio);
							height = thumbHeight;

						} else {
							width = thumbWidth;
							height = Math.floor(height / widthRatio);
						}
					}

					$(this).css({
						width: width,
						height: height,
						top: Math.floor(thumbHeight / 2 - height / 2),
						left: Math.floor(thumbWidth / 2 - width / 2)
					});

					parent.width(thumbWidth).height(thumbHeight);

					$(this).hide().appendTo(parent).fadeIn(300);

				}).attr('src', thumbSource(this));
			});

			//Set initial width
			this.width = this.list.children().eq(0).outerWidth();

			this.list.width(this.width * (F.group.length + 1)).css('left', Math.floor($(window).width() * 0.5 - (F.current.index * this.width + this.width * 0.5)));
		},

		//Center list
		update: function (opts) {
			if (this.list) {
				this.list.stop(true).animate({
					'left': Math.floor($(window).width() * 0.5 - (F.current.index * this.width + this.width * 0.5))
				}, 150);
			}
		},

		beforeLoad: function (opts) {
			//Remove self if gallery do not have at least two items 
			if (F.group.length < 2) {
				F.coming.helpers.thumbs = false;

				return;
			}

			//Increase bottom margin to give space for thumbs
			F.coming.margin[ opts.position === 'top' ? 0 : 2 ] = opts.height + 30;
		},

		afterShow: function (opts) {
			//Check if exists and create or update list
			if (this.list) {
				this.update(opts);

			} else {
				this.init(opts);
			}

			//Set active element
			this.list.children().removeClass('active').eq(F.current.index).addClass('active');
		},

		onUpdate: function () {
			this.update();
		},

		beforeClose: function () {
			if (this.wrap) {
				this.wrap.remove();
			}

			this.wrap = null;
			this.list = null;
			this.width = 0;
		}
	}

}(jQuery));﻿/*!
 * jQuery blockUI plugin
 * Version 2.39 (23-MAY-2011)
 * @requires jQuery v1.2.3 or later
 *
 * Examples at: http://malsup.com/jquery/block/
 * Copyright (c) 2007-2010 M. Alsup
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Thanks to Amir-Hossein Sobhi for some excellent contributions!
 */

;(function($) {

if (/1\.(0|1|2)\.(0|1|2)/.test($.fn.jquery) || /^1.1/.test($.fn.jquery)) {
	alert('blockUI requires jQuery v1.2.3 or later!  You are using v' + $.fn.jquery);
	return;
}

$.fn._fadeIn = $.fn.fadeIn;

var noOp = function() {};

// this bit is to ensure we don't call setExpression when we shouldn't (with extra muscle to handle
// retarded userAgent strings on Vista)
var mode = document.documentMode || 0;
var setExpr = $.browser.msie && (($.browser.version < 8 && !mode) || mode < 8);
var ie6 = $.browser.msie && /MSIE 6.0/.test(navigator.userAgent) && !mode;

// global $ methods for blocking/unblocking the entire page
$.blockUI   = function(opts) { install(window, opts); };
$.unblockUI = function(opts) { remove(window, opts); };

// convenience method for quick growl-like notifications  (http://www.google.com/search?q=growl)
$.growlUI = function(title, message, timeout, onClose) {
	var $m = $('<div class="growlUI"></div>');
	if (title) $m.append('<h1>'+title+'</h1>');
	if (message) $m.append('<h2>'+message+'</h2>');
	if (timeout == undefined) timeout = 3000;
	$.blockUI({
		message: $m, fadeIn: 700, fadeOut: 1000, centerY: false,
		timeout: timeout, showOverlay: false,
		onUnblock: onClose, 
		css: $.blockUI.defaults.growlCSS
	});
};

// plugin method for blocking element content
$.fn.block = function(opts) {
	return this.unblock({ fadeOut: 0 }).each(function() {
		if ($.css(this,'position') == 'static')
			this.style.position = 'relative';
		if ($.browser.msie)
			this.style.zoom = 1; // force 'hasLayout'
		install(this, opts);
	});
};

// plugin method for unblocking element content
$.fn.unblock = function(opts) {
	return this.each(function() {
		remove(this, opts);
	});
};

$.blockUI.version = 2.39; // 2nd generation blocking at no extra cost!

// override these in your code to change the default behavior and style
$.blockUI.defaults = {
	// message displayed when blocking (use null for no message)
	message:  '<h1>Please wait...</h1>',

	title: null,	  // title string; only used when theme == true
	draggable: true,  // only used when theme == true (requires jquery-ui.js to be loaded)
	
	theme: false, // set to true to use with jQuery UI themes
	
	// styles for the message when blocking; if you wish to disable
	// these and use an external stylesheet then do this in your code:
	// $.blockUI.defaults.css = {};
	css: {
		padding:	0,
		margin:		0,
		width:		'30%',
		top:		'40%',
		left:		'35%',
		textAlign:	'center',
		color:		'#000',
		border:		'3px solid #aaa',
		backgroundColor:'#fff',
		cursor:		'wait'
	},
	
	// minimal style set used when themes are used
	themedCSS: {
		width:	'30%',
		top:	'40%',
		left:	'35%'
	},

	// styles for the overlay
	overlayCSS:  {
		backgroundColor: '#000',
		opacity:	  	 0.6,
		cursor:		  	 'wait'
	},

	// styles applied when using $.growlUI
	growlCSS: {
		width:  	'350px',
		top:		'10px',
		left:   	'',
		right:  	'10px',
		border: 	'none',
		padding:	'5px',
		opacity:	0.6,
		cursor: 	'default',
		color:		'#fff',
		backgroundColor: '#000',
		'-webkit-border-radius': '10px',
		'-moz-border-radius':	 '10px',
		'border-radius': 		 '10px'
	},
	
	// IE issues: 'about:blank' fails on HTTPS and javascript:false is s-l-o-w
	// (hat tip to Jorge H. N. de Vasconcelos)
	iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank',

	// force usage of iframe in non-IE browsers (handy for blocking applets)
	forceIframe: false,

	// z-index for the blocking overlay
	baseZ: 1000,

	// set these to true to have the message automatically centered
	centerX: true, // <-- only effects element blocking (page block controlled via css above)
	centerY: true,

	// allow body element to be stetched in ie6; this makes blocking look better
	// on "short" pages.  disable if you wish to prevent changes to the body height
	allowBodyStretch: true,

	// enable if you want key and mouse events to be disabled for content that is blocked
	bindEvents: true,

	// be default blockUI will supress tab navigation from leaving blocking content
	// (if bindEvents is true)
	constrainTabKey: true,

	// fadeIn time in millis; set to 0 to disable fadeIn on block
	fadeIn:  200,

	// fadeOut time in millis; set to 0 to disable fadeOut on unblock
	fadeOut:  400,

	// time in millis to wait before auto-unblocking; set to 0 to disable auto-unblock
	timeout: 0,

	// disable if you don't want to show the overlay
	showOverlay: true,

	// if true, focus will be placed in the first available input field when
	// page blocking
	focusInput: true,

	// suppresses the use of overlay styles on FF/Linux (due to performance issues with opacity)
	applyPlatformOpacityRules: true,
	
	// callback method invoked when fadeIn has completed and blocking message is visible
	onBlock: null,

	// callback method invoked when unblocking has completed; the callback is
	// passed the element that has been unblocked (which is the window object for page
	// blocks) and the options that were passed to the unblock call:
	//	 onUnblock(element, options)
	onUnblock: null,

	// don't ask; if you really must know: http://groups.google.com/group/jquery-en/browse_thread/thread/36640a8730503595/2f6a79a77a78e493#2f6a79a77a78e493
	quirksmodeOffsetHack: 4,

	// class name of the message block
	blockMsgClass: 'blockMsg'
};

// private data and functions follow...

var pageBlock = null;
var pageBlockEls = [];

function install(el, opts) {
	var full = (el == window);
	var msg = opts && opts.message !== undefined ? opts.message : undefined;
	opts = $.extend({}, $.blockUI.defaults, opts || {});
	opts.overlayCSS = $.extend({}, $.blockUI.defaults.overlayCSS, opts.overlayCSS || {});
	var css = $.extend({}, $.blockUI.defaults.css, opts.css || {});
	var themedCSS = $.extend({}, $.blockUI.defaults.themedCSS, opts.themedCSS || {});
	msg = msg === undefined ? opts.message : msg;

	// remove the current block (if there is one)
	if (full && pageBlock)
		remove(window, {fadeOut:0});

	// if an existing element is being used as the blocking content then we capture
	// its current place in the DOM (and current display style) so we can restore
	// it when we unblock
	if (msg && typeof msg != 'string' && (msg.parentNode || msg.jquery)) {
		var node = msg.jquery ? msg[0] : msg;
		var data = {};
		$(el).data('blockUI.history', data);
		data.el = node;
		data.parent = node.parentNode;
		data.display = node.style.display;
		data.position = node.style.position;
		if (data.parent)
			data.parent.removeChild(node);
	}

	$(el).data('blockUI.onUnblock', opts.onUnblock);
	var z = opts.baseZ;

	// blockUI uses 3 layers for blocking, for simplicity they are all used on every platform;
	// layer1 is the iframe layer which is used to supress bleed through of underlying content
	// layer2 is the overlay layer which has opacity and a wait cursor (by default)
	// layer3 is the message content that is displayed while blocking

	var lyr1 = ($.browser.msie || opts.forceIframe) 
		? $('<iframe class="blockUI" style="z-index:'+ (z++) +';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="'+opts.iframeSrc+'"></iframe>')
		: $('<div class="blockUI" style="display:none"></div>');
	
	var lyr2 = opts.theme 
	 	? $('<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:'+ (z++) +';display:none"></div>')
	 	: $('<div class="blockUI blockOverlay" style="z-index:'+ (z++) +';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>');

	var lyr3, s;
	if (opts.theme && full) {
		s = '<div class="blockUI ' + opts.blockMsgClass + ' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:'+(z+10)+';display:none;position:fixed">' +
				'<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(opts.title || '&nbsp;')+'</div>' +
				'<div class="ui-widget-content ui-dialog-content"></div>' +
			'</div>';
	}
	else if (opts.theme) {
		s = '<div class="blockUI ' + opts.blockMsgClass + ' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:'+(z+10)+';display:none;position:absolute">' +
				'<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(opts.title || '&nbsp;')+'</div>' +
				'<div class="ui-widget-content ui-dialog-content"></div>' +
			'</div>';
	}
	else if (full) {
		s = '<div class="blockUI ' + opts.blockMsgClass + ' blockPage" style="z-index:'+(z+10)+';display:none;position:fixed"></div>';
	}			 
	else {
		s = '<div class="blockUI ' + opts.blockMsgClass + ' blockElement" style="z-index:'+(z+10)+';display:none;position:absolute"></div>';
	}
	lyr3 = $(s);

	// if we have a message, style it
	if (msg) {
		if (opts.theme) {
			lyr3.css(themedCSS);
			lyr3.addClass('ui-widget-content');
		}
		else 
			lyr3.css(css);
	}

	// style the overlay
	if (!opts.theme && (!opts.applyPlatformOpacityRules || !($.browser.mozilla && /Linux/.test(navigator.platform))))
		lyr2.css(opts.overlayCSS);
	lyr2.css('position', full ? 'fixed' : 'absolute');

	// make iframe layer transparent in IE
	if ($.browser.msie || opts.forceIframe)
		lyr1.css('opacity',0.0);

	//$([lyr1[0],lyr2[0],lyr3[0]]).appendTo(full ? 'body' : el);
	var layers = [lyr1,lyr2,lyr3], $par = full ? $('body') : $(el);
	$.each(layers, function() {
		this.appendTo($par);
	});
	
	if (opts.theme && opts.draggable && $.fn.draggable) {
		lyr3.draggable({
			handle: '.ui-dialog-titlebar',
			cancel: 'li'
		});
	}

	// ie7 must use absolute positioning in quirks mode and to account for activex issues (when scrolling)
	var expr = setExpr && (!$.boxModel || $('object,embed', full ? null : el).length > 0);
	if (ie6 || expr) {
		// give body 100% height
		if (full && opts.allowBodyStretch && $.boxModel)
			$('html,body').css('height','100%');

		// fix ie6 issue when blocked element has a border width
		if ((ie6 || !$.boxModel) && !full) {
			var t = sz(el,'borderTopWidth'), l = sz(el,'borderLeftWidth');
			var fixT = t ? '(0 - '+t+')' : 0;
			var fixL = l ? '(0 - '+l+')' : 0;
		}

		// simulate fixed position
		$.each([lyr1,lyr2,lyr3], function(i,o) {
			var s = o[0].style;
			s.position = 'absolute';
			if (i < 2) {
				full ? s.setExpression('height','Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.boxModel?0:'+opts.quirksmodeOffsetHack+') + "px"')
					 : s.setExpression('height','this.parentNode.offsetHeight + "px"');
				full ? s.setExpression('width','jQuery.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"')
					 : s.setExpression('width','this.parentNode.offsetWidth + "px"');
				if (fixL) s.setExpression('left', fixL);
				if (fixT) s.setExpression('top', fixT);
			}
			else if (opts.centerY) {
				if (full) s.setExpression('top','(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"');
				s.marginTop = 0;
			}
			else if (!opts.centerY && full) {
				var top = (opts.css && opts.css.top) ? parseInt(opts.css.top) : 0;
				var expression = '((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + '+top+') + "px"';
				s.setExpression('top',expression);
			}
		});
	}

	// show the message
	if (msg) {
		if (opts.theme)
			lyr3.find('.ui-widget-content').append(msg);
		else
			lyr3.append(msg);
		if (msg.jquery || msg.nodeType)
			$(msg).show();
	}

	if (($.browser.msie || opts.forceIframe) && opts.showOverlay)
		lyr1.show(); // opacity is zero
	if (opts.fadeIn) {
		var cb = opts.onBlock ? opts.onBlock : noOp;
		var cb1 = (opts.showOverlay && !msg) ? cb : noOp;
		var cb2 = msg ? cb : noOp;
		if (opts.showOverlay)
			lyr2._fadeIn(opts.fadeIn, cb1);
		if (msg)
			lyr3._fadeIn(opts.fadeIn, cb2);
	}
	else {
		if (opts.showOverlay)
			lyr2.show();
		if (msg)
			lyr3.show();
		if (opts.onBlock)
			opts.onBlock();
	}

	// bind key and mouse events
	bind(1, el, opts);

	if (full) {
		pageBlock = lyr3[0];
		pageBlockEls = $(':input:enabled:visible',pageBlock);
		if (opts.focusInput)
			setTimeout(focus, 20);
	}
	else
		center(lyr3[0], opts.centerX, opts.centerY);

	if (opts.timeout) {
		// auto-unblock
		var to = setTimeout(function() {
			full ? $.unblockUI(opts) : $(el).unblock(opts);
		}, opts.timeout);
		$(el).data('blockUI.timeout', to);
	}
};

// remove the block
function remove(el, opts) {
	var full = (el == window);
	var $el = $(el);
	var data = $el.data('blockUI.history');
	var to = $el.data('blockUI.timeout');
	if (to) {
		clearTimeout(to);
		$el.removeData('blockUI.timeout');
	}
	opts = $.extend({}, $.blockUI.defaults, opts || {});
	bind(0, el, opts); // unbind events

	if (opts.onUnblock === null) {
		opts.onUnblock = $el.data('blockUI.onUnblock');
		$el.removeData('blockUI.onUnblock');
	}

	var els;
	if (full) // crazy selector to handle odd field errors in ie6/7
		els = $('body').children().filter('.blockUI').add('body > .blockUI');
	else
		els = $('.blockUI', el);

	if (full)
		pageBlock = pageBlockEls = null;

	if (opts.fadeOut) {
		els.fadeOut(opts.fadeOut);
		setTimeout(function() { reset(els,data,opts,el); }, opts.fadeOut);
	}
	else
		reset(els, data, opts, el);
};

// move blocking element back into the DOM where it started
function reset(els,data,opts,el) {
	els.each(function(i,o) {
		// remove via DOM calls so we don't lose event handlers
		if (this.parentNode)
			this.parentNode.removeChild(this);
	});

	if (data && data.el) {
		data.el.style.display = data.display;
		data.el.style.position = data.position;
		if (data.parent)
			data.parent.appendChild(data.el);
		$(el).removeData('blockUI.history');
	}

	if (typeof opts.onUnblock == 'function')
		opts.onUnblock(el,opts);
};

// bind/unbind the handler
function bind(b, el, opts) {
	var full = el == window, $el = $(el);

	// don't bother unbinding if there is nothing to unbind
	if (!b && (full && !pageBlock || !full && !$el.data('blockUI.isBlocked')))
		return;
	if (!full)
		$el.data('blockUI.isBlocked', b);

	// don't bind events when overlay is not in use or if bindEvents is false
	if (!opts.bindEvents || (b && !opts.showOverlay)) 
		return;

	// bind anchors and inputs for mouse and key events
	var events = 'mousedown mouseup keydown keypress';
	b ? $(document).bind(events, opts, handler) : $(document).unbind(events, handler);

// former impl...
//	   var $e = $('a,:input');
//	   b ? $e.bind(events, opts, handler) : $e.unbind(events, handler);
};

// event handler to suppress keyboard/mouse events when blocking
function handler(e) {
	// allow tab navigation (conditionally)
	if (e.keyCode && e.keyCode == 9) {
		if (pageBlock && e.data.constrainTabKey) {
			var els = pageBlockEls;
			var fwd = !e.shiftKey && e.target === els[els.length-1];
			var back = e.shiftKey && e.target === els[0];
			if (fwd || back) {
				setTimeout(function(){focus(back)},10);
				return false;
			}
		}
	}
	var opts = e.data;
	// allow events within the message content
	if ($(e.target).parents('div.' + opts.blockMsgClass).length > 0)
		return true;

	// allow events for content that is not being blocked
	return $(e.target).parents().children().filter('div.blockUI').length == 0;
};

function focus(back) {
	if (!pageBlockEls)
		return;
	var e = pageBlockEls[back===true ? pageBlockEls.length-1 : 0];
	if (e)
		e.focus();
};

function center(el, x, y) {
	var p = el.parentNode, s = el.style;
	var l = ((p.offsetWidth - el.offsetWidth)/2) - sz(p,'borderLeftWidth');
	var t = ((p.offsetHeight - el.offsetHeight)/2) - sz(p,'borderTopWidth');
	if (x) s.left = l > 0 ? (l+'px') : '0';
	if (y) s.top  = t > 0 ? (t+'px') : '0';
};

function sz(el, p) {
	return parseInt($.css(el,p))||0;
};

})(jQuery);
/*
 * jQuery history plugin
 * 
 * sample page: http://www.mikage.to/jquery/jquery_history.html
 *
 * Copyright (c) 2006-2009 Taku Sano (Mikage Sawatari)
 * Licensed under the MIT License:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Modified by Lincoln Cooper to add Safari support and only call the callback once during initialization
 * for msie when no initial hash supplied.
 */


jQuery.extend({
	historyCurrentHash: undefined,
	historyCallback: undefined,
	historyIframeSrc: undefined,
	historyNeedIframe: jQuery.browser.msie && (jQuery.browser.version < 8 || document.documentMode < 8),
	
	historyInit: function(callback, src){
		jQuery.historyCallback = callback;
		if (src) jQuery.historyIframeSrc = src;
		var current_hash = location.hash.replace(/\?.*$/, '');
		
		jQuery.historyCurrentHash = current_hash;
		if (jQuery.historyNeedIframe) {
			// To stop the callback firing twice during initilization if no hash present
			if (jQuery.historyCurrentHash == '') {
				jQuery.historyCurrentHash = '#';
			}
		
			// add hidden iframe for IE
			jQuery("body").prepend('<iframe id="jQuery_history" style="display: none;"'+
				' src="javascript:false;"></iframe>'
			);
			var ihistory = jQuery("#jQuery_history")[0];
			var iframe = ihistory.contentWindow.document;
			iframe.open();
			iframe.close();
			iframe.location.hash = current_hash;
		}
		else if (jQuery.browser.safari) {
			// etablish back/forward stacks
			jQuery.historyBackStack = [];
			jQuery.historyBackStack.length = history.length;
			jQuery.historyForwardStack = [];
			jQuery.lastHistoryLength = history.length;
			
			jQuery.isFirst = true;
		}
		if(current_hash)
			jQuery.historyCallback(current_hash.replace(/^#/, ''));
		setInterval(jQuery.historyCheck, 100);
	},
	
	historyAddHistory: function(hash) {
		// This makes the looping function do something
		jQuery.historyBackStack.push(hash);
		
		jQuery.historyForwardStack.length = 0; // clear forwardStack (true click occured)
		this.isFirst = true;
	},
	
	historyCheck: function(){
		if (jQuery.historyNeedIframe) {
			// On IE, check for location.hash of iframe
			var ihistory = jQuery("#jQuery_history")[0];
			var iframe = ihistory.contentDocument || ihistory.contentWindow.document;
			var current_hash = iframe.location.hash.replace(/\?.*$/, '');
			if(current_hash != jQuery.historyCurrentHash) {
			
				location.hash = current_hash;
				jQuery.historyCurrentHash = current_hash;
				jQuery.historyCallback(current_hash.replace(/^#/, ''));
				
			}
		} else if (jQuery.browser.safari) {
			if(jQuery.lastHistoryLength == history.length && jQuery.historyBackStack.length > jQuery.lastHistoryLength) {
				jQuery.historyBackStack.shift();
			}
			if (!jQuery.dontCheck) {
				var historyDelta = history.length - jQuery.historyBackStack.length;
				jQuery.lastHistoryLength = history.length;
				
				if (historyDelta) { // back or forward button has been pushed
					jQuery.isFirst = false;
					if (historyDelta < 0) { // back button has been pushed
						// move items to forward stack
						for (var i = 0; i < Math.abs(historyDelta); i++) jQuery.historyForwardStack.unshift(jQuery.historyBackStack.pop());
					} else { // forward button has been pushed
						// move items to back stack
						for (var i = 0; i < historyDelta; i++) jQuery.historyBackStack.push(jQuery.historyForwardStack.shift());
					}
					var cachedHash = jQuery.historyBackStack[jQuery.historyBackStack.length - 1];
					if (cachedHash != undefined) {
						jQuery.historyCurrentHash = location.hash.replace(/\?.*$/, '');
						jQuery.historyCallback(cachedHash);
					}
				} else if (jQuery.historyBackStack[jQuery.historyBackStack.length - 1] == undefined && !jQuery.isFirst) {
					// back button has been pushed to beginning and URL already pointed to hash (e.g. a bookmark)
					// document.URL doesn't change in Safari
					if (location.hash) {
						var current_hash = location.hash;
						jQuery.historyCallback(location.hash.replace(/^#/, ''));
					} else {
						var current_hash = '';
						jQuery.historyCallback('');
					}
					jQuery.isFirst = true;
				}
			}
		} else {
			// otherwise, check for location.hash
			var current_hash = location.hash.replace(/\?.*$/, '');
			if(current_hash != jQuery.historyCurrentHash) {
				jQuery.historyCurrentHash = current_hash;
				jQuery.historyCallback(current_hash.replace(/^#/, ''));
			}
		}
	},
	historyLoad: function(hash){
		var newhash;
		hash = decodeURIComponent(hash.replace(/\?.*$/, ''));
		
		if (jQuery.browser.safari) {
			newhash = hash;
		}
		else {
			newhash = '#' + hash;
			location.hash = newhash;
		}
		jQuery.historyCurrentHash = newhash;
		
		if (jQuery.historyNeedIframe) {
			var ihistory = jQuery("#jQuery_history")[0];
			var iframe = ihistory.contentWindow.document;
			iframe.open();
			iframe.close();
			iframe.location.hash = newhash;
			jQuery.lastHistoryLength = history.length;
			jQuery.historyCallback(hash);
		}
		else if (jQuery.browser.safari) {
			jQuery.dontCheck = true;
			// Manually keep track of the history values for Safari
			this.historyAddHistory(hash);
			
			// Wait a while before allowing checking so that Safari has time to update the "history" object
			// correctly (otherwise the check loop would detect a false change in hash).
			var fn = function() {jQuery.dontCheck = false;};
			window.setTimeout(fn, 200);
			jQuery.historyCallback(hash);
			// N.B. "location.hash=" must be the last line of code for Safari as execution stops afterwards.
			//      By explicitly using the "location.hash" command (instead of using a variable set to "location.hash") the
			//      URL in the browser and the "history" object are both updated correctly.
			location.hash = newhash;
		}
		else {
		  jQuery.historyCallback(hash);
		}
	}
});


