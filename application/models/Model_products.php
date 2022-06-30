<?php 

class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getProductData($id = null)
	{

		if($id) {
			$sql = "SELECT * FROM `products` where id = ?";
			$query = $this->db->query($sql, array($id));

			$data = $query->row_array();

			// Get the selected product last order sale price
			$last = $this->db->order_by('id',"desc")
			->where('product_id',$id)
			->limit(1)
			->get('orders_item')
			->row();

			if($last){
				$data['price'] = $last->sale_rate;
			}
			//End

			return $data;
		}

		$sql = "SELECT * FROM `products` ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	/* getProductDataForItem */
	public function getProductDataForItem($id = null)
	{

		if($id) {
			$sql = "SELECT * FROM `products` where id = ?";
			$query = $this->db->query($sql, array($id));

			$data = $query->row_array();

			return $data;
		}
	}

	public function getActiveProductData()
	{
		$sql = "SELECT * FROM `products` WHERE availability = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('products', $data);

			return $insert_id = $this->db->insert_id();

			// return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalProducts()
	{
		$sql = "SELECT * FROM `products`";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	/*Items Purchase section starts*/

	/* get the getItemsData */
	public function getItemsData($id = null)
	{

		return $result = $this->db->select('products_items.*,pd.name') 
             ->from('products_items')
             ->join('products pd', 'products_items.product_id = pd.id', 'left')
             ->order_by('products_items.purchase_date ','desc')
             ->get()
             ->result();
	}

	/* getStocksProducts*/
	public function getStocksProducts()
	{
		$sql = "SELECT * FROM `products`";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function item_create($data)
	{
		if($data) {
			$insert = $this->db->insert('products_items', $data);
			$insert_id = $this->db->insert_id();

			return $insert_id;

			//return ($insert == true) ? true : false;
		}
	}

	/* get the getItemsDataById */
	public function getItemsDataById($id = null)
	{

		return $result = $this->db->select('*') 
             ->from('products_items')
             ->where('id',$id)
             ->get()
             ->row();
	}

	public function item_update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products_items', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove_item($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products_items');
			return ($delete == true) ? true : false;
		}
	}

	/* get the getItemsDataById */
	public function updateItemsDataByProductId($product_id = null,$id = null)
	{

		$result = $this->db->select('*') 
             ->from('products_items')
             ->where('product_id',$product_id)
             ->where('id !=',$id)
             ->where('no_update_delete',0)
             ->get()
             ->num_rows();

        if($result > 0){

        	$products_items = $this->db->select('*') 
             ->from('products_items')
             ->where('product_id',$product_id)
             ->where('id !=',$id)
             ->where('no_update_delete',0)
             ->get()
             ->row();

        	$data_item = array(
                'no_update_delete' => 1,
            );

            $this->db->where('id', $products_items->id);
			$update = $this->db->update('products_items', $data_item);

			return ($update == true) ? true : false;

        }
	}

	/* get the getItemsDataById */
	public function getProductInfoByUnitId($unit_id = null,$product_id = null)
	{

		return $result = $this->db->select('*') 
             ->from('products')
             ->where('id',$product_id)
             ->where('unit',$unit_id)
             ->get()
             ->row();
	}

	/* get the purchase or order item related with stock product */
	public function purchaseAndOrderItem($product_id = null)
	{

		$products_items = $this->db->select('*') 
             ->from('products_items')
             ->where('product_id',$product_id)
             ->get()
             ->num_rows();

        $order_items = $this->db->select('*') 
             ->from('orders_item')
             ->where('product_id',$product_id)
             ->get()
             ->num_rows();

        if($products_items > 0 || $order_items > 0){
        	return true;
        }
        return false;
    }

    /* get the getTotalPurchase */
	public function getTotalPurchase($product_id = null)
	{

		$total = 0.00;

		$results = $this->db->select('*') 
             ->from('products_items')
             ->where('product_id',$product_id)
             ->get()
             ->result();

        foreach ($results as $key => $value) {

        	$total = (float)$total + (float)$value->total_price;
        }

        return $total;

	}

	/* get the getTotalSale */
	public function getTotalSale($product_id = null)
	{

		$total = 0.00;

		$results = $this->db->select('*') 
             ->from('orders_item')
             ->where('product_id',$product_id)
             ->get()
             ->result();

        foreach ($results as $key => $value) {

        	$order = $this->db->select('*') 
             ->from('orders')
             ->where('id',$value->order_id)
             ->get()
             ->row();

             if($order->paid_status == 1){

             	$qty = (int)$value->qty - (int)$value->return_qty;
        		$total = (float)$total + ((float)$value->rate * $qty);
             }
        }

        return $total;

	}

	/* get the getProductInfoByName */
	public function getProductInfoByName($product_name)
	{

		return $result = $this->db->select('*') 
             ->from('products')
             ->where('name',$product_name)
             ->get()
             ->row();
	}

	// Ends

}