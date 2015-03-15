<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Get type of card name
 *
 * @param 	string $card_number The card number to be checked
 * @return 	string $card_type
 */
function get_type_of_credit_card($credit_card_number) 
{
	// Get the first digit
	$data = array();
	$firstnumber = substr($credit_card_number, 0, 1);
	// Make sure it is the correct amount of digits. Account for dashes being present.
	switch ($firstnumber)
	{
		case 3:
			$card_type = "American Express";
			break;
		case 4:
			$card_type ="Visa";
			break;
		case 5:
			$card_type ="MasterCard";
			break;
		case 6:
			$card_type ="Discover";
			break;
		default:
			$card_type = "Invalid card type";
			break;
	}
	
	return $card_type;
}

/* End of file payment_helper.php */
/* Location: ./application/helpers/payment_helper.php */ 