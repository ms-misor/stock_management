<?php 

class Model_reports extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*getting the total months*/
	private function months()
	{
		return array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	}

	/* getting the year of the orders */
	public function getOrderYear()
	{
		$sql = "SELECT * FROM `orders` WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		$result = $query->result_array();
		
		$return_data = array();
		foreach ($result as $k => $v) {
			$date = date('Y', $v['date_time']);
			$return_data[] = $date;
		}

		$return_data = array_unique($return_data);

		return $return_data;
	}

	// getting the order reports based on the year and moths
	public function getOrderData($year)
	{	
		if($year) {
			$months = $this->months();
			
			$sql = "SELECT * FROM `orders` WHERE paid_status = ?";
			$query = $this->db->query($sql, array(1));
			$result = $query->result_array();

			$final_data = array();
			foreach ($months as $month_k => $month_y) {
				$get_mon_year = $year.'-'.$month_y;	

				$final_data[$get_mon_year][] = '';
				foreach ($result as $k => $v) {
					$month_year = date('Y-m', $v['date_time']);

					if($get_mon_year == $month_year) {
						$final_data[$get_mon_year][] = $v;
					}
				}
			}	


			return $final_data;
			
		}
	}

	/*category wise report functionality starts*/

	public function get_total_purchase_products($data){

		// $total_purchase_arr = array();

		// $start_date = date('Y-m-d',strtotime($data['start_date']));
		// $end_date =  date('Y-m-d',strtotime($data['end_date']));

		$products = $this->db->select('*') 
             ->from('products')
             ->where('category_id',$data['category'])
             ->order_by('name','asc')
             ->get()
             ->result();

        return $products;

        // foreach ($products as $key => $row) {

        // 	$total_purchase = 0;

        // 	$results = $this->db->select('*') 
        //      ->from('products_items')
        //      ->where('product_id',$row->id)
        //      ->where('purchase_date >=',$start_date)
        //      ->where('purchase_date <=',$end_date)
        //      ->get()
        //      ->result();

	       //  foreach ($results as $key => $value) {

	       //  	$total_purchase = (float)$total_purchase + (float)$value->total_price;
	       //  }

	       //  $total_purchase_arr[$row->name] = $total_purchase;
        // }

        // return $total_purchase_arr;

	}

	public function get_total_sale_products($data){

		$total_sale_arr = array();

		$start_time = strtotime($data['start_date']);
		$end_time =  strtotime($data['end_date']);

		$orders = $this->db->select('*') 
             ->from('orders')
             ->where('date_time >=', $start_time)
             ->where('date_time <=', $end_time)
             ->where('paid_status', 1)
             ->get()
             ->result();

        foreach ($orders as $key => $row) {

        	$results = $this->db->select('*') 
             ->from('orders_item')
             ->where('order_id',$row->id)
             ->get()
             ->result();

	        foreach ($results as $key => $value) {
	        	$total = 0;
	        	$qty = (int)$value->qty - (int)$value->return_qty;
        		$total = (float)$total + ((float)$value->rate * $qty);

	        	if(!isset($total_sale_arr[$value->product_id])){

	        		$total_sale_arr[$value->product_id] = $total;

	        	}else{

	        		$total_sale_arr[$value->product_id] = $total_sale_arr[$value->product_id] + $total;
	        	}
	        }
        }

        $final_sale_arr = array();

        if(count($total_sale_arr) > 0){
        	foreach ($total_sale_arr as $key => $new_row) {
	        	$final_result = $this->db->select('name') 
	             ->from('products')
	             ->where('id',$key)
	             ->where('category_id',$data['category'])
	             ->get()
	             ->row();

	             if($final_result){
	             	$final_sale_arr[$final_result->name] = $new_row;
	             }
	        }
        }
        

        return $final_sale_arr;

	}

	/*category wise report functionality starts*/
}