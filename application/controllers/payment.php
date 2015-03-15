<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends CI_Controller {

	/**
	 * Index Page for this controller which is default page for this project
	 *
	 */
	public function index()
	{
		$this->load->library(array('form_validation', 'payment_library'));
		$this->load->helper(array('payment_helper'));
		$this->load->model(array('payment_model'));
		
		$this->form_validation->set_rules('customerName', 'Customer Name', 'trim|required|min_length[3]|max_length[15]');
		$this->form_validation->set_rules('price', 'Price', 'trim|required|numeric');
		$this->form_validation->set_rules('currencyType', 'Currency Type', 'trim|required');
		$this->form_validation->set_rules('cardHolderName', 'Name on card', 'trim|required');
		$this->form_validation->set_rules('cardNumber', 'Card Number', 'trim|required|callback_validateCreditcard_number');
		$this->form_validation->set_rules('expireMonth', 'Month', 'trim|required||callback_validateCreditCardExpirationDate');
		$this->form_validation->set_rules('expireYear', 'Expire Year', 'trim|required');
		$this->form_validation->set_rules('cvv', 'CVV', 'trim|required|callback_validateCVV');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == TRUE)
		{
			//Get input
			$customerName = $this->input->post('customerName');
			$price = $this->input->post('price');
			$currencyType = $this->input->post('currencyType');
			$cardHolderName = $this->input->post('cardHolderName');
			$cardNumber = $this->input->post('cardNumber');
			$expireMonth = $this->input->post('expireMonth');
			$expireYear = $this->input->post('expireYear');
			$cvv = $this->input->post('cvv');			
			
			//Find card type based on card number
			$cardType = get_type_of_credit_card($cardNumber);
			
			//if currency is not USD and credit card is AMEX, return error message
			if($cardType == 'American Express' && $currencyType != 'USD')
			{
				$this->session->set_flashdata('error_message', 'AMEX is possible to use only for USD.');
				redirect('', 'refresh');
			}
			
			//if credit card type is AMEX, then use Paypal.
			//if currency is USD, EUR, or AUD, then use Paypal. Otherwise use Braintree.
			if($cardType == 'American Express' || $currencyType == 'USD' || $currencyType == 'EUR' || $currencyType == 'AUD'){
				$payment = $this->payment_library->make_paypal_payment($price, $currencyType, "For Room Booking", $cardType, $cardHolderName, $cardNumber, $expireMonth, $expireYear, $cvv);
				
				//record order history in DB
				$payment_method = 'paypal';
				$payment_id = $payment->getId();
				$this->payment_model->record_order_history($customerName, $price, $currencyType, $payment_method, $payment_id);
				
				//Todo: record entire payment history in seperate table 
				
				$this->session->set_flashdata('success_message', 'Great! your transaction has been successfully recorded!!');
				redirect('', 'refresh');
			}else{
			
				$this->session->set_flashdata('error_message', 'Under construction for this payment method. We are working with Braintree.');
				redirect('', 'refresh');
			}
		}
			
		$this->load->view('payment');
	}
	
	/**
	 * Validate the credit card number
	 *
	 */
	public function validateCreditcard_number($cc_num)		
	{
		$credit_card_number = $this->sanitize($cc_num);
		// Get the first digit
		$data = array();
		$firstnumber = substr($credit_card_number, 0, 1);
		// Make sure it is the correct amount of digits. Account for dashes being present.
		switch ($firstnumber)
		{
			case 3:
				$data['card_type'] ="American Express";
				if (!preg_match('/^3\d{3}[ \-]?\d{6}[ \-]?\d{5}$/', $credit_card_number))
				{
					$this->form_validation->set_message("validateCreditcard_number", "This is not a valid American Express card number");
					return false;
				}
				break;
			case 4:
				$data['card_type'] ="Visa";
				if (!preg_match('/^4\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
				{
					$this->form_validation->set_message("validateCreditcard_number", "This is not a valid Visa card number"); 
					return false;
				}
				break;
			case 5:
				$data['card_type'] ="MasterCard";
				if (!preg_match('/^5\d{3}[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
				{
					$this->form_validation->set_message("validateCreditcard_number", "This is not a valid MasterCard card number");
					return false;
				}
				break;
			case 6:
				$data['card_type'] ="Discover";
				if (!preg_match('/^6011[ \-]?\d{4}[ \-]?\d{4}[ \-]?\d{4}$/', $credit_card_number))
				{
					$this->form_validation->set_message("validateCreditcard_number", "This is not a valid Discover card number");
					return false;
				}
				break;
			default:
				$this->form_validation->set_message("validateCreditcard_number", "This is not a valid credit card number");
				return false;
		}
		// Here's where we use the Luhn Algorithm
		$credit_card_number = str_replace('-', '', $credit_card_number);
		$map = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,0, 2, 4, 6, 8, 1, 3, 5, 7, 9);
		$sum = 0;
		$last = strlen($credit_card_number) - 1;
		for ($i = 0; $i <= $last; $i++)
		{
			$sum += $map[$credit_card_number[$last - $i] + ($i & 1) * 10];
		}
		if ($sum % 10 != 0)
		{
			$this->form_validation->set_message("validateCreditcard_number", "This is not a valid credit card number");
			return false;
		}
		// If we made it this far the credit card number is in a valid format
		return true;
	}
	
	/**
	 * Validate the credit card expiration month & year
	 *
	 */
	public function validateCreditCardExpirationDate($mon)
	{
		$month = $this->sanitize($mon);
		$yr = $_POST["expireYear"];
		$year = $this->sanitize($yr);
		if (!preg_match('/^\d{1,2}$/', $month))
		{
			$this->form_validation->set_message("validateCreditCardExpirationDate", "The month isn't a one or two digit number");
			return false;
		}
		else if (!preg_match('/^\d{4}$/', $year))
		{
			$this->form_validation->set_message("validateCreditCardExpirationDate", "The year isn't four digits long");
			return false;
		}
		else if ($year < date("Y"))
		{
			$this->form_validation->set_message("validateCreditCardExpirationDate", "The card is already expired");
			return false;
		}
		else if ($month < date("m") && $year == date("Y"))
		{
			$this->form_validation->set_message("validateCreditCardExpirationDate", "The card is already expired");
			return false;
		}
		return true;
	}
	
	/**
	 * Validate the credit card security code
	 *
	 */
	public function validateCVV($cc_cvv)
	{
		$cc_num = $_POST["cardNumber"];
		$cardNumber = $this->sanitize($cc_num);
		$cvv = $this->sanitize($cc_cvv);
		// Get the first number of the credit card so we know how many digits to look for
		$firstnumber = (int) substr($cardNumber, 0, 1);
		if ($firstnumber === 3)
		{
			if (!preg_match("/^\d{4}$/", $cvv))
			{
				$this->form_validation->set_message("validateCVV", "The credit card is an American Express card but does not have a four digit CVV code");
				return false;
			}
		}
		else if (!preg_match("/^\d{3}$/", $cvv))
		{
			$this->form_validation->set_message("validateCVV", "The credit card is a Visa, MasterCard, or Discover Card card but does not have a three digit CVV code");
			return false;
		}
		return true;
	}
	
	/**
	 * Util method for sanitaizing ithe input
	 *
	 */
	function sanitize($value)
    {
        return trim(strip_tags($value));
    }	
}

/* End of file payment.php */
/* Location: ./application/controllers/payment.php */