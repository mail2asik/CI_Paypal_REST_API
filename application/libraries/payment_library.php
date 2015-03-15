<?php
class Payment_library
{
	/**
	 * Set CI instance to this class constructor
	 *
	 */
	public function __construct()
	{
		if (!isset($this->CI))
		{
			$this->CI =& get_instance();
		}
	}

	/**
	 * Make paypal Payment via REST API
	 *
	 */
	public function make_paypal_payment($price, $currencyType, $payment_desc, $card_type, $card_holder_name, $card_number, $expire_month, $expire_year, $cvv)
	{		
		$price = (float)$price;
		$card_type = strtolower($card_type);
		
		// ### CreditCard
		// A resource representing a credit card that can be
		// used to fund a payment.
		$card = new PayPal\Api\CreditCard();
		$card->setType($card_type)
			->setNumber($card_number)
			->setExpireMonth("11")
			->setExpireYear($expire_year)
			->setCvv2($cvv)
			->setFirstName($card_holder_name);
			//->setLastName("Shopper");

		// ### FundingInstrument
		// A resource representing a Payer's funding instrument.
		// For direct credit card payments, set the CreditCard
		// field on this object.
		$fi = new PayPal\Api\FundingInstrument();
		$fi->setCreditCard($card);

		// ### Payer
		// A resource representing a Payer that funds a payment
		// For direct credit card payments, set payment method
		// to 'credit_card' and add an array of funding instruments.
		$payer = new PayPal\Api\Payer();
		$payer->setPaymentMethod("credit_card")
			->setFundingInstruments(array($fi));


		// ### Amount
		// Lets you specify a payment amount.
		// You can also specify additional details
		// such as shipping, tax.
		$amount = new PayPal\Api\Amount();
		$amount->setCurrency($currencyType)
			->setTotal($price);
			//->setDetails($details);

		// ### Transaction
		// A transaction defines the contract of a
		// payment - what is the payment for and who
		// is fulfilling it. 
		$transaction = new PayPal\Api\Transaction();
		$transaction->setAmount($amount)
			//->setItemList($itemList)
			->setDescription($payment_desc)
			->setInvoiceNumber(uniqid());

		// ### Payment
		// A Payment Resource; create one using
		// the above types and intent set to sale 'sale'
		$payment = new PayPal\Api\Payment();
		$payment->setIntent("sale")
			->setPayer($payer)
			->setTransactions(array($transaction));

		// For Sample Purposes Only.
		$request = clone $payment;

		// ### Create Payment
		// Create a payment by calling the payment->create() method
		// with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
		// The return object contains the state.
		try {
			$clientId = $this->config->item('clientId');
			$clientSecret = $this->config->item('clientSecret');
			$apiContext = getApiContext($clientId, $clientSecret);
			$payment->create($apiContext);
		} catch (Exception $ex) {
			$this->session->set_flashdata('error_message', 'Something is going wrong! please try again later!!');
			redirect('', 'refresh');
			//ResultPrinter::printError('Create Payment Using Credit Card. If 500 Exception, try creating a new Credit Card using <a href="https://ppmts.custhelp.com/app/answers/detail/a_id/750">Step 4, on this link</a>, and using it.', 'Payment', null, $request, $ex);
			//exit(1);
		}

		//ResultPrinter::printResult('Create Payment Using Credit Card', 'Payment', $payment->getId(), $request, $payment);

		return $payment;
	}
}

/* End of file Payment_library.php */
/* Location: ./application/librarie/payment_library.php */