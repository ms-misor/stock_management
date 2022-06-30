<?php 

class Model_orders extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the orders data */
	public function getOrdersData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM `orders` WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM `orders` ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	// get the orders item data
	public function getOrdersItemData($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM `orders_item` WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	public function create()
	{
		$user_id = $this->session->userdata('id');
		$bill_no = 'BILPR-'.strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
    	$data = array(
    		'bill_no' => $bill_no,
    		'customer_name' => $this->input->post('customer_name'),
    		'customer_address' => $this->input->post('customer_address'),
    		'customer_phone' => $this->input->post('customer_phone'),
    		'date_time' => strtotime(date('Y-m-d h:i:s a')),
    		'gross_amount' => $this->input->post('gross_amount_value'),
    		'service_charge_rate' => $this->input->post('service_charge_rate'),
    		'service_charge' => ($this->input->post('service_charge_value') > 0) ?$this->input->post('service_charge_value'):0,
    		'vat_charge_rate' => $this->input->post('vat_charge_rate'),
    		'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
    		'net_amount' => $this->input->post('net_amount_value'),
    		'discount' => $this->input->post('discount'),
    		'status' => "draft",
    		'user_id' => $user_id
    	);

		$insert = $this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		$this->load->model('model_products');

		$count_product = count($this->input->post('product'));
    	for($x = 0; $x < $count_product; $x++) {
    		$items = array(
    			'order_id' => $order_id,
    			'product_id' => $this->input->post('product')[$x],
    			'qty' => $this->input->post('qty')[$x],
    			'rate' => $this->input->post('rate_value')[$x],
    			'sale_rate' => $this->input->post('sale_rate_value')[$x],
    			'amount' => $this->input->post('amount')[$x],
    		);

    		$this->db->insert('orders_item', $items);

    		// now decrease the stock FROM `the` product

    		// $product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
    		// $qty = (int) $product_data['qty'] - (int) $this->input->post('qty')[$x];

    		// $update_product = array('qty' => $qty);


    		// $this->model_products->update($update_product, $this->input->post('product')[$x]);
    	}

		return ($order_id) ? $order_id : false;
	}

	public function countOrderItem($order_id)
	{
		if($order_id) {
			$sql = "SELECT * FROM `orders_item` WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
	}

	public function update($id)
	{
		if($id) {
			$user_id = $this->session->userdata('id');
			// fetch the order data 

			$data = array(
				'customer_name' => $this->input->post('customer_name'),
	    		'customer_address' => $this->input->post('customer_address'),
	    		'customer_phone' => $this->input->post('customer_phone'),
	    		'gross_amount' => $this->input->post('gross_amount_value'),
	    		'service_charge_rate' => $this->input->post('service_charge_rate'),
	    		'service_charge' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value'):0,
	    		'vat_charge_rate' => $this->input->post('vat_charge_rate'),
	    		'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
	    		'net_amount' => $this->input->post('net_amount_value'),
	    		'discount' => $this->input->post('discount'),
	    		'return_qty_print' => $this->input->post('return_qty_print'),
	    		'paid_status' => $this->input->post('paid_status'),
	    		'user_id' => $user_id
	    	);

			$this->db->where('id', $id);
			$update = $this->db->update('orders', $data);

			// now the order item 
			// first we will replace the product qty to original and subtract the qty again
			$this->load->model('model_products');
			// $get_order_item = $this->getOrdersItemData($id);
			// foreach ($get_order_item as $k => $v) {
			// 	$product_id = $v['product_id'];
			// 	$qty = $v['qty'];
			// 	// get the product 
			// 	$product_data = $this->model_products->getProductData($product_id);
			// 	$update_qty = $qty + $product_data['qty'];
			// 	$update_product_data = array('qty' => $update_qty);
				
			// 	// update the product qty
			// 	// $this->model_products->update($update_product_data, $product_id);
			// }

			// now remove the order item data 
			$this->db->where('order_id', $id);
			$this->db->delete('orders_item');

			// now decrease the product qty
			$count_product = count($this->input->post('product'));
	    	for($x = 0; $x < $count_product; $x++) {
	    		$items = array(
	    			'order_id' => $id,
	    			'product_id' => $this->input->post('product')[$x],
	    			'qty' => $this->input->post('qty')[$x],
	    			'return_qty' => $this->input->post('return_qty')[$x],
	    			'rate' => $this->input->post('rate_value')[$x],
	    			'sale_rate' => $this->input->post('sale_rate_value')[$x],
	    			'amount' => $this->input->post('amount')[$x],
	    		);
	    		$this->db->insert('orders_item', $items);

	    		// now decrease the stock FROM `the` product

	    		// $product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
	    		// $qty = (int) $product_data['qty'] + (int) $this->input->post('return_qty')[$x];

	    		// $update_product = array('qty' => $qty);
	    		// $this->model_products->update($update_product, $this->input->post('product')[$x]);

	    		$deduct_qty = 0;

	    		$deduct_qty = (int) $this->input->post('qty')[$x] - (int) $this->input->post('return_qty')[$x];

	    		$product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
	    		$qty = (int) $product_data['qty'] - $deduct_qty;

	    		$update_product = array('qty' => $qty);
	    		$this->model_products->update($update_product, $this->input->post('product')[$x]);
	    	}

			return true;
		}
	}


	public function update_status_data($id)
	{
		if($id) {
			$user_id = $this->session->userdata('id');
			// fetch the order data 

			$data = array(
				'customer_name' => $this->input->post('customer_name'),
	    		'customer_address' => $this->input->post('customer_address'),
	    		'customer_phone' => $this->input->post('customer_phone'),
	    		'gross_amount' => $this->input->post('gross_amount_value'),
	    		'service_charge_rate' => $this->input->post('service_charge_rate'),
	    		'service_charge' => ($this->input->post('service_charge_value') > 0) ? $this->input->post('service_charge_value'):0,
	    		'vat_charge_rate' => $this->input->post('vat_charge_rate'),
	    		'vat_charge' => ($this->input->post('vat_charge_value') > 0) ? $this->input->post('vat_charge_value') : 0,
	    		'net_amount' => $this->input->post('net_amount_value'),
	    		'discount' => $this->input->post('discount'),
	    		'paid_status' => $this->input->post('paid_status'),
	    		'status' => $this->input->post('status'),
	    		'user_id' => $user_id
	    	);

			$this->db->where('id', $id);
			$update = $this->db->update('orders', $data);

			// now the order item 
			// first we will replace the product qty to original and subtract the qty again
			$this->load->model('model_products');
			// $get_order_item = $this->getOrdersItemData($id);
			// foreach ($get_order_item as $k => $v) {
			// 	$product_id = $v['product_id'];
			// 	$qty = $v['qty'];
			// 	// get the product 
			// 	$product_data = $this->model_products->getProductData($product_id);
			// 	$update_qty = $qty + $product_data['qty'];
			// 	$update_product_data = array('qty' => $update_qty);
				
			// 	// update the product qty
			// 	$this->model_products->update($update_product_data, $product_id);
			// }

			// now remove the order item data 
			$this->db->where('order_id', $id);
			$this->db->delete('orders_item');

			// now decrease the product qty
			$count_product = count($this->input->post('product'));
	    	for($x = 0; $x < $count_product; $x++) {
	    		$items = array(
	    			'order_id' => $id,
	    			'product_id' => $this->input->post('product')[$x],
	    			'qty' => $this->input->post('qty')[$x],
	    			'rate' => $this->input->post('rate_value')[$x],
	    			'sale_rate' => $this->input->post('sale_rate_value')[$x],
	    			'amount' => $this->input->post('amount')[$x],
	    		);
	    		$this->db->insert('orders_item', $items);

	    		// now decrease the stock FROM `the` product

	    		// $product_data = $this->model_products->getProductData($this->input->post('product')[$x]);
	    		// $qty = (int) $product_data['qty'] - (int) $this->input->post('qty')[$x];

	    		// $update_product = array('qty' => $qty);
	    		// $this->model_products->update($update_product, $this->input->post('product')[$x]);
	    	}

			return true;
		}
	}


	public function remove($id)
	{
		if($id) {

			$order_data = $this->getOrdersData($id);

			//If order is not paid then delete the order and it's items only
			if((int)$order_data['paid_status'] == 0){

				$this->db->where('id', $id);
				$delete = $this->db->delete('orders');

				$this->db->where('order_id', $id);
				$delete_item = $this->db->delete('orders_item');

				return ($delete == true && $delete_item) ? true : false;
			}

			// If the order is paid , then delete all the order_items and the order... and also reduce the order product quantity

			$this->db->where('id', $id);
			$delete = $this->db->delete('orders');

			// now the order item 
			// first we will replace the product qty to original and subtract the qty again
			$this->load->model('model_products');
			$get_order_item = $this->getOrdersItemData($id);
			foreach ($get_order_item as $k => $v) {
				$product_id = $v['product_id'];
				$qty = $v['qty'] - $v['return_qty'];
				// get the product 
				$product_data = $this->model_products->getProductData($product_id);
				$update_qty = $qty + $product_data['qty'];
				$update_product_data = array('qty' => $update_qty);
				
				// update the product qty
				$this->model_products->update($update_product_data, $product_id);
			}

			$this->db->where('order_id', $id);
			$delete_item = $this->db->delete('orders_item');
			return ($delete == true && $delete_item) ? true : false;
		}
	}

	public function countTotalPaidOrders()
	{
		$sql = "SELECT * FROM `orders` WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}


	public function update_order_reuturn_status($return_qty_print,$order_id)
	{
		if($order_id) {
			$user_id = $this->session->userdata('id');
			// fetch the order data 

			$data = array(
	    		'return_qty_print' => $return_qty_print,
	    		'user_id' => $user_id
	    	);

			$this->db->where('id', $order_id);
			$update = $this->db->update('orders', $data);

			if($update){

				return true;
			}else{

				return false;
			}
		}
	}

}