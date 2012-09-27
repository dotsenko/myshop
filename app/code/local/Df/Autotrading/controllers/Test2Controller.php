<?php

class Df_Autotrading_Test2Controller extends Mage_Core_Controller_Front_Action {


	/**
	 * @return void
	 */
    public function indexAction() {

		try {

			/** @var Df_Autotrading_Model_Request_Rate $request  */
			$request =
				df_model (
					Df_Autotrading_Model_Request_Rate::getNameInMagentoFormat()
				)
			;

			df_assert ($request instanceof Df_Autotrading_Model_Request_Rate);


			/** @var phpQueryObject $pqResultCell  */
			$pqResultCell = df_pq ('.calculator .calc_result ul li.inner .col2', $request->getResponseAsPq());

			df_assert ($pqResultCell instanceof phpQueryObject);


			/** @var string $resultAsText  */
			$resultAsText = df_trim ($pqResultCell->text());

			df_assert_string ($resultAsText);



			/** @var string $pattern  */
			$pattern = '#([\d\s,]+)#u';


			/** @var array $matches  */
			$matches = array ();


			/** @var bool|int $r  */
			$r = preg_match ($pattern, $resultAsText, $matches);

			df_assert (1 === $r);


			/** @var string $costFormatted  */
			$costFormatted = df_a ($matches, 1);

			df_assert_string ($costFormatted);



			/**
			 * Обратите внимание,
			 * что $costFormatted теперь содержит строку вида «6 657,4»,
			 * причём пробел между цифрами — необычный, там символ Unicode.
			 * Чтобы его устранить, используем preg_replace
			 */

			/** @var string $costFormattedRegularly  */
			$costFormattedRegularly =
				strtr (
					preg_replace ('#\s#u', '', $costFormatted)
					,
					array (
						',' => '.'
						,
						' ' => ''
					)
				)
			;

			df_assert_string ($costFormattedRegularly);


			/** @var float $cost  */
			$cost =
				floatval (
					$costFormattedRegularly
				)
			;


			$this
				->getResponse()
				->setBody (
					$cost
				)
			;

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e, false);
			echo $e->getMessage();
		}

    }




}


