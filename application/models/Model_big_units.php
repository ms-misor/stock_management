<?php 

class Model_big_units extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getBigUnitData($id = null)
	{

		return $result = $this->db->select('big_units.*,units.name as small_unit_name') 
             ->from('big_units')
             ->join('units', 'big_units.unit_id = units.id', 'left')
             ->order_by('big_units.create_date ','desc')
             ->get()
             ->result();
	}

	/*get the active brands information*/
	public function getActiveUnits()
	{
		$sql = "SELECT * FROM `units` WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/*get the active brands information*/
	public function getActiveBigUnits()
	{
		$sql = "SELECT * FROM `big_units` WHERE active = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	/* get the getBigUnitDataByName */
	public function getBigUnitDataByName($name = null)
	{

		return $result = $this->db->select('*') 
             ->from('big_units')
             ->where('name',$name)
             ->get()
             ->row();
	}

		/* get the getBigUnitDataById */
	public function getBigUnitDataById($id = null)
	{

		return $result = $this->db->select('*') 
             ->from('big_units')
             ->where('big_unit_id',$id)
             ->get()
             ->row();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('big_units', $data);

			return $insert_id = $this->db->insert_id();
			
			// return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('big_unit_id', $id);
			$update = $this->db->update('big_units', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('big_unit_id', $id);
			$delete = $this->db->delete('big_units');
			return ($delete == true) ? true : false;
		}
	}

}