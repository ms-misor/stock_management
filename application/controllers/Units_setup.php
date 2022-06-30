<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Units_setup extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Big Units';

		$this->load->model('model_big_units');
	}

	/* 
	* It only redirects to the manage product page and
	*/
	public function index()
	{

		$result = $this->model_big_units->getBigUnitData();

		$this->data['results'] = $result;

		//dd($result);

		$this->render_template('big_units/index', $this->data);
	}

	/*
    item_create for purchase
    */
    public function big_unit_create()
    {
        $this->form_validation->set_rules('unit_id', 'Small Unit', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        
    
        if ($this->form_validation->run() == TRUE) {

            $data = array(
                'unit_id' => $this->input->post('unit_id'),
                'name' => $this->input->post('name'),
                'qty' => $this->input->post('qty'),
                'create_date' => date('Y-m-d h:i:s'),

            );

            // Check , if the big unit name already used...
            $big_unit_name = $this->model_big_units->getBigUnitDataByName($this->input->post('name'));
            if($big_unit_name){

            	$this->session->set_flashdata('error', $this->input->post('name').' already used for big unit name !!');
                redirect('units_setup/big_unit_create');
            }
            // dd($data);

            $result = $this->model_big_units->create($data);
            if($result) {

                addActivityLog("big units setup", "create", $result, "big_units", 1, $data);

                $this->session->set_flashdata('success', 'Successfully created');
                redirect('units_setup/', 'refresh');
            }
            else {
                $this->session->set_flashdata('error', 'Error occurred!!');
                redirect('units_setup/big_unit_create');
            }
        }
        else {
            // false case

            // attributes 
            $this->data['page_title'] = 'Set Big Unit';
            $this->data['units'] = $this->model_big_units->getActiveUnits();

            // dd($this->data);          

            $this->render_template('big_units/create_big_unit', $this->data);
        }   
    }

    /*
    item_create for purchase
    */
    public function big_unit_edit($id = null)
    {

        $this->data['big_unit_info'] = $big_unit_info = $this->model_big_units->getBigUnitDataById($id);

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        
    
        if ($this->form_validation->run() == TRUE) {

            $data = array(
                'name' => $this->input->post('name'),
                'qty' => $this->input->post('qty'),
                'update_date' => date('Y-m-d h:i:s'),

            );

            // Check , if the big unit name already used...
            if($this->input->post('name') != $big_unit_info->name){

	            $big_unit_name = $this->model_big_units->getBigUnitDataByName($this->input->post('name'));
	            if($big_unit_name){

	            	$this->session->set_flashdata('error', $this->input->post('name').' already used for big unit name !!');
	                redirect('units_setup/big_unit_edit/'.$id, 'refresh');
	            }
            }
            //dd($data);

            $result = $this->model_big_units->update($data,$id);
            if($result) {

                addActivityLog("big units setup", "updated", $id, "big_units", 2, $data);

                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('units_setup/', 'refresh');
            }
            else {
                $this->session->set_flashdata('error', 'Error occurred!!');
                redirect('units_setup/big_unit_edit/'.$id, 'refresh');
            }
        }
        else {
            // false case

            $this->data['id'] = $id;

            // attributes 
            $this->data['page_title'] = 'Update big unit';
            $this->data['units'] = $this->model_big_units->getActiveUnits();

            //dd($this->data);

            $this->render_template('big_units/update_big_unit', $this->data);
        }   
    }


    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
    public function remove_big_unit($id)
    {
        if($id) {

            //Get item info and check if the flag no_update_delete is 1, then not allow to delete...
            // $itemsData = $this->model_products->getItemsDataById($id);
            // if((int)$itemsData->no_update_delete == 1){

            //     $this->session->set_flashdata('error', 'There is new item purchase of for the product, which needs to delete first !');
            //     redirect('products/item_list', 'refresh'); 
            // }

            $delete = $this->model_big_units->remove($id);
            if($delete == true) {

                //Store activity logs
                $data  = array();
                addActivityLog("big units setup", "deleted", $id, "big_units", 3, $data);

                $this->session->set_flashdata('success', 'Successfully deleted');
                redirect('units_setup/', 'refresh');
            }
            else {
                $this->session->set_flashdata('error', 'Error in the database while removing the bi unit information');
                redirect('units_setup/', 'refresh');           }
        }
        else {
            $this->session->set_flashdata('error', 'Refersh the page again!!');
            redirect('units_setup/', 'refresh');
        }
    }

}