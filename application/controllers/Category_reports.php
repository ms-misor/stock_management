<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_reports extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();

		$this->data['page_title'] = 'Stores';
		$this->load->model('model_reports');
		$this->load->model('model_category');

		date_default_timezone_set('Asia/Dhaka');
	}

	/* 
    * It redirects to the report page
    * and based on the year, all the orders data are fetch from the database.
    */
	public function index()
	{

		$this->data['categories'] = $this->model_category->getActiveCategroy();

		// echo "<pre>";
		// print_r($this->data);
		// exit;

		$this->render_template('reports_category/category_report', $this->data);
	}

	public function category_report_search()
	{

		$this->data['categories'] = $this->model_category->getActiveCategroy();

		$this->data['purchases'] = $this->model_reports->get_total_purchase_products($this->input->post());
		// $this->data['sales'] = $this->model_reports->get_total_sale_products($this->input->post());

		$this->data['category']   = $this->input->post('category');
		// $this->data['start_date'] = $this->input->post('start_date');
		// $this->data['end_date']   = $this->input->post('end_date');

		// echo "<pre>";
		// print_r($this->data);
		// exit;

		$this->render_template('reports_category/category_report_searched', $this->data);
	}
}	