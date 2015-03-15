<?php
class Payment_model extends CI_Model
{
	
	// Constructor
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Record order history
	 *
	 */
	public function record_order_history($customer_name, $price, $currency_type, $payment_method, $payment_id)
	{
		$data = array(
				'customer_name' => $customer_name,
				'price' => $price,
				'currency_type' => $currency_type,
				'payment_method' => $payment_method,
				'payment_id' => $payment_id
				);
		$this->db->insert('tbl_orders', $data);
	}
}

/* End of file payment_model.php */
/* Location: ./applications/models/payment_model.php */