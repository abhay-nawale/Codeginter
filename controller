<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Vendor extends CI_Controller {



	public function __construct()

	{

 		parent::__construct();

        $this->load->helper('form');

        $this->load->helper('url');

		$this->load->model('Home_model');

		$this->load->model('Vendor_model');

		$this->load->library('upload');	

		$this->load->library('session');

		$this->data['login_user_info']=$this->Home_model->get_login_information();

 	}

	

	public function index()

	{

		redirect('Home/index');

	}


	public function vendor_list(){

		if($this->Home_model->check_permission('vendor_management','list')){

			$this->load->helper('url');

			$this->data['page_name'] = 'vendor_list';

			$this->data['folder'] = 'vendor';

			$this->data['page_title'] = 'Vendor List'; 

			if($this->input->post('search_query')){

				$search['search_query']=$this->input->post('search_query');

			}else{

				$search['search_query']="";

			}

			if($this->input->post('is_published')>-1){

				$search['is_published']=$this->input->post('is_published');

			}else{

				$search['is_published']=-1;

			}

			$this->data['search'] = $search; 

			$this->data['results']=$this->Vendor_model->get_vendor_list($search);

			$this->load->view('index',@$this->data);

		}else{

			redirect('Home/index');

		}

	}

	public function vendor_add(){

		if(($this->Home_model->check_permission('vendor_management','add'))||($this->Home_model->check_permission('vendor_management','edit'))){

			$this->load->helper('url');

			$this->load->library('session');

			$this->data['page_name'] = 'vendor_add';

			$this->data['folder'] = 'vendor'; 

			if (@$this->uri->segment(3)) {

				$pass['id']=$this->uri->segment(3);

				$this->data['result']=$this->Vendor_model->get_vendor_info($pass);

				$this->data['states'] = $this->Vendor_model->get_all_state();

				$this->data['cities'] = $this->Vendor_model->get_all_cities();

				$this->data['page_title'] = 'Edit Vendor'; 

			}else{

				$this->data['states'] = $this->Vendor_model->get_all_state();

				$this->data['page_title'] = 'Add Vendor';

				

			}

			$this->load->view('index',@$this->data);

		}else{

			redirect('Home/index');

		}		

	}

	public function save_vendor()
	{

		$photo="";
		if ($this->input->post('photo')) {
			$photo=$this->input->post('photo');
			$pop = explode('/',$photo);
			$photo=array_pop($pop);
		}
		$time = TIME();
		if (!empty($_FILES['photo']['name']))
        {	
        	$temp=$photo;
			$photo = $time.$_FILES["photo"]['name'];
			$photo = str_replace(' ', '_', $photo);
			$config['file_name'] = $photo;
			$config['upload_path'] = './uploads/vendor_image/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']	= '50000';
			
			$this->upload->initialize($config);
			$path ="uploads/vendor_image/".$temp;
			if(file_exists($path)){
				unlink($path); }

			if ( ! $this->upload->do_upload('photo'))
			{
				$error = array('error' => $this->upload->display_errors());
				echo $this->upload->display_errors();
				exit();
			}

			$this::thumbnailfile($photo,100,100,'vendor_image');
		}

		$image = 'uploads/vendor_image/'.$photo;

		$thumb_image = 'uploads/thumb/vendor_image/'.$photo;

		$data['id']=$this->input->post('id');

		$data['name']=$this->input->post('name');

		$data['address']=$this->input->post('address');

		$data['city']=$this->input->post('city');

		$data['state']=$this->input->post('state');

		$data['pincode']=$this->input->post('pincode');

		if($this->input->post('is_published')){

			$data['is_published']=$this->input->post('is_published');

		}else{

			$data['is_published']=0;

		}

		$data['telephone_no']=$this->input->post('telephone_no');

		$data['mobile_no']=$this->input->post('mobile_no');

		$data['company_name']=$this->input->post('company_name');

		$data['gst_no']=$this->input->post('gst_no');

		$data['photo']=$image;

		$data['thumb_photo']=$thumb_image;

		$data['created_by']=$this->data['login_user_info']->id;

		$data['created_date']=date('Y-m-d h:i:s');

		$data['updated_by']=$this->data['login_user_info']->id;

		$data['updated_date']=date('Y-m-d h:i:s');



		if($this->Vendor_model->save_vendor($data)){

			if($data['id']){

				$this->session->set_flashdata('successmsg', 'Record Updated');

			}else{

				$this->session->set_flashdata('successmsg', '  Record  Saved ');

			}

			redirect('vendor/vendor-list');

		}else{

			redirect('vendor/vendor-add');

		}

	}


	public function vendor_delete(){

		if(($this->Home_model->check_permission('vendor_management','delete'))){

			if (@$this->uri->segment(3)) {

				$pass['id']=$this->uri->segment(3);

				if($this->Vendor_model->delete_vendor($pass)){

					$this->session->set_flashdata('successmsg', 'Record Deleted');

				}else{

					$this->session->set_flashdata('errorsmsg', 'Record Not Delete');

				}

				redirect('vendor/vendor-list');

			}else{

				redirect('Home/index');

			} 

		}else{

			redirect('Home/index');

		}

		

	}

	function thumbnailfile($filename,$twidth,$theight,$folder)
	{
		$img=IMAGE_PATH.$folder.'/'.$filename;

		//echo $img; die();

		$imagename=$filename;

		$extensions = explode('.',$img);

		$ext = end($extensions);

		$thumbname = $filename;

		switch(strtolower($ext)){

		case 'jpeg':

		case 'jpg':

		$imagesource = imagecreatefromjpeg ($img);

		break;

		case 'gif':

		$imagesource = imagecreatefromgif ($img);

		break;

		case 'png':

		$imagesource = imagecreatefrompng ($img);

		break;

		}

		$iw = imagesx($imagesource);

		$ih = imagesy($imagesource);

		$tw=$twidth;

		$th=$theight;

			//Actual sizes

			 $iw = imagesx($imagesource);

			 $ih= imagesy($imagesource);

			//Resize proportionately

			if($iw >= $tw || $ih >= $th){

				if($iw > $ih){

					$ratio= $iw/$ih;

					$w1= $tw;

					$h1= $tw/$ratio;

				}else{

					$ratio= $ih/$iw;

					$h1= $th;

					$w1= $th/$ratio;

				}

			}else{

				$w1=$iw;

				$h1=$ih;

			}

		$img3 = imagecreatetruecolor( $w1, $h1 );

		if($ext == "gif" or $ext == "png"){

		imagecolortransparent($img3, imagecolorallocatealpha($img3, 0, 0, 0, 127));

		imagealphablending($img3, false);

		imagesavealpha($img3, true);

		}

		// echo THUMB_IMAGE_PATH.$thumbname; die();

		imagecopyresampled(  $img3, $imagesource, 0, 0, 0 , 0, $w1, $h1, $iw, $ih );	

		switch(strtolower($ext)){

		case 'jpeg':

		case 'jpg':

		imagejpeg($img3, THUMB_IMAGE_PATH.$folder.'/'.$thumbname);

		break;

		case 'gif':

		imagegif($img3, THUMB_IMAGE_PATH.$folder.'/'.$thumbname);

		break;

		case 'png':

		imagepng($img3,THUMB_IMAGE_PATH.$folder.'/'.$thumbname);

		break;

		}

		imagedestroy($imagesource);

	}

	function get_sub_city()
	{
		$id=$this->input->post('id'); 
		$data['posts'] = $this->Vendor_model->get_sub_city($id);
		$html= "<select id='city_id' name='city' class='form-control select2'>";
		$html= $html."<option value=''>Select City</option>";
		foreach($data['posts'] as $row)
		{			
			$html= $html."<option value='$row->id'>$row->city_name</option>";
		}	
		$html= $html. "</select>";
		echo $html;
	}


	/************ Vendor MASTER END ****************/

	 

}

