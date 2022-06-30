<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Units extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Units';

		$this->load->model('model_units');
	}

	/* 
	* It only redirects to the manage product page and
	*/
	public function index()
	{
		// if(!in_array('viewBrand', $this->permission)) {
		// 	redirect('dashboard', 'refresh');
		// }

		$result = $this->model_units->getUnitData();

		$this->data['results'] = $result;

		$this->render_template('units/index', $this->data);
	}

	/*
	* Fetches the brand data from the brand table 
	* this function is called from the datatable ajax function
	*/
	public function fetchUnitData()
	{
		$result = array('data' => array());

		$data = $this->model_units->getUnitData();

		// echo json_encode($data);
		// exit;

		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			// if(in_array('viewBrand', $this->permission)) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editUnit('.$value['id'].')" data-toggle="modal" data-target="#editUnitModal"><i class="fa fa-pencil"></i></button>';	
			// }
			
			// if(in_array('deleteBrand', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeUnit('.$value['id'].')" data-toggle="modal" data-target="#removeUnitModal"><i class="fa fa-trash"></i></button>
				';
			// }				

			$status = ($value['active'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['name'],
				$status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* It checks if it gets the brand id and retreives
	* the brand information from the brand model and 
	* returns the data into json format. 
	* This function is invoked from the view page.
	*/
	public function fetchUnitDataById($id)
	{
		if($id) {
			$data = $this->model_units->getUnitData($id);
			echo json_encode($data);
		}

		return false;
	}

	/*
	* Its checks the brand form validation 
	* and if the validation is successfully then it inserts the data into the database 
	* and returns the json format operation messages
	*/
	public function create()
	{

		// if(!in_array('createBrand', $this->permission)) {
		// 	redirect('dashboard', 'refresh');
		// }

		$response = array();

		$this->form_validation->set_rules('unit_name', 'Unit name', 'trim|required');
		$this->form_validation->set_rules('active', 'Active', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('unit_name'),
        		'active' => $this->input->post('active'),	
        		'create_date' => date('Y-m-d h:i:s'),	
        	);

        	$create_id = $this->model_units->create($data);
        	if($create_id) {

        		addActivityLog("units", "created", $create_id, "units", 1, $data);

        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the unit information';			
        	}
        }
        else {
        	$response['success'] = false;
        	foreach ($_POST as $key => $value) {
        		$response['messages'][$key] = form_error($key);
        	}
        }

        echo json_encode($response);

	}

	/*
	* Its checks the brand form validation 
	* and if the validation is successfully then it updates the data into the database 
	* and returns the json format operation messages
	*/
	public function update($id)
	{
		// if(!in_array('updateBrand', $this->permission)) {
		// 	redirect('dashboard', 'refresh');
		// }

		$response = array();

		if($id) {
			$this->form_validation->set_rules('edit_unit_name', 'Unit name', 'trim|required');
			$this->form_validation->set_rules('edit_active', 'Active', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('edit_unit_name'),
	        		'active' => $this->input->post('edit_active'),	
	        		'update_date' => date('Y-m-d h:i:s'),
	        	);

	        	$update = $this->model_units->update($data, $id);
	        	if($update == true) {

	        		addActivityLog("units", "updated", $id, "units", 2, $data);

	        		$response['success'] = true;
	        		$response['messages'] = 'Succesfully updated';
	        	}
	        	else {
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while updated the unit information';			
	        	}
	        }
	        else {
	        	$response['success'] = false;
	        	foreach ($_POST as $key => $value) {
	        		$response['messages'][$key] = form_error($key);
	        	}
	        }
		}
		else {
			$response['success'] = false;
    		$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	/*
	* It removes the brand information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		// if(!in_array('deleteBrand', $this->permission)) {
		// 	redirect('dashboard', 'refresh');
		// }
		
		$unit_id = $this->input->post('unit_id');
		$response = array();
		if($unit_id) {
			$delete = $this->model_units->remove($unit_id);

			if($delete == true) {

				$data = array();

				addActivityLog("units", "deleted", $unit_id, "units", 3, $data);

				$response['success'] = true;
				$response['messages'] = "Successfully removed";	
			}
			else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the unit information";
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}

}