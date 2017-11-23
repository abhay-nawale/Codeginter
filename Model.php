<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Vendor_model extends CI_Model {



	public function __construct()

	{

		$this->load->database();

		$this->load->library('session');

	}

	public function get_vendor_list($data=false){   // list of vendor
 
		$user_info=$this->Home_model->get_login_information(); 

		$this->db->select('v.*, c.city_name');

		$this->db->from(PREFIX.'vendor v');

		$where=" v.name like '%".$data['search_query']."%' OR v.city like '%".$data['search_query']."%' OR v.mobile_no like '%".$data['search_query']."%' OR v.gst_no like '%".$data['search_query']."%'";

		if($data['is_published']>'-1'){

			$where=$where." AND v.is_published = '".$data['is_published']."' ";
		}

		if($user_info->member_type=='member'){
			$where=$where." AND v.created_by = '".$user_info->id."' ";
		}	

		$this->db->join(PREFIX.'city c','c.id = v.city','left');

		$this->db->where($where);

		$this->db->order_by('v.created_date', 'DESC');

		$query = $this->db->get();

		return $data_rn= $query->result();

	}

	public function save_vendor($data)   //save vendor information in database
	{
		$this->load->library('session');

 			if(@$data['id']){ // update

				$this->db->where('id', $data['id']);

				$this->db->update(PREFIX.'vendor', array( 'name' => $data['name'],'address' => $data['address'],'city'=>$data['city'],'state'=>$data['state'],'pincode'=>$data['pincode'],'is_published'=>$data['is_published'],'telephone_no'=>$data['telephone_no'],'mobile_no'=>$data['mobile_no'],'company_name'=>$data['company_name'],'gst_no'=>$data['gst_no'],'photo'=>$data['photo'] ,'thumb_photo'=>$data['thumb_photo'] ,'updated_by'=>$data['updated_by'] ,'updated_date'=>$data['updated_date']));

				$id=$data['id'];

			}else{  //Insert

				$this->db->insert(PREFIX.'vendor', array('id' => NULL ,'name' => $data['name'],'address' => $data['address'],'city'=>$data['city'] ,'state'=>$data['state'],'pincode'=>$data['pincode'],'is_published'=>$data['is_published'],'telephone_no'=>$data['telephone_no'],'mobile_no'=>$data['mobile_no'],'company_name'=>$data['company_name'],'gst_no'=>$data['gst_no'],'photo'=>$data['photo'],'thumb_photo'=>$data['thumb_photo'] ,'created_by' => $data['created_by'] ,'created_date'=>$data['created_date']  ,'updated_by'=>$data['updated_by'] ,'updated_date'=>$data['updated_date']));

				$id=$this->db->insert_id();

			}

 		return $id;
	}


	public function get_vendor_info($data)  // get vendor information by id
	{
		
		$this->db->select('*');

		$this->db->from(PREFIX.'vendor');

		$this->db->where('id',$data['id']); 

		$query = $this->db->get();

		return $data_rn= $query->row();

	}

	function delete_vendor($data)  //delete vendor
	{
		$this->db->select('photo');
		$this->db->where('id',$data['id']);
		$this->db->from(PREFIX.'vendor');
		$query = $this->db->get();
		$row = $query->row();

			if (isset($row->photo))    //delete photo
			{
				$path =$row->photo;
				$pop = explode('/',$path);
				$img = array_pop($pop);
				$path1 ="uploads/vendor_image/".$img;
				if(file_exists($path1) && !is_dir($path1)){
				unlink($path1); }
			}

		$this->db->where('id', $data['id']);
		$this->db->delete(PREFIX.'vendor');
   		return 1;
	}

	public function get_all_state()   //get all state
	{
		$this->db->select('*');

		$this->db->from(PREFIX.'state');

		$query = $this->db->get();

		return $data_rn= $query->result();
	}

	public function get_sub_city($id)  //get all city by state
	{
		$this->db->select('*');

		$this->db->where('state_id',$id);

		$this->db->from(PREFIX.'city');

		$query = $this->db->get();

		return $data_rn= $query->result();
	}


	public function get_all_cities()  //get all city 
	{
		$this->db->select('*');

		$this->db->from(PREFIX.'city');

		$query = $this->db->get();

		return $data_rn= $query->result();
	}


	/************ Vendor MASTER END ****************/

}

