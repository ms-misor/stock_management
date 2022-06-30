<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Products';

		$this->load->model('model_products');
		$this->load->model('model_brands');
		$this->load->model('model_category');
		$this->load->model('model_stores');
		$this->load->model('model_attributes');

        $this->load->model('model_units');
        $this->load->model('model_big_units');

        date_default_timezone_set('Asia/Dhaka');
	}

    /* 
    * It only redirects to the manage product page
    */
	public function index()
	{
        if(!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('products/index', $this->data);	
	}

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
	public function fetchProductData()
	{
		$result = array('data' => array());

		$data = $this->model_products->getProductData();

		foreach ($data as $key => $value) {

            $store_data = $this->model_stores->getStoresData($value['store_id']);
			// button
            $buttons = '';
            if(in_array('updateProduct', $this->permission)) {
    			$buttons .= '<a href="'.base_url('products/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }

            if(in_array('deleteProduct', $this->permission)) { 
    			$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }
			

			$img = '<img src="'.base_url($value['image']).'" alt="'.$value['name'].'" class="img-circle" width="50" height="50" />';

            $availability = ($value['availability'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $qty_status = '';
            if($value['qty'] <= 10) {
                $qty_status = '<span class="label label-warning">Low !</span>';
            } else if($value['qty'] <= 0) {
                $qty_status = '<span class="label label-danger">Out of stock !</span>';
            }

            $total_purchase = 0;
            $total_sale = 0;
            // $total_purchase = $this->model_products->getTotalPurchase($value['id']);
            $total_purchase = (float)$value['price'] * (float)$value['qty'];
            $total_sale = $this->model_products->getTotalSale($value['id']);

			// $result['data'][$key] = array(
			// 	$img,
			// 	$value['sku'],
			// 	$value['name'],
			// 	$value['price'],
   //              $value['qty'] . ' ' . $qty_status,
   //              $store_data['name'],
			// 	$availability,
			// 	$buttons
			// );
            $result['data'][$key] = array(
                $value['name'],
                $value['price'],
                $value['qty'] . ' ' . $qty_status,
                number_format((float)$total_purchase, 2, '.', ''),
                $total_sale,
                $availability,
                $buttons
            );
		} // /foreach

		echo json_encode($result);
	}	

    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function create()
	{
		if(!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
		// $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        // $this->form_validation->set_rules('store', 'Store', 'trim|required');
		$this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        $this->form_validation->set_rules('unit', 'Unit', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {

            // check product name aready taken
            $product_info = $this->model_products->getProductInfoByName($this->input->post('product_name'));
            if($product_info){

                $this->session->set_flashdata('error', 'Product name already used!!');
                redirect('products/create', 'refresh');
            }

            // true case
        	// $upload_image = $this->upload_image();

        	$data = array(
        		'name' => $this->input->post('product_name'),
        		// 'sku' => $this->input->post('sku'),
        		'price' => $this->input->post('price'),
                'qty' => $this->input->post('qty'),
        		'unit' => $this->input->post('unit'),
        		// 'image' => $upload_image,
        		// 'description' => $this->input->post('description'),
        		'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
        		'brand_id' => json_encode($this->input->post('brands')),
        		'category_id' => $this->input->post('category'),
                // 'store_id' => $this->input->post('store'),
        		'availability' => $this->input->post('availability'),
                'create_date' => date('Y-m-d h:i:s'),
        	);

            //dd($data);

        	$create_id = $this->model_products->create($data);
        	if($create_id) {

                addActivityLog("products stocks", "created", $create_id, "products", 1, $data);

        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('products/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('error', 'Error occurred!!');
        		redirect('products/create', 'refresh');
        	}
        }
        else {
            // false case

        	// attributes 
        	$attribute_data = $this->model_attributes->getActiveAttributeData();

        	$attributes_final_data = array();
        	foreach ($attribute_data as $k => $v) {
        		$attributes_final_data[$k]['attribute_data'] = $v;

        		$value = $this->model_attributes->getAttributeValueData($v['id']);

        		$attributes_final_data[$k]['attribute_value'] = $value;
        	}

        	$this->data['attributes'] = $attributes_final_data;
			$this->data['brands'] = $this->model_brands->getActiveBrands();        	
			$this->data['category'] = $this->model_category->getActiveCategroy();        	
			$this->data['stores'] = $this->model_stores->getActiveStore(); 

            $this->data['units'] = $this->model_units->getActiveUnits();

            //dd($this->data);        	

            $this->render_template('products/create', $this->data);
        }	
	}

    /*
    * This function is invoked from another function to upload the image into the assets folder
    * and returns the image path
    */
	public function upload_image()
    {
    	// assets/images/product_image
        $config['upload_path'] = 'assets/images/product_image';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('product_image'))
        {
            $error = $this->upload->display_errors();
            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES['product_image']['name']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_name'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function update($product_id)
	{      
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        // $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        // $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {

            // check product name aready taken

            $product_data_info = $this->model_products->getProductData($product_id);

            if($product_data_info['name'] != $this->input->post('product_name')){

                $product_info = $this->model_products->getProductInfoByName($this->input->post('product_name'));
                if($product_info){
                    
                    $this->session->set_flashdata('error', 'Product name already used!!');
                    redirect('products/update/'.$product_id, 'refresh');
                }
            }// End
            

            // true case
            
            $data = array(
                'name' => $this->input->post('product_name'),
                // 'sku' => $this->input->post('sku'),
                'price' => $this->input->post('price'),
                // 'qty' => $this->input->post('qty'),
                'unit' => $this->input->post('unit'),
                // 'description' => $this->input->post('description'),
                'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
                'brand_id' => json_encode($this->input->post('brands')),
                'category_id' => $this->input->post('category'),
                // 'store_id' => $this->input->post('store'),
                'availability' => $this->input->post('availability'),
                'update_date' => date('Y-m-d h:i:s'),
            );

            
            // if($_FILES['product_image']['size'] > 0) {
            //     $upload_image = $this->upload_image();
            //     $upload_image = array('image' => $upload_image);
                
            //     $this->model_products->update($upload_image, $product_id);
            // }

            $update = $this->model_products->update($data, $product_id);
            if($update == true) {

                addActivityLog("products stocks", "updated", $product_id, "products", 2, $data);

                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('products/', 'refresh');
            }
            else {
                $this->session->set_flashdata('error', 'Error occurred!!');
                redirect('products/update/'.$product_id, 'refresh');
            }
        }
        else {
            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }
            
            // false case
            $this->data['attributes'] = $attributes_final_data;
            $this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();          

            $product_data = $this->model_products->getProductData($product_id);
            $this->data['product_data'] = $product_data;

            $this->data['units'] = $this->model_units->getActiveUnits();

            //dd($this->data);

            $this->render_template('products/edit', $this->data); 
        }   
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        $product_id = $this->input->post('product_id');

        //Check, if this product already started/related with purchase or order
        $purchaseAndOrderItem = $this->model_products->purchaseAndOrderItem($product_id);
        if($purchaseAndOrderItem){

            $response['success'] = false;
            $response['messages'] = "This product already related with puchase or order items !";

            echo json_encode($response);exit;
        }
        // End

        $response = array();
        if($product_id) {
            $delete = $this->model_products->remove($product_id);
            if($delete == true) {

                // store addActivityLogs
                $data = array();
                addActivityLog("products stocks", "deleted", $product_id, "products", 3, $data);

                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
	}


    /* Purchase starts from 21jan 2022*/

    /* 
    * It only redirects to the manage product page
    */
    public function item_list()
    {
        // if(!in_array('viewProduct', $this->permission)) {
        //     redirect('dashboard', 'refresh');
        // }

        $items_data = $this->model_products->getItemsData();

        $this->data['items'] = $items_data;
        $this->data['page_title'] = 'Items';

        //dd($this->data);

        $this->render_template('products/item_list', $this->data);  
    }


    /*
    item_create for purchase
    */
    public function item_create()
    {
        // if(!in_array('createProduct', $this->permission)) {
        //     redirect('dashboard', 'refresh');
        // }

        $this->form_validation->set_rules('product_id', 'Product name', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('small_unit', 'Small unit', 'trim|required');
        $this->form_validation->set_rules('small_unit_qty', 'Small unit qty', 'trim|required');
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'trim|required');
        
    
        if ($this->form_validation->run() == TRUE) {

            $product_id = $this->input->post('product_id');

            $product_info = $this->model_products->getProductDataForItem($product_id);

            // Check, if the new purchase item's stock product qty is zero, then allow to purchase with new/updated price.. otherwise force to purchase with the stock product existing price..

            if((float)$this->input->post('price') != (float)$product_info['price']){
                if((int)$product_info['qty'] > 0){

                    $this->session->set_flashdata('error', 'To purchase the stock item with new price, stock qty must be zero, otherwise please enter stock unit price '.$product_info['price']);
                    redirect('products/item_create', 'refresh');
                }
            }
            //End

            $data = array(
                'product_id' => $this->input->post('product_id'),
                'price' => $this->input->post('price'),
                'total_price' => $this->input->post('total_price'),
                'qty' => $this->input->post('qty'),
                'big_unit' => $this->input->post('big_unit'),
                'big_unit_qty' => $this->input->post('big_unit_qty'),
                'small_unit' => $this->input->post('small_unit'),
                'small_unit_qty' => $this->input->post('small_unit_qty'),
                // 'description' => $this->input->post('description'),
                'purchase_date' => date('Y-m-d',strtotime($this->input->post('purchase_date'))),
                'create_date' => date('Y-m-d h:i:s'),

            );

            //dd($data);

            $result = $this->model_products->item_create($data);
            if($result) {

                addActivityLog("purchase items", "create", $result, "products_items", 1, $data);

                // Increase Stocks products qty, after purchase product items from here

                $quantity = 0;
                $quantity = (int)$this->input->post('qty') + (int)$product_info['qty'];
                $data_product = array(
                    'price' => $this->input->post('price'),
                    'qty' => $quantity,
                );

                $product_up = $this->model_products->update($data_product,$product_id);
                if($product_up){
                    addActivityLog("products qty updated from purchase", "updated", $product_id, "products", 2, $data_product);
                }

                //Ends

                //Check if items already purchase for any product, then set not_delete value to 1 for that the purchase_items
                $purchase_item_info = $this->model_products->updateItemsDataByProductId($product_id,$result);

                //Ends

                $this->session->set_flashdata('success', 'Successfully created');
                redirect('products/item_list', 'refresh');
            }
            else {
                $this->session->set_flashdata('error', 'Error occurred!!');
                redirect('products/item_create', 'refresh');
            }
        }
        else {
            // false case

            // attributes 
            $this->data['page_title'] = 'Create Item';
            $this->data['products'] = $this->model_products->getStocksProducts();

            $this->data['small_units'] = $this->model_big_units->getActiveUnits();
            $this->data['big_units'] = $this->model_big_units->getActiveBigUnits();

            // dd($this->data);         

            $this->render_template('products/item_create', $this->data);
        }   
    }

    /*
    item_create for purchase
    */
    public function item_edit($id = null)
    {
        // if(!in_array('createProduct', $this->permission)) {
        //     redirect('dashboard', 'refresh');
        // }
        $this->data['item_info'] = $item_info = $this->model_products->getItemsDataById($id);
        $product_info = $this->model_products->getProductDataForItem($item_info->product_id);

        $this->form_validation->set_rules('small_unit', 'Small unit', 'trim|required');
        $this->form_validation->set_rules('small_unit_qty', 'Small unit qty', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('purchase_date', 'Purchase Date', 'trim|required');
        
    
        if ($this->form_validation->run() == TRUE) {

            // Check if not_delete_update is 1, means there is new item created for the same product in purchase item... so that new item should modify first...

            if($item_info->no_update_delete == 1){

                $this->session->set_flashdata('error', 'There is new item purchase created for the selected product, so that new purchase can be updated only');
                redirect('products/item_list/', 'refresh');
            }

            // Now also check, if wants to update the new item purchase unit orice.. then it will be required that stock product qty and purchase item qty which going to update should be equal..

            if((float)$this->input->post('price') != (float)$product_info['price']){
                if((int)$product_info['qty'] != (int)$this->input->post('qty')){

                    $this->session->set_flashdata('error', 'To update the purchase item with new price, stock product qty must be equal with the purchase item qty which going to update with new unit price.');
                    redirect('products/item_edit/'.$id, 'refresh');
                }
            }

            // ENDS

            // Starts product qty update from here purchase item update
            $update_product_flag = false;
            $quantity = 0;
            $qty_to_be_added = 0;
            $qty_to_be_deducted = 0;
            // if new qty is large than old qty
            if((int)$this->input->post('qty') > (int)$item_info->qty){

                $qty_to_be_added = (int)$this->input->post('qty') - (int)$item_info->qty;
                $quantity = $qty_to_be_added + (int)$product_info['qty'];
                $update_product_flag = true;
            }
            if((int)$this->input->post('qty') < (int)$item_info->qty){

                $qty_to_be_deducted = (int)$item_info->qty - (int)$this->input->post('qty');
                if($qty_to_be_deducted <= (int)$product_info['qty']){

                    $quantity = (int)$product_info['qty'] - $qty_to_be_deducted;
                    $update_product_flag = true;
                }else{

                    $this->session->set_flashdata('error', 'Your stock product quantity is lesser than the quantity you are trying to reduce !');
                    redirect('products/item_edit/'.$id, 'refresh');
                }
            }
            //Check, if price is not equal to item existing price, then also update for product in stock
            if((float)$this->input->post('price') != (float)$item_info->price){
                $update_product_flag = true;
            }

            // Ends

            // true case
            // $upload_image = $this->upload_image();

            $data = array(
                'big_unit' => $this->input->post('big_unit'),
                'big_unit_qty' => $this->input->post('big_unit_qty'),
                'small_unit' => $this->input->post('small_unit'),
                'small_unit_qty' => $this->input->post('small_unit_qty'),
                'price' => (float)$this->input->post('price'),
                'total_price' => (float)$this->input->post('total_price'),
                'qty' => $this->input->post('qty'),
                // 'description' => $this->input->post('description'),
                'purchase_date' => date('Y-m-d',strtotime($this->input->post('purchase_date'))),
                'update_date' => date('Y-m-d h:i:s'),

            );

            // dd($data);

            $result = $this->model_products->item_update($data,$id);
            if($result) {

                addActivityLog("purchase items", "updated", $id, "products_items", 2, $data);

                // Update Stocks products qty, after purchase product items update from here
                
                if($update_product_flag){

                    $data_product = array(
                        'price' => (float)$this->input->post('price'),
                        'qty' => $quantity,
                    );

                    //dd($data_product);

                    //if quantity not changed, then
                    if((int)$this->input->post('qty') == (int)$item_info->qty){
                        unset($data_product['qty']);
                    }
                    //End
                    $product_up = $this->model_products->update($data_product,$item_info->product_id);
                    if($product_up){
                        addActivityLog("products qty updated from purchase", "updated", $item_info->product_id, "products", 2, $data_product);
                    }
                }
                //Ends

                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('products/item_list', 'refresh');
            }
            else {
                $this->session->set_flashdata('error', 'Error occurred!!');
                redirect('products/item_edit', 'refresh');
            }
        }
        else {
            // false case

            $this->data['id'] = $id;

            // attributes 
            $this->data['page_title'] = 'Update Item';
            $this->data['products'] = $this->model_products->getStocksProducts();

            $this->data['small_units'] = $this->model_big_units->getActiveUnits();
            $this->data['big_units'] = $this->model_big_units->getActiveBigUnits();
            
            $this->data['item_info']->purchase_date = date('m/d/Y',strtotime($item_info->purchase_date));

            // dd($this->data);          

            $this->render_template('products/item_edit', $this->data);
        }   
    }

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
    public function remove_item($id)
    {
        if($id) {

            //Get item info and check if the flag no_update_delete is 1, then not allow to delete...
            $itemsData = $this->model_products->getItemsDataById($id);
            if((int)$itemsData->no_update_delete == 1){

                $this->session->set_flashdata('error', 'There is new item purchase of for the product, which needs to delete first !');
                redirect('products/item_list', 'refresh'); 
            }

            //If eligible to delete, then reduce the qty from stock purchase also after checking if the stock product has same qty available as like the deleted item..
            $productData = $this->model_products->getProductData($itemsData->product_id);
            if((int)$itemsData->qty > (int)$productData['qty']){

                $this->session->set_flashdata('error', 'item quantity is smaller than stock product quantity, so you can only update the item.');
                redirect('products/item_list', 'refresh'); 

            }
            // dd($productData);
            // exit;

            $delete = $this->model_products->remove_item($id);
            if($delete == true) {

                // Reduce the Stock product quantity after deleting the purchase item
                $updated_qty = (int)$productData['qty'] - (int)$itemsData->qty;
                $data_product  = array(
                    "qty" => $updated_qty,
                );

                $product_up = $this->model_products->update($data_product,$itemsData->product_id);
                if($product_up){
                    addActivityLog("products qty reduced after purchase item delete", "updated", $itemsData->product_id, "products", 2, $data_product);
                }

                // Ends

                //Store activity logs
                $data  = array();
                addActivityLog("purchase items", "deleted", $id, "products_items", 3, $data);

                $this->session->set_flashdata('success', 'Successfully deleted');
                redirect('products/item_list', 'refresh'); 
            }
            else {
                $this->session->set_flashdata('error', 'Error in the database while removing the item information');
                redirect('products/item_list', 'refresh');            }
        }
        else {
            $this->session->set_flashdata('error', 'Refersh the page again!!');
            redirect('products/item_list', 'refresh');
        }
    }

    public function check_big_unit(){

        $product_id = $this->input->post('product_id');
        $big_unit_id = $this->input->post('big_unit_id');

        $big_unit_info = $this->model_big_units->getBigUnitDataById($big_unit_id);
        $product_info = $this->model_products->getProductInfoByUnitId($big_unit_info->unit_id,$product_id);

        if($product_info){

            echo json_encode(true);
        }else{

            echo json_encode(false);
        }

        // echo json_encode($product_info);
    }

    public function product_qty_for_big_unit(){

        $big_unit_qty = $this->input->post('big_unit_qty');
        $big_unit_id = $this->input->post('big_unit_id');

        $big_unit_info = $this->model_big_units->getBigUnitDataById($big_unit_id);

        if($big_unit_info){

            $total_qty = (int)$big_unit_info->qty * (int)$big_unit_qty;

            $data = array(
                'status'    => true,
                'total_qty' => $total_qty

                );
            echo json_encode($data);exit;
        }else{

            $data = array(
                'status'    => false,
                'total_qty' => 0

                );
            echo json_encode($data);exit;
        }

        // echo json_encode($big_unit_info);
    }

    public function check_small_unit_calc(){

        $product_id = $this->input->post('product_id');
        $small_unit_id = $this->input->post('small_unit_id');

        $product_info = $this->model_products->getProductInfoByUnitId($small_unit_id,$product_id);

        if($product_info){

            echo json_encode(true);
        }else{

            echo json_encode(false);
        }

        // echo json_encode($product_info);
    }

    public function product_qty_for_small_unit(){

        $small_unit_qty = $this->input->post('small_unit_qty');
        $small_unit_id = $this->input->post('small_unit_id');

        $big_unit_qty = $this->input->post('big_unit_qty');
        $big_unit_id = $this->input->post('big_unit_id');

        $big_unit_info = $this->model_big_units->getBigUnitDataById($big_unit_id);

        $total_big_unit_qty = 0;

        if($big_unit_info && (int)$big_unit_qty > 0){
            $total_big_unit_qty = (int)$big_unit_info->qty * (int)$big_unit_qty; 
        }

        $final_qty = 0;
        $final_qty = $total_big_unit_qty + (int)$small_unit_qty;

        $data = array(
                'status'    => true,
                'total_qty' => $final_qty

            );
        echo json_encode($data);exit;


        // echo json_encode($this->input->post());
    }

    /*
    * It gets the product id passed from the ajax method.
    * It checks retrieves the particular product data from the product id 
    * and return the data into the json format.
    */
    public function getProductValueByIdForItem()
    {
        $product_id = $this->input->post('product_id');
        if($product_id) {
            $product_data = $this->model_products->getProductDataForItem($product_id);
            echo json_encode($product_data);
        }
    }

    /*End*/

}