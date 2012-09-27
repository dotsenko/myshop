(function ($) {

	df.namespace ('df.checkout');


	/**
	 * @param {String} elementSelector
	 */
	df.checkout.Ergonomic = {

		interfaceUpdated: 'df.checkout.Ergonomic.interfaceUpdated'

		,
		loadWaiting: 'df.checkout.Ergonomic.loadWaiting'

		,
		sectionUpdated: 'df.checkout.Ergonomic.sectionUpdated'

		,
		construct: function (_config) { var _this = {


			init: function () {


				$(function () {

					if (0 < _this.getElement().size()) {

						checkout.reloadProgressBlock = function () {};


						_this.loadWaiting_adjust();



						$('a.df-login', _this.getElement ())
							.fancybox ({
								'titlePosition'		: 'inside',
								'transitionIn'		: 'none',
								'transitionOut'		: 'none'
							})
						;


						/**
						 * @type {df.checkout.ergonomic.address.Billing}
						 */
						df.checkout.ergonomic.billingAddressSingleton =
							df.checkout.ergonomic.address.Billing
								.construct (
									{
										element: $('.df-block-address-billing', _this.getElement())
									}
								)
						;


						/**
						 * @type {df.checkout.ergonomic.address.Shipping}
						 */
						df.checkout.ergonomic.shippingAddressSingleton =
							df.checkout.ergonomic.address.Shipping
								.construct (
									{
										element: $('.df-block-address-shipping', _this.getElement())
									}
								)
						;


						/**
						 * @type {df.checkout.ergonomic.method.Shipping}
						 */
						df.checkout.ergonomic.shippingMethodSingleton =
							df.checkout.ergonomic.method.Shipping
								.construct (
									{
										element: $('.df-block-method-shipping', _this.getElement())
									}
								)
						;


						/**
						 * @type {df.checkout.ergonomic.method.Payment}
						 */
						df.checkout.ergonomic.paymentMethodSingleton =
							df.checkout.ergonomic.method.Payment
								.construct (
									{
										element: $('.df-block-method-payment', _this.getElement())
									}
								)
						;


						/**
						 * @type {df.checkout.ergonomic.Review}
						 */
						df.checkout.ergonomic.reviewSingleton =
							df.checkout.ergonomic.Review
								.construct (
									{
										element: $('.order-review', _this.getElement())
									}
								)
						;


					}

				});

			}



			,
			/**
			 * @private
			 * @returns {df.checkout.Ergonomic}
			 */
			loadWaiting_adjust: function () {

				/** @function */
				var originalFunction = this.getCheckout().setLoadWaiting;

				this.getCheckout().setLoadWaiting = function (step, keepDisabled) {

					originalFunction.call (_this.getCheckout(), step, keepDisabled);


					if (false !== step) {
						_this.loadWaiting_enable();
					}
					else {
						_this.loadWaiting_disable();
					}

				};

				return this;
			}



			,
			/**
			 * @private
			 * @returns {df.checkout.Ergonomic}
			 */
			loadWaiting_disable: function () {

				$.unblockUI();

				return this;

			}




			,
			/**
			 * @private
			 * @returns {df.checkout.Ergonomic}
			 */
			loadWaiting_enable: function () {

				$.blockUI({
					message: $('#df-loading-mask')
					,
					css: {
						border: 0
					}
					,
					overlayCSS:  {
						opacity: 0
					}
				});

				return this;
			}



			,
			/**
			 * @private
			 * @returns {Checkout}
			 */
			getCheckout: function () {
				return checkout;
			}


			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getElement: function () {

				if ('undefined' == typeof this._element) {

					/** @type {jQuery} HTMLElement */
					var result =
						$(this.getElementSelector ())
					;

					this._element = result;

				}

				return this._element;
			}


			,
			/**
			 * @private
			 * @returns {String}
			 */
			getElementSelector: function () {
				return _config.elementSelector;
			}


		}; _this.init (); return _this; }


	};





})(jQuery);(function ($) {

	df.namespace ('df.checkout');


	df.checkout.OrderComments = {

		construct: function (_config) { var _this = {

			init: function () {

				if (
					/**
					 * Отсутствие блока комментариев
					 * говорит об отключенности данной функциональности
					 */
					(0 < this.getElement().size())
				) {

					if (this.isItMultiShippingCheckout()) {

						if ('below' === df.checkout.orderComments.position) {

							this.getTarget().after (this.getElement ());

						}
						else {

							this.getTarget().before (this.getElement ());

						}
					}

					else {
						if (0 === this.getAgreements().size()) {

							$('#checkout-review-submit')
								.prepend (
									$('<form/>')
										.attr ({
											id: 'checkout-agreements'
											,
											action: ''
											,
											onsubmit: 'return false;'
										})
										.append (
											this.getElement ()
										)
								)
							;
						}
						else {

							this.getElement ().removeClass ('buttons-set');

							if ('below' === df.checkout.orderComments.position) {
								this.getTarget().append (this.getElement ());
							}
							else {
								this.getTarget().prepend (this.getElement ());
							}

						}

					}

				}

			}




			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement[]
			 */
			getAgreements: function () {

				if ('undefined' == typeof this._agreements) {

					/** @type {jQuery} HTMLElement */
					this._agreements =
						$('.agree')
					;
				}

				return this._agreements;
			}




			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getElement: function () {

				if ('undefined' == typeof this._element) {

					/** @type {jQuery} HTMLElement */
					this._element =
						$('#df_checkout_review_orderComments')
							.clone()
							.removeAttr ('id')
							.removeClass ('df-hidden')
					;


					/**
					 * Стандартный браузерный программный код оформления заказа
					 * перезаписывает блок review
					 * после практически любых шагов покупателя при оформлении заказа.
					 *
					 * При этом перезаписывается и блок комментариев, и комментарии теряются.
					 *
					 * Чтобы сохранить комментарии,
					 * надо на событие потери фокуса блоком комментариев
					 * сохранять комментарий в какой-нибудь браузерной переменной
					 * (но не динамической переменной внутри данного класса,
					 * потому что объект данного класса создается заново
					 * после перезаписи блока review).
					 */

					/** @type {jQuery} HTMLTextAreaElement */
					var $textarea = $('textarea', this._element);

					$textarea
						.blur (
							function () {
								df.checkout.ergonomic.helperSingleton.orderComment =
									$textarea.val()
								;
							}
						)
						.val(
							df.checkout.ergonomic.helperSingleton.orderComment
						)
					;


				}

				return this._element;
			}




			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getTarget: function () {

				if ('undefined' == typeof this._target) {

					/** @type {jQuery} HTMLElement */
					this._target =
						$('#checkout-agreements')
					;

				}

				return this._target;
			}




			,
			/**
			 * @private
			 * @returns {Boolean}
			 */
			isItMultiShippingCheckout: function () {

				if ('undefined' == typeof this._itIsMultiShippingCheckout) {

					/** @type {jQuery} HTMLElement */
					this._itIsMultiShippingCheckout =
						0 < $('.multiple-checkout').size()
					;

				}

				return this._itIsMultiShippingCheckout;
			}



		}; _this.init (); return _this; }


	};





})(jQuery);/**
 * Программный код,
 * который надо выполнить сразу после загрузки страницы
 */

df.namespace ('df.checkout');

(function ($) { $(function () {

	df.checkout.Ergonomic
		.construct (
			{
				elementSelector: '.df .df-checkout-ergonomic'
			}
		)
	;

	df.checkout.OrderComments
		.construct (
			{
			}
		)
	;

	$(window)
		.bind (
			df.checkout.Ergonomic.sectionUpdated
			,
			/**
			 * @param {jQuery.Event} event
			 */
			function (event) {

				if ('review' === event.section) {
					df.checkout.OrderComments
						.construct (
							{
							}
						)
					;

				}
			}
		)
	;

}); })(jQuery);(function ($) {

	df.namespace ('df.checkout.ergonomic');


	df.checkout.ergonomic.Helper = {

		/**
		 * @function
		 * @returns {df.checkout.ergonomic.Helper}
		 */
		construct: function (_config) { var _this = {

			init: function () {

			}



			,
			/**
			 * @public
			 * @param {String} inputId
			 * @returns {df.checkout.ergonomic.address.Billing}
			 */
			addFakeInputIfNeeded: function (inputId) {

				if (!document.getElementById (inputId)) {
					$('<input/>')
						.attr ({
							id: inputId
							,
							type: 'text'
						})
						.hide ()
						.appendTo (this.getFakeForm())
					;

				}

				return this;
			}




			,
			/**
			 * @public
			 * @returns {jQuery} HTMLFormElement
			 */
			getFakeForm: function () {

				if ('undefined' === typeof this._fakeForm) {

					var fakeFormClass = 'df-fake-form';

					/**
					 * @type {jQuery} HTMLFormElement
					 */
					this._fakeForm = $('form.' + fakeFormClass);

					if (1 > this._fakeForm.size ()) {
						this._fakeForm =
							$('<form/>')
								.addClass (fakeFormClass)
								.appendTo ('body')
						;
					}
				}

				return this._fakeForm;
			}



		}; _this.init (); return _this; }


	};


	/**
	 * @type {df.checkout.ergonomic.Helper}
	 */
	df.checkout.ergonomic.helperSingleton =
		df.checkout.ergonomic.Helper
			.construct (
				{}
			)
	;





})(jQuery);(function ($) {

	df.namespace ('df.checkout.ergonomic');


	df.checkout.ergonomic.Review = {

		construct: function (_config) { var _this = {


			init: function () {

				/**
				 * При нажатии кнопки "Разместить заказ"
				 * система должна провести валидацию всех форм.
				 */

				/** @type {jQuery} HTMLButtonElement */
				var $submitOrderButton = $('#review-buttons-container .btn-checkout');

				$submitOrderButton
					.removeAttr('onclick')
					.click (
						/**
						 * @param {jQuery.Event} event
						 */
						function (event) {
							event.preventDefault();

							/** @type {Object}[] */
							var blocks =
								[
									df.checkout.ergonomic.billingAddressSingleton
									,
									df.checkout.ergonomic.shippingAddressSingleton
									,
									df.checkout.ergonomic.shippingMethodSingleton
									,
									df.checkout.ergonomic.paymentMethodSingleton

									/**
									 * Обратите внимание, что у Review нет формы и валидатора!
									 */
								]
							;

							/** @type {Boolean} */
							var valid = true;

							$.each (blocks, function (index, block) {

								if (!block.getValidator().validate()) {
									valid = false;
								}

							});

							if (valid) {

								if (false === _this.getCheckout().loadWaiting) {
									_this.save ();
								}
								else {
									/**
									 * Вызывать save() пока бесполезно, потому что система занята.
									 * Поэтому вместо прямого вызова save планируем этот вызов на будущее.
									 */
									_this.needSave (true);
								}


							}


						}
					)
				;


				$(window)
					.bind (
						df.checkout.Ergonomic.interfaceUpdated
						,
						/**
						 * @param {jQuery.Event} event
						 */
						function (event) {
							if (_this.needSave()) {
								_this.save ();
							}

						}
					)
				;

			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.Review}
			 */
			save: function () {

				_this.needSave (false);
				_this.getReview().save();

				return this;
			}




			,
			/**
			 * @private
			 * @returns {Review}
			 */
			getReview: function () {
				return review;
			}



			,
			/**
			 * @private
			 * @returns {Checkout}
			 */
			getCheckout: function () {
				return checkout;
			}




			,
			/**
			 * @public
			 * @param {Boolean}
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			needSave: function (value) {

				if ('undefined' !== typeof value) {
					this._needSave = value;
				}

				return this._needSave;
			}

			,
			/** @type {Boolean} */
			_needSave: false



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

	df.namespace ('df.checkout.ergonomic.address');


	/**
	 * @param {jQuery} element
	 */
	df.checkout.ergonomic.address.Billing = {

		shippingAddressIsTheSame: 'df.checkout.ergonomic.address.Billing.shippingAddressIsTheSame'

		,
		construct: function (_config) { var _this = {


			init: function () {

				this.getBilling().onComplete = this.onComplete.bindAsEventListener (this);

				this.handleShippingAddressHasNoFields ();

				this.listenForSelection ();

				this.handleUseForShipping ();

				this.addFakeRegionFieldsIfNeeded ();

			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			save: function () {

				_this.needSave (false);


				var $regionAsText = this.getAddress().getFieldRegionText().getElement();

				var $regionAsSelect = this.getAddress().getFieldRegionSelect().getElement();


				var regionAsText = $regionAsText.get(0);

				var regionAsSelect = $regionAsSelect.get(0);

				if (regionAsText && regionAsSelect) {

					if ('none' === regionAsText.style.display) {

						regionAsText.value = $('option:selected', $regionAsSelect).text();

					}
				}


				_this.getBilling().save();

				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Billing}
			 */
			addFakeRegionFieldsIfNeeded: function () {

				df.checkout.ergonomic.helperSingleton.addFakeInputIfNeeded ('billing:region');
				df.checkout.ergonomic.helperSingleton.addFakeInputIfNeeded ('billing:region_id');

				return this;
			}





			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Billing}
			 */
			listenForSelection: function () {

				_this.getAddress().getFields()
					.change (
						function () {

							_this.handleSelection ();

						}
					)
				;

				if (document.getElementById ('billing-address-select')) {
					this.handleSelection ();
				}

				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Billing}
			 */
			handleSelection: function () {

				this.getValidator().dfValidateFilledFieldsOnly();

				if (this.getValidator().dfValidateSilent()) {

					if (false === this.getCheckout().loadWaiting) {
						this.save ();
					}
					else {
						/**
						 * Вызывать save() пока бесполезно, потому что система занята.
						 * Поэтому вместо прямого вызова save планируем этот вызов на будущее.
						 */
						this.needSave (true);
					}

				}
				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			handleShippingAddressHasNoFields: function () {

				$(window)
					.bind (
						df.checkout.ergonomic.address.Shipping.hasNoFields
						,
						/**
						 * @param {jQuery.Event} event
						 */
						function (event) {

							$(_this.getAddress().getField ('use_for_shipping').getElement())
								.closest ('li.control')
									.hide ()
							;

						}
					)
				;

				return this;
			}




			,
			/**
			 * @public
			 * @param {Object} transport
			 * @returns {df.checkout.ergonomic.address.Billing}
			 */
			onComplete: function (transport) {

				this.getBilling().resetLoadWaiting (transport);

				$(window)
					.trigger (
						{
							/** @type {String} */
							type: df.checkout.Ergonomic.interfaceUpdated

							,
							/** @type {String} */
							updateType: 'billingAddress'
						}
					)
				;

				return this;
			}





			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Billing}
			 */
			handleUseForShipping: function () {

				_this.getAddress().getField ('use_for_shipping').getElement()
					.change (
						function () {
							_this.onUseForShipping ();
						}
					)
				;

				_this.onUseForShipping ();

				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Billing}
			 */
			onUseForShipping: function () {

				/** @type {Boolean} */
				var shippingAddressIsTheSame = document.getElementById ('billing:use_for_shipping_yes').checked;

				$(window)
					.trigger (
						{
							/** @type {String} */
							type: df.checkout.ergonomic.address.Billing.shippingAddressIsTheSame

							,
							/** @type {df.checkout.ergonomic.address.Shipping} */
							value: shippingAddressIsTheSame
						}
					)
				;

				return this;
			}



			,
			/**
			 * @public
			 * @returns {df.customer.Address}
			 */
			getAddress: function () {

				if ('undefined' === typeof this._address) {

					/**
					 * @type {df.customer.Address}
					 */
					this._address =
						df.customer.Address
							.construct (
								{
									element: $('#co-billing-form', _this.getElement())
									,
									type: 'billing'
								}
							)
					;
				}

				return this._address;
			}




			,
			/**
			 * @private
			 * @returns {Billing}
			 */
			getBilling: function () {
				return billing;
			}




			,
			/**
			 * @private
			 * @returns {Checkout}
			 */
			getCheckout: function () {
				return checkout;
			}




			,
			/**
			 * @public
			 * @param {Boolean}
			 * @returns {df.checkout.ergonomic.address.Billing}
			 */
			needSave: function (value) {

				if ('undefined' !== typeof value) {
					this._needSave = value;
				}

				return this._needSave;
			}

			,
			/** @type {Boolean} */
			_needSave: false





			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getElement: function () {
				return _config.element;
			}



			,
			/**
			 * @private
			 * @returns {Validation}
			 */
			getValidator: function () {

				if ('undefined' === typeof this._validator) {

					/**
					 * @type {Validation}
					 */
					this._validator =
						new Validation (
							_this.getBilling().form
						)
					;
				}

				return this._validator;
			}



		}; _this.init (); return _this; }


	};





})(jQuery);(function ($) {

	df.namespace ('df.checkout.ergonomic.address');


	df.checkout.ergonomic.address.Shipping = {

		hasNoFields: 'df.checkout.ergonomic.address.Shipping.hasNoFields'

		,
		construct: function (_config) { var _this = {


			init: function () {

				this.getShipping().onComplete = this.onComplete.bindAsEventListener (this);

				/**
				 * Важно вызывать этот метод ранее других
				 */
				this.addFakeRegionFieldsIfNeeded ();

				this.listenForShippingAddressTheSameAsBilling ();

				this.disableShippingAddressTheSameSwitcherIfNeeded();

				this.handleShippingAddressHasNoFields ();

				this.listenForSelection ();




				$(window)
					.bind (
						df.checkout.Ergonomic.interfaceUpdated
						,
						/**
						 * @param {jQuery.Event} event
						 */
						function (event) {

							if (
									_this.needSave ()
								||
									(
											('billingAddress' === event.updateType)
										&&
											_this.hasNoFields ()
									)
							) {
								_this.save ();
							}

						}
					)
				;

			}



			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			save: function () {

				this.needSave (false);


				var $regionAsText = this.getAddress().getFieldRegionText().getElement();

				var $regionAsSelect = this.getAddress().getFieldRegionSelect().getElement();


				var regionAsText = $regionAsText.get(0);

				var regionAsSelect = $regionAsSelect.get(0);

				if (regionAsText && regionAsSelect) {

					if ('none' === regionAsText.style.display) {

						regionAsText.value = $('option:selected', $regionAsSelect).text();

					}
				}


				this.getShipping().save();

				return this;
			}



			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			addFakeRegionFieldsIfNeeded: function () {

				df.checkout.ergonomic.helperSingleton.addFakeInputIfNeeded ('shipping:region');
				df.checkout.ergonomic.helperSingleton.addFakeInputIfNeeded ('shipping:region_id');

				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			disableShippingAddressTheSameSwitcherIfNeeded: function () {

				/**
				 * Если форма адреса доставки содержит обязательное для заполнения поле,
				 * которое в то же время не является обязательным для заполнения в адресе плательщика,
				 * то переключатель "Доставить на этот адрес" / "Доставить по другому адресу"
				 * надо скрыть и сразу выбрать значение "Доставить по другому адресу".
				 */

				/** @type {Boolean} */
				var needDisableSwitcher = false;

				this.getAddress().getFields()
					.each (
						function () {

							/** @type {df.customer.address.Field} */
							var shippingField =
								df.customer.address.Field
									.construct ({
										element: $(this)
									})
							;

							if (shippingField.isRequired()) {

								/** @type {df.customer.address.Field} */
								var billingField =
									df.checkout.ergonomic.billingAddressSingleton.getAddress().getField (
										shippingField.getShortName()
									)
								;

								if (!billingField.isExist() || !billingField.isRequired()) {
									needDisableSwitcher = true;
									return false;
								}
							}
						}
					)
				;

				if (needDisableSwitcher) {

					_this.handleShippingAddressTheSameAsBilling (false);

					$(
						df.checkout.ergonomic.billingAddressSingleton.getAddress()
							.getField ('use_for_shipping').getElement()
					)
						.closest ('li.control')
							.hide ()
					;


					$('#billing\\:use_for_shipping_yes')
						.removeAttr ('checked')
					;
					$('#billing\\:use_for_shipping_no')
						.attr ('checked', 'checked')
					;
					$('#shipping\\:same_as_billing').val(0);
					$('#shipping\\:same_as_billing').get(0).checked = false;
				}

				return this;
			}





			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			listenForSelection: function () {

				_this.getAddress().getFields()
					.change (
						function () {

							_this.handleSelection();

						}
					)
				;

				if (document.getElementById ('shipping-address-select')) {
					this.handleSelection ();
				}

				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			handleSelection: function () {

				this.getValidator().dfValidateFilledFieldsOnly();

				if (this.getValidator().dfValidateSilent()) {

					if (false === this.getCheckout().loadWaiting) {
						this.save ();
					}
					else {
						/**
						 * Вызывать save() пока бесполезно, потому что система занята.
						 * Поэтому вместо прямого вызова save планируем этот вызов на будущее.
						 */
						this.needSave (true);
					}

				}
				return this;
			}





			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			handleShippingAddressHasNoFields: function () {

				/**
				 * Один невидимый элемента у нас всегда есть: shipping:address_id
				 */
				if (this.hasNoFields ()) {

					this.getElement().hide();

					$(window)
						.trigger (
							{
								/** @type {String} */
								type: df.checkout.ergonomic.address.Shipping.hasNoFields

							}
						)
					;
				}

				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			listenForShippingAddressTheSameAsBilling: function () {

				$(window)
					.bind (
						df.checkout.ergonomic.address.Billing.shippingAddressIsTheSame
						,
						/**
						 * @param {jQuery.Event} event
						 */
						function (event) {
							_this.handleShippingAddressTheSameAsBilling (event.value);
						}
					)
				;

				/**
				 * Явно вызываем метод handleShippingAddressTheSameAsBilling в первый раз,
				 * потому что df.checkout.ergonomic.address.Billing инициализируется до
				 * df.checkout.ergonomic.address.Shipping, и первое оповещение от
				 * df.checkout.ergonomic.address.Billing не доходит до
				 * df.checkout.ergonomic.address.Shipping.
				 */
				_this.handleShippingAddressTheSameAsBilling (
					document.getElementById ('billing:use_for_shipping_yes').checked
				);

				return this;
			}




			,
			/**
			 * @public
			 * @param {Boolean} value
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			handleShippingAddressTheSameAsBilling: function (value) {

				_this.getShipping().setSameAsBilling (value);

				_this.getElement().toggle (!value);


				return this;
			}




			,
			/**
			 * @public
			 * @param {Object} transport
			 * @returns {df.checkout.ergonomic.address.Shipping}
			 */
			onComplete: function (transport) {

				this.getShipping().resetLoadWaiting (transport);

				$(window)
					.trigger (
						{
							/** @type {String} */
							type: df.checkout.Ergonomic.interfaceUpdated

							,
							/** @type {String} */
							updateType: 'shippingAddress'
						}
					)
				;

				return this;
			}




			,
			/**
			 * @public
			 * @returns {Boolean}
			 */
			hasNoFields: function () {

				if ('undefined' === typeof this._hasNoFields) {

					/** @type {jQuery} HTMLInputElement */
					var $fields = $('#shipping-new-address-form fieldset :input', _this.getElement());

					/**
					 * @type {Boolean}
					 */
					this._hasNoFields = (2 > $fields.size ());
				}

				return this._hasNoFields;
			}




			,
			/**
			 * @public
			 * @returns {df.customer.Address}
			 */
			getAddress: function () {

				if ('undefined' === typeof this._address) {

					/**
					 * @type {df.customer.Address}
					 */
					this._address =
						df.customer.Address
							.construct (
								{
									element: $('#co-shipping-form', _this.getElement())
									,
									type: 'shipping'
								}
							)
					;
				}

				return this._address;
			}



			,
			/**
			 * @private
			 * @returns {Checkout}
			 */
			getCheckout: function () {
				return checkout;
			}




			,
			/**
			 * @public
			 * @param {Boolean}
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			needSave: function (value) {

				if ('undefined' !== typeof value) {
					this._needSave = value;
				}

				return this._needSave;
			}

			,
			/** @type {Boolean} */
			_needSave: false





			,
			/**
			 * @private
			 * @returns {Shipping}
			 */
			getShipping: function () {
				return shipping;
			}



			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getElement: function () {
				return _config.element;
			}



			,
			/**
			 * @private
			 * @returns {Validation}
			 */
			getValidator: function () {

				if ('undefined' === typeof this._validator) {

					/**
					 * @type {Validation}
					 */
					this._validator =
						new Validation (
							_this.getShipping().form
						)
					;
				}

				return this._validator;
			}



		}; _this.init (); return _this; }


	};





})(jQuery);(function ($) {

	df.namespace ('df.checkout.ergonomic.method');


	df.checkout.ergonomic.method.Payment = {

		construct: function (_config) { var _this = {


			init: function () {

				this.getPaymentMethod().onComplete = this.onComplete.bindAsEventListener (this);

				this.handleSingleMethodCase ();

				this.handleSelection ();

			}



			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			handleSingleMethodCase: function () {

				/**
				 * @function
				 */
				var checker = function () {
					if (1 === $('input[name=payment\\[method\\]]', _this.getElement()).size ()) {

						_this.save ();

					}
				};


				$(window)
					.bind (
						df.checkout.Ergonomic.interfaceUpdated
						,
						/**
						 * @param {jQuery.Event} event
						 */
						function (event) {

							if (
								/**
								 *  Данное ограничение — не просто прихоть ради ускорения.
								 *  Без этого ограничения система зависнет,
								 *  потому что система постоянно будет выполнять метод save/
								 *  Причём возможны два вида бесконечных циклов:
								 *  1) прямой (shipping.save(),  shipping.save(), shipping.save())
								 *  2) косвенный (shipping.save(),  billing.save(), shipping.save())
								 */
									-1
								===
									['shippingMethod', 'paymentMethod'].indexOf(event.updateType)
							) {
								checker ();
							}
							else if (_this.needSave()) {
								_this.save ();
							}

						}
					)
				;

				checker ();

				return this;
			}



			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.method.Payment}
			 */
			handleSelection: function () {

				$(document.getElementById (_this.getPaymentMethod().form))
					.change (
						function () {
							if (false === _this.getCheckout().loadWaiting) {
								_this.save ();
							}
							else {
								/**
								 * Вызывать save() пока бесполезно, потому что система занята.
								 * Поэтому вместо прямого вызова save планируем этот вызов на будущее.
								 */
								_this.needSave (true);
							}
						}
					)
				;

				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.method.Payment}
			 */
			save: function () {

				this.getValidator().dfValidateFilledFieldsOnly();

				if (this.getValidator().dfValidateSilent()) {
					this.needSave (false);
					this.getPaymentMethod().save();
				}

				return this;
			}



			,
			/**
			 * @public
			 * @param {Object} transport
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			onComplete: function (transport) {

				this.getPaymentMethod().resetLoadWaiting (transport);


				if (response.df_update_sections) {

					$.each (response.df_update_sections, function () {
						$('#checkout-'+this.name+'-load').html (this.html);

						$(window)
							.trigger (
								{
									/** @type {String} */
									type: df.checkout.Ergonomic.sectionUpdated

									,
									/** @type {String} */
									section: this.name
								}
							)
						;
					});

				}


				if (response.update_section) {
					$(window)
						.trigger (
							{
								/** @type {String} */
								type: df.checkout.Ergonomic.sectionUpdated

								,
								/** @type {String} */
								section: response.update_section.name
							}
						)
					;
				}


				$(window)
					.trigger (
						{
							/** @type {String} */
							type: df.checkout.Ergonomic.interfaceUpdated

							,
							/** @type {String} */
							updateType: 'paymentMethod'
						}
					)
				;

				return this;
			}


			,
			/** @function */
			_standardOnSaveHandler: payment.onSave




			,
			/**
			 * @private
			 * @returns {Payment}
			 */
			getPaymentMethod: function () {
				return payment;
			}




			,
			/**
			 * @private
			 * @returns {Checkout}
			 */
			getCheckout: function () {
				return checkout;
			}




			,
			/**
			 * @public
			 * @param {Boolean}
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			needSave: function (value) {

				if ('undefined' !== typeof value) {
					this._needSave = value;
				}

				return this._needSave;
			}

			,
			/** @type {Boolean} */
			_needSave: false




			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getElement: function () {
				return _config.element;
			}


			,
			/**
			 * @private
			 * @returns {Validation}
			 */
			getValidator: function () {

				if ('undefined' === typeof this._validator) {

					/**
					 * @type {Validation}
					 */
					this._validator =
						new Validation (
							_this.getPaymentMethod().form
						)
					;
				}

				return this._validator;
			}


		}; _this.init (); return _this; }


	};





})(jQuery);(function ($) {

	df.namespace ('df.checkout.ergonomic.method');


	df.checkout.ergonomic.method.Shipping = {

		construct: function (_config) { var _this = {


			init: function () {

				this.getShippingMethod().onComplete = this.onComplete.bindAsEventListener (this);

				this.handleSingleMethodCase ();

				this.handleSelection ();

			}



			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			handleSingleMethodCase: function () {

				/**
				 * @function
				 */
				var checker = function () {
					if (1 === $('input[name=shipping_method]', _this.getElement()).size ()) {

						_this.save ();

					}

				};


				$(window)
					.bind (
						df.checkout.Ergonomic.interfaceUpdated
						,
						/**
						 * @param {jQuery.Event} event
						 */
						function (event) {

							if (
								/**
								 *  Данное ограничение — не просто прихоть ради ускорения.
								 *  Без этого ограничения система зависнет,
								 *  потому что система постоянно будет выполнять метод save/
								 *  Причём возможны два вида бесконечных циклов:
								 *  1) прямой (shipping.save(),  shipping.save(), shipping.save())
								 *  2) косвенный (shipping.save(),  billing.save(), shipping.save())
								 */
									-1
								===
									['shippingMethod', 'paymentMethod'].indexOf(event.updateType)
							) {
								checker ();
							}
							else if (_this.needSave()) {
								_this.save ();
							}

						}
					)
				;

				checker ();

				return this;
			}




			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			handleSelection: function () {

				$(document.getElementById (_this.getShippingMethod().form))
					.change (
						function () {
							if (false === _this.getCheckout().loadWaiting) {
								_this.save ();
							}
							else {
								/**
								 * Вызывать save() пока бесполезно, потому что система занята.
								 * Поэтому вместо прямого вызова save планируем этот вызов на будущее.
								 */
								_this.needSave (true);
							}
						}
					)
				;

				return this;
			}






			,
			/**
			 * @public
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			save: function () {

				this.getValidator().dfValidateFilledFieldsOnly();

				if (this.getValidator().dfValidateSilent()) {
					this.needSave (false);
					this.getShippingMethod().save();
				}

				return this;
			}




			,
			/**
			 * @public
			 * @param {Object} transport
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			onComplete: function (transport) {

				this.getShippingMethod().resetLoadWaiting (transport);

				if (response.df_update_sections) {

					$.each (response.df_update_sections, function () {
						$('#checkout-'+this.name+'-load').html (this.html);

						$(window)
							.trigger (
								{
									/** @type {String} */
									type: df.checkout.Ergonomic.sectionUpdated

									,
									/** @type {String} */
									section: this.name
								}
							)
						;
					});

				}


				if (response.update_section) {
					$(window)
						.trigger (
							{
								/** @type {String} */
								type: df.checkout.Ergonomic.sectionUpdated

								,
								/** @type {String} */
								section: response.update_section.name
							}
						)
					;
				}


				$(window)
					.trigger (
						{
							/** @type {String} */
							type: df.checkout.Ergonomic.interfaceUpdated

							,
							/** @type {String} */
							updateType: 'shippingMethod'
						}
					)
				;

				return this;
			}


			,
			/** @function */
			_standardOnSaveHandler: shippingMethod.onSave





			,
			/**
			 * @private
			 * @returns {ShippingMethod}
			 */
			getShippingMethod: function () {
				return shippingMethod;
			}



			,
			/**
			 * @private
			 * @returns {Checkout}
			 */
			getCheckout: function () {
				return checkout;
			}




			,
			/**
			 * @public
			 * @param {Boolean}
			 * @returns {df.checkout.ergonomic.method.Shipping}
			 */
			needSave: function (value) {

				if ('undefined' !== typeof value) {
					this._needSave = value;
				}

				return this._needSave;
			}

			,
			/** @type {Boolean} */
			_needSave: false




			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getElement: function () {
				return _config.element;
			}


			,
			/**
			 * @private
			 * @returns {Validation}
			 */
			getValidator: function () {

				if ('undefined' === typeof this._validator) {

					/**
					 * @type {Validation}
					 */
					this._validator =
						new Validation (
							_this.getShippingMethod().form
						)
					;
				}

				return this._validator;
			}


		}; _this.init (); return _this; }


	};





})(jQuery);(function ($) {

	df.namespace ('df.customer');


	/**
	 * @param {String} type		тип адреса
	 * @param {jQuery} element
	 */
	df.customer.Address = {construct: function (_config) { var _this = {


		init: function () {

			$(function () {

			});

		}



		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement[]
		 */
		getFields: function () {

			if ('undefined' === typeof this._fields) {

				/**
				 * @type {jQuery} HTMLElement[]
				 */
				this._fields =
					$(':input', this.getElement())
						.not (':button')
						.not('[type="hidden"]')
				;
			}

			return this._fields;
		}



		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getFieldCity: function () {
			return this.getField ('city');
		}



		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getFieldCountry: function () {
			return this.getField ('country');
		}



		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getFieldNameFirst: function () {
			return this.getField ('firstname');
		}



		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getFieldNameLast: function () {
			return this.getField ('lastname');
		}



		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getFieldNameMiddle: function () {
			return this.getField ('middlename');
		}



		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getFieldPostalCode: function () {
			return this.getField ('postcode');
		}




		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getFieldRegionSelect: function () {
			return this.getField ('region_id');
		}



		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getFieldRegionText: function () {
			return this.getField ('region');
		}




		,
		/**
		 * @public
		 * @param {String} nameSuffix
		 * @returns {df.customer.address.Field}
		 */
		getField: function (nameSuffix) {

			if ('undefined' == typeof this._field [nameSuffix]) {

				/** @type {df.customer.address.Field} */
				var result =
					df.customer.address.Field
						.construct ({
							element:
								$(
									'[name="%fieldName%"]'
										.replace ('%fieldName%', this.getFieldName(nameSuffix))
									,
									this.getElement ()
								)
						})
				;


				this._field [nameSuffix] = result;

			}

			return this._field [nameSuffix];
		}


		,
		/**
		 * @type {df.customer.address.Field[]}
		 */
		_field: []




		,
		/**
		 * @private
		 * @returns {jQuery} HTMLElement
		 */
		getElement: function () {
			return _config.element;
		}





		,
		/**
		 * @private
		 * @param {String} nameSuffix
		 * @returns {String}
		 */
		getFieldName: function (nameSuffix) {

			/** @type {String} */
			var result = null;

			if (0 === this.getType().length) {
				result = nameSuffix;
			}
			else {

				/** @type ?Array */
				var matches = nameSuffix.match (/(\w+)\[\]/);

				if (null === matches) {
					result =
						'%prefix%[%suffix%]'
							.replace ('%prefix%', this.getType())
							.replace ('%suffix%', nameSuffix)
					;
				}
				else {
					result =
						'%prefix%[%suffix%][]'
							.replace ('%prefix%', this.getType())
							.replace ('%suffix%', matches[1])
					;
				}

			}

			return result;
		}


		,
		/**
		 * @private
		 * @returns {String}
		 */
		getType: function () {
			return _config.type;
		}






	}; _this.init (); return _this; } };


})(jQuery);(function ($) {

	df.namespace ('df.customer.address');


	/**
	 * @param {jQuery} element
	 */
	df.customer.address.Field = {construct: function (_config) { var _this = {


		init: function () {

			$(function () {

			});
		}


		,
		/**
		 * @public
		 * @returns {jQuery} HTMLElement
		 */
		getElement: function () {
			return _config.element;
		}



		,
		/**
		 * @public
		 * @returns {Boolean}
		 */
		isExist: function () {

			if ('undefined' === typeof this._exist) {

				/**
				 * @type {Boolean}
				 */
				this._exist = (0 < this.getElement().size());
			}

			return this._exist;
		}



		,
		/**
		 * @public
		 * @returns {Boolean}
		 */
		isRequired: function () {

			if ('undefined' === typeof this._required) {

				/**
				 * @type {Boolean}
				 */
				this._required =
					0 < $('label.required', this.getElement().closest ('.field')).size()
				;
			}

			return this._required;
		}



		,
		/**
		 * @public
		 * @returns {String}
		 */
		getShortName: function () {

			if ('undefined' === typeof this._shortName) {

				/**
				 * @type {String}
				 */
				this._shortName = this.getElement().attr('name')
					.replace (/\w+\[(\w+)\]/, '$1')
				;
			}

			return this._shortName;
		}




	}; _this.init (); return _this; } };


})(jQuery);function show_hide_checkbox_fields(objName,condition)
{
	document.getElementById(objName).disabled = condition==1?"disabled":"";
}
(function () {


	/**
	 * Наша задача: выделить в корзине товары-подарки.
	 */
	document.observe("dom:loaded", function() {

		if (
				window.df
			&&
				window.df.promo_gift
			&&
				window.df.promo_gift.giftingQuoteItems
		) {
			var giftingQuoteItems = window.df.promo_gift.giftingQuoteItems;

			if (giftingQuoteItems instanceof Array) {

				/**
				 * Итак, надо найти в корзине строки заказа giftingQuoteItems и выделить их.
				 */

				var $quoteItems = $$ ("#shopping-cart-table a.btn-remove");

				if (1 > $quoteItems.length) {
					$quoteItems = $$ ("#shopping-cart-table a.btn-remove2");
				}

				$quoteItems.each (function (item) {

					var url = item.href;

					if (df_validate_string (url)) {

						var quoteItemIdExp = /id\/(\d+)\//;
						var matches = url.match (quoteItemIdExp);

						if (matches instanceof Array) {


							if (1 < matches.length) {

								var quoteItemId = parseInt (matches [1]);

								if (!isNaN (quoteItemId)) {


									/**
									 * Нашли идентификатор текущего товара в корзине
									 */

									if (-1 < giftingQuoteItems.indexOf (quoteItemId)) {

										/**
										 * Эта строка заказа — подарок. Выделяем её
										 */

										var $tr = $(item).up ('tr');

										if ($tr) {
											$tr.addClassName("df-free-quote-item");
										}


										/**
										 * Подписываем подарок
										 */
										var elements = $tr.select ('.product-name');
										if (0 < elements.length) {

											var $productName = $(elements [0]);


											var $giftLabel = new Element ('div');
											$giftLabel.addClassName ('df-gift-label');
											$giftLabel
												.update (
													window.df.promo_gift.giftingQuoteItemTitle
												)
											;

											$productName
												.insert ({
													'after': $giftLabel
												})
											;
										}


									}

								}

							}
						}
					}
				});
			}
		}


	});

})();(function () {


	/**
	 * Наша задача: выделить в корзине товары-подарки.
	 */
	document.observe("dom:loaded", function() {

		if (
				window.df
			&&
				window.df.promo_gift
			&&
				window.df.promo_gift.eligibleProductIds
		) {
			var eligibleProductIds = window.df.promo_gift.eligibleProductIds;

			if (eligibleProductIds instanceof Array) {

				/**
				 * Итак, если покупатель смотрит карточку товара,
				 * и данный товар он вправе получить в подарок (выполнил условия акции),
				 * то надо внешне отразить сей факт на карточке товара
				 */

				var $addToCartForm = $('product_addtocart_form');

				if ($addToCartForm) {

					var productIdInputs = $addToCartForm.select ("input[name='product']");

					if (
							productIdInputs
						&&
							(productIdInputs instanceof Array)
						&&
							(0 < productIdInputs.length)
					) {

						var productId = parseInt ($(productIdInputs [0]).getValue ());

						if (-1 < eligibleProductIds.indexOf(productId)) {

							$addToCartForm.up ('.product-view').addClassName ('df-gift-product');



							var labelText = window.df.promo_gift.eligibleProductLabel;

							if (df_validate_string (labelText)) {

								var $giftLabel = new Element ('div');
								$giftLabel.addClassName ('df-gift-label');
								$giftLabel
									.update (
										labelText
									)
								;

								var priceBoxes = $addToCartForm.select ('.price-box');
								if (
										priceBoxes
									&&
										(priceBoxes instanceof Array)
									&&
										(0 < priceBoxes.length)
								) {
									var $priceBox = $(priceBoxes[0]);

									$priceBox
										.insert ({
											'after': $giftLabel
										})
									;
								}

							}

						}
					}
				}
			}
		}


	});

})();(function () {

	/**
	 * Наша задача:
	 * 		[*]	назначить чётным подаркам класс df-even
	 * 		[*] назначить нечётным подаркам класс df-odd
	 * 		[*] назначить первому подарку класс df-first
	 * 		[*] назначить последнему подарку класс df-last
	 */
	document.observe("dom:loaded", function() {

		var odd = true;

		var $products = $$ (".df-promo-gift .df-gift-chooser .df-side li.df-product");


		/**
		 * Обратите внимание, что $products.first() может вернуть undefined,
		 * если массив $products пуст.
		 * Аналогично и $products.last().
		 *
		 * @link http://www.prototypejs.org/api/array/first
		 */
		if ($products.first ()) {
			$products.first ().addClassName ('df-first');
		}

		if ($products.last ()) {
			$products.last ().addClassName ('df-last');
		}

		$products
			.each (
				function (product) {
					$(product).addClassName (odd ? 'df-odd' : 'df-even');
					odd = !odd;
				}
			)
		;



	});

})();(function ($) {

	$(function () {


		/** @type {HTMLElement} */
		var $container = $('.df-shipping-torg12');

		/** @type {Number} */
		var $containerWidthInMm = 275.4;


		/** @type {Number} */
		var dotsInMm = $container.width () / $containerWidthInMm;


		/** @type {Number} */
		var mmsInDot = $containerWidthInMm / $container.width ();


		/** @type {Number} */
		var pageHeightInMm = 210;

		var currentPageOrdering = 1;



		(function () {

			/** @type {Array} */
			var engines = ['webkit', 'opera', 'msie', 'mozilla'];


			$.each (engines, function (index, engine) {

				if ($.browser[engine]) {
					$container
						.addClass ('df-shipping-torg12-' + engine)
					;
				}

			});

		})();




		(function () {

			/** @type {jQuery} HTMLTableRowElement[] */
			var $rows = $('.df-orderItems tbody tr.df-orderItem');

			$rows.first().addClass ('df-first');

			$rows.last().addClass ('df-last');

		})();



		(function () {


			var $row3Input =
				$('.df-heading-left .df-row-3 .df-input .df-text', $container)
			;

			var $row5Input =
				$('.df-heading-left .df-row-5 .df-input .df-text', $container)
			;


			//$row3Input.alignBottom ();

			//$row5Input.alignBottom ();

		})();



//		(function () {
//
//			/** @type {jQuery} HTMLTableRowElement[] */
//			var $rows = $('.df-orderItems tbody tr.df-orderItem');
//
//			/** @type {?jQuery} HTMLTableRowElement */
//			var $prevRow = null;
//
//			$rows.each (function () {
//
//				/** @type {jQuery} HTMLTableRowElement */
//				var $row = $(this);
//
//				/** @type {Number} */
//				var rowBottom = $row.offset().top + $row.outerHeight(false);
//
//
//				if (rowBottom > pageHeightInMm * dotsInMm * currentPageOrdering) {
//
//					$row.addClass ('df-breakBefore');
//
//					$row.addClass ('df-firstRowOnPage');
//
//					if ($prevRow) {
//						$prevRow.addClass ('df-lastRowOnPage');
//					}
//
//					currentPageOrdering++;
//				}
//
//				$prevRow = $row;
//
//			});
//
//		})();


	});

})(jQuery);(function ($) { $(function () {


	(function () {

		/** @type {jQuery} HTMLAnchorElement */
		var $reviewLinks = $('.product-view .ratings .rating-links a');

		$reviewLinks.first().addClass ('.first-child');

		$reviewLinks.last().addClass ('.last-child');

	})();



}); })(jQuery);(function ($) {

	df.namespace ('df.vk');


	df.vk.Widget = {

		construct: function (_config) { var _this = {


			init: function () {

				/** @type {jQuery} HTMLElement */
				var $parent = $(this.getParentSelector());

				if (0 < $parent.size()) {


					$parent
						.append (
							$('<div></div>')
								.attr ('id', this.getContainerId())
						)
					;

					if ('undefined' !== typeof VK) {
						_this.createWidget();
					}
					else {
						$
							.getScript (
								'http://userapi.com/js/api/openapi.js'
								,
								function () {
									VK.init(
										{
											apiId: _this.getApplicationId()
											,
											onlyWidgets: true
										}
									);
									_this.createWidget();
								}
							)
						;

					}

				}

			}


			,
			/**
			 * @private
			 * @returns {df.vk.Widget }
			 */
			createWidget: function () {

				/**
				 *  Надо вызвать конструктор типа VK.Widgets.Comments
				 *  по его текстовой записи: "VK.Widgets.Comments"
				 */


				var dotParser = function (object, index) {

					var result = object[index];

					if ('undefined' === typeof result) {
						console.log ('Index %index is undefined'.replace ('%index', index));
					}

					return result;
				};

				var constructor =
					/**
					 *  Не используем Array.prototype.reduce из JavaScript 1.8,
					 *  потому что в Magento 1.4.1.0 этот метод конфликтует
					 *  с одноимённым методом библиотеки Prototype.
					 */
					df.reduce (
						this.getObjectName().split('.')
						,
						dotParser
						,
						window
					)

				;

				constructor.call (window, this.getContainerId(), this.getWidgetSettings());

				return this;
			}



			,
			/**
			 * @private
			 * @returns {Number}
			 */
			getApplicationId: function () {
				return _config.applicationId;
			}


			,
			/**
			 * @private
			 * @returns {String}
			 */
			getContainerId: function () {
				return _config.containerId;
			}


			,
			/**
			 * @private
			 * @returns {String}
			 */
			getObjectName: function () {
				return _config.objectName;
			}


			,
			/**
			 * @private
			 * @returns {String}
			 */
			getParentSelector: function () {
				return _config.parentSelector;
			}


			,
			/**
			 * @private
			 * @returns {Object}
			 */
			getWidgetSettings: function () {
				return _config.widgetSettings;
			}




		}; _this.init (); return _this; }


	};





})(jQuery);/**
 * Программный код,
 * который надо выполнить сразу после загрузки страницы
 */

(function ($) { $(function () {

	df.namespace('df.vk.comments');

	if (df.vk.comments.enabled) {
		df.vk.Widget
			.construct (
				{
					applicationId: df.vk.comments.applicationId
					,
					containerId: 'vk_comments'
					,
					objectName: 'VK.Widgets.Comments'
					,
					parentSelector: '.product-view'
					,
					widgetSettings: df.vk.comments.settings
				}
			)
		;
	}


	df.namespace('df.vk.like');

	if (df.vk.like.enabled) {
		df.vk.Widget
			.construct (
				{
					applicationId: df.vk.like.applicationId
					,
					containerId: 'vk_like'
					,
					objectName: 'VK.Widgets.Like'
					,
					parentSelector: '.product-shop'
					,
					widgetSettings: df.vk.like.settings
				}
			)
		;
	}



	df.namespace('df.vk.groups');

	if (df.vk.groups.enabled) {
		df.vk.widget.Groups
			.construct (
				{
					applicationId: df.vk.groups.applicationId
					,
					containerId: 'vk_groups'
					,
					objectName: 'VK.Widgets.Group'
					,
					widgetSettings: df.vk.groups.settings
				}
			)
		;
	}


}); })(jQuery);(function ($) {

	df.namespace ('df.vk.widget');


	df.vk.widget.Groups = {

		construct: function (_config) { var _this = {


			init: function () {


				if (0 < this.getParent().size()) {

					/** @type {jQuery} HTMLElement[] */
					var $blocks = $('.block', this.getParent());


					/** @type {Number} */
					var childrenCount = $blocks.size();



					/** @type {Number} */
					var insertionIndex =
						Math.max (
							0
							,
							Math.min (
								childrenCount - 1
								,
								/**
								 * Вычитает единицу,
								 * потому что в административном интерфейсе
								 * нумерация начинается с 1
								 */
								df.vk.groups.verticalOrdering - 1
							)
						)
					;


					/** @type {jQuery} HTMLElement */
					var $widget =
						$('<div></div>')
							.attr ('id', this.getContainerId())
							.addClass ('block')
					;

					if (0 === insertionIndex) {
						this.getParent().prepend ($widget);
					}
					else {
						$($blocks.get(insertionIndex)).before($widget);
					}



					if ('undefined' !== typeof VK) {
						_this.createWidget();
					}
					else {
						$
							.getScript (
								'http://userapi.com/js/api/openapi.js'
								,
								function () {
									_this.createWidget();
								}
							)
						;

					}

				}

			}


			,
			/**
			 * @private
			 * @returns {df.vk.Widget }
			 */
			createWidget: function () {

				/**
				 *  Надо вызвать конструктор типа VK.Widgets.Comments
				 *  по его текстовой записи: "VK.Widgets.Comments"
				 */


				var dotParser = function (object, index) {

					var result = object[index];

					if ('undefined' === typeof result) {
						console.log ('Index %index is undefined'.replace ('%index', index));
					}

					return result;
				};


				var constructor =
					/**
					 *  Не используем Array.prototype.reduce из JavaScript 1.8,
					 *  потому что в Magento 1.4.1.0 этот метод конфликтует
					 *  с одноимённым методом библиотеки Prototype.
					 */
					df.reduce (
						this.getObjectName().split('.')
						,
						dotParser
						,
						window
					)

				;


				constructor
					.call (
						window
						,
						this.getContainerId()
						,
						this.getWidgetSettings()
						,
						_this.getApplicationId()
					)
				;

				return this;
			}



			,
			/**
			 * @private
			 * @returns {Number}
			 */
			getApplicationId: function () {
				return _config.applicationId;
			}


			,
			/**
			 * @private
			 * @returns {String}
			 */
			getContainerId: function () {
				return _config.containerId;
			}


			,
			/**
			 * @private
			 * @returns {String}
			 */
			getObjectName: function () {
				return _config.objectName;
			}


			,
			/**
			 * @private
			 * @returns {jQuery} HTMLElement
			 */
			getParent: function () {

				if ('undefined' === typeof this._parent) {

					/** @type {String} */
					var selector =
							('left' === df.vk.groups.position)
						?
							'.col-left'
						:
							'.col-right'
					;

					/**
					 * @type {jQuery} HTMLElement
					 */
					this._parent = $(selector);

					if (0 === this._parent.size()) {

						if (
								0
							<
								(
										$('.col2-right-layout').size()
									+
										$('.col2-left-layout').size()
								)
						) {
							this._parent = $('.col-main');
						}
					}
				}

				return this._parent;
			}


			,
			/**
			 * @private
			 * @returns {Object}
			 */
			getWidgetSettings: function () {
				return _config.widgetSettings;
			}




		}; _this.init (); return _this; }


	};





})(jQuery);if ('undefined' !== typeof RegionUpdater) {
	RegionUpdater.prototype.update =
		function () {
			if (this.regions[this.countryEl.value]) {
				var i, option, region, def;

				var defaultRegionId = this.regionSelectEl.getAttribute('defaultValue');

				if (this.regionTextEl) {
					def = this.regionTextEl.value.toLowerCase();
					this.regionTextEl.value = '';
				}
				if (!def) {
					def = defaultRegionId;
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

					if (
							(regionId == defaultRegionId)
						||
							(region.name.toLowerCase()==def)
						||
							(region.code.toLowerCase()==def)
					) {
						this.regionSelectEl.value = regionId;
					}
				}

				if (this.disableAction=='hide') {
					if (this.regionTextEl) {
						this.regionTextEl.style.display = 'none';
					}

					this.regionSelectEl.style.display = '';
				} else if (this.disableAction=='disable') {
					if (this.regionTextEl) {
						this.regionTextEl.disabled = true;
					}
					this.regionSelectEl.disabled = false;
				}
				this.setMarkDisplay(this.regionSelectEl, true);
			} else {
				if (this.disableAction=='hide') {
					if (this.regionTextEl) {
						this.regionTextEl.style.display = '';
					}
					this.regionSelectEl.style.display = 'none';
					Validation.reset(this.regionSelectEl);
				} else if (this.disableAction=='disable') {
					if (this.regionTextEl) {
						this.regionTextEl.disabled = false;
					}
					this.regionSelectEl.disabled = true;
				} else if (this.disableAction=='nullify') {
					this.regionSelectEl.options.length = 1;
					this.regionSelectEl.value = '';
					this.regionSelectEl.selectedIndex = 0;
					this.lastCountryId = '';
				}
				this.setMarkDisplay(this.regionSelectEl, false);
			}

			// Make Zip and its label required/optional
			var zipUpdater = new ZipUpdater(this.countryEl.value, this.zipEl);
			zipUpdater.update();
		}
	;
}