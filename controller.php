<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class Store extends CI_Controller {
 

	 public function __construct()



	{

		

		 parent::__construct();

        $this->load->helper('form');

        $this->load->helper('url');

		$this->load->model('Store_model');

		$this->load->model('User_model');

		$this->load->library('session');

 		if($this->session->userdata('book_frant_login_user_id')){

			$this->data['is_login']=1;

			$this->data['login_user_info']=$this->User_model->get_login_information();

		}else{

			$this->data['is_login']=0;

		}
		$this->data['most_purchase_books_footer']=$this->Store_model->get_most_purchase_books_footer();
		$this->data['top_rated_book_footer']=$this->Store_model->get_top_rated_book_footer();
		$this->data['hearder_cart']=$this->User_model->get_header_cart();
		$this->data['logo_rs']=$this->Store_model->get_setting();

 	}

	

		public function index()

	{

	

 		$this->load->helper('url');

		$this->load->library('session');

		$this->load->model('Home_model');

		$this->load->model('Store_model');

		$this->data['menus_html']=$this->Home_model->dyanamic_menu_html(); 

  			$this->load->view('load_css');

			if($this->input->post('search_start')){

				$data['start']=$this->input->post('search_start');

			}else{

				$data['start']=0;

			}

			if($this->input->post('no_of_rc')){

				$data['no_of_rc']=$this->input->post('no_of_rc');

			}else{

				$data['no_of_rc']=10;

			}

			if($this->input->post('sort_by')){

				$data['sort_by']=$this->input->post('sort_by');

			}else{

				$data['sort_by']='new_popular';

			}

			if(@$this->input->post('search_category')){

				$data['search_category']=$this->input->post('search_category');

			}

			if(@$this->input->post('search_author')){

				$data['search_author']=$this->input->post('search_author');

			}

			if(@$this->input->post('search_price')){

				$data['search_price']=$this->input->post('search_price');

			}

			if(@$this->input->post('search_price_slider')){

				$data['search_price_slider']=$this->input->post('search_price_slider');

			}

			$this->data['categories']=$this->Store_model->get_search_category($data);

			$this->data['prices']=$this->Store_model->get_search_by_price();

			$this->data['authors']=$this->Store_model->get_search_author($data);

			$this->data['lsliders']=$this->Store_model->get_long_banner();

			$this->data['ssliders']=$this->Store_model->get_short_banner();

			$this->data['books']=$this->Store_model->get_book_list($data);

			$this->data['latest_review']=$this->Store_model->get_latest_review();

			 

			$this->load->view('header',$this->data);

			

 			$this->load->view('store',$this->data);  

			$this->load->view('footer');

			$this->load->view('load_js');

 	}

 	public function search_book()
 	{
 		
 		  $keyword=$this->input->post('keyword');
 		 
		$this->load->library('session');

		$this->load->model('Home_model');

		$this->load->model('Store_model');

		$this->data['menus_html']=$this->Home_model->dyanamic_menu_html($_POST); 

  		$this->load->view('load_css');

  		$this->data['categories']=$this->Store_model->get_search_category($data);

		$this->data['prices']=$this->Store_model->get_search_by_price();

		$this->data['authors']=$this->Store_model->get_search_author($data);

		$this->data['lsliders']=$this->Store_model->get_long_banner();

		$this->data['ssliders']=$this->Store_model->get_short_banner();

		$this->data['books']=$this->Store_model->get_book_list($data);

		$this->data['latest_review']=$this->Store_model->get_latest_review();

		$this->load->view('header',$this->data);

		$this->load->view('store',$this->data);  

		$this->load->view('footer');

		$this->load->view('load_js');
 	}

	

	 

	public function books()

	{

	
 
 		$this->load->helper('url');
		$cat_id = $this->uri->segment(3);
		$this->load->library('session');
		$this->load->model('Home_model');

		$this->load->model('Store_model');
if($this->input->post('search_query')){

				$data['keyword']=$this->input->post('search_query');

			}else{

				$data['keyword']='';

			}

			if($this->input->post('search_start')){

				$data['start']=$this->input->post('search_start');

			}else{

				$data['start']=0;

			}

			if($this->input->post('no_of_rc')){

				$data['no_of_rc']=$this->input->post('no_of_rc');

			}else{

				$data['no_of_rc']=10;

			}

			if($this->input->post('sort_by')){

				$data['sort_by']=$this->input->post('sort_by');

			}else{

				$data['sort_by']='new_popular';

			}
			
			if(@$cat_id){
				$uni_arra['table']="tbl_book_category";
				$uni_arra['alias_name']=$cat_id;
				$uni_rs=$this->Home_model->get_id_from_unique_name($uni_arra);
 				$data['search_category']=$uni_rs->id;
			}
			
			if(@$this->input->post('search_category')){
				$data['search_category']=$this->input->post('search_category');
			}

if(@$this->input->post('keyword')){
				$data['keyword']=$this->input->post('keyword');
			}
			
			 
			if(@$this->input->post('search_author')){

				$data['search_author']=$this->input->post('search_author');

			}

			if(@$this->input->post('search_price')){

				$data['search_price']=$this->input->post('search_price');

			}

			if(@$this->input->post('search_price_slider')){

				$data['search_price_slider']=$this->input->post('search_price_slider');

			}
		$this->data['menus_html']=$this->Home_model->dyanamic_menu_html($data); 

		$this->data['meta_data'] = $this->Store_model->get_meta_data_cat($cat_id);

		$this->data['meta_data_online'] = $this->Store_model->get_meta_data_online();


  			$this->load->view('load_css',$this->data);

			$this->data['categories']=$this->Store_model->get_search_category($data);

			$this->data['prices']=$this->Store_model->get_search_by_price();

			$this->data['authors']=$this->Store_model->get_search_author($data);

			$this->data['lsliders']=$this->Store_model->get_long_banner();

			$this->data['ssliders']=$this->Store_model->get_short_banner();

			$this->data['books']=$this->Store_model->get_book_list($data);

			$this->data['latest_review']=$this->Store_model->get_latest_review();

			$this->data['cat_id']=$cat_id;


			$this->load->view('header',$this->data);

 			$this->load->view('store',$this->data);  

			$this->load->view('footer');

			$this->load->view('load_js');

 	}
	
	public function f_books()

	{

	
 
 		$this->load->helper('url');
		$cat_id = $this->uri->segment(4);
		$this->load->library('session');
		$this->load->model('Home_model');

		$this->load->model('Store_model');
if($this->input->post('search_query')){

				$data['keyword']=$this->input->post('search_query');

			}else{

				$data['keyword']='';

			}

			if($this->input->post('search_start')){

				$data['start']=$this->input->post('search_start');

			}else{

				$data['start']=0;

			}

			if($this->input->post('no_of_rc')){

				$data['no_of_rc']=$this->input->post('no_of_rc');

			}else{

				$data['no_of_rc']=10;

			}

			if($this->input->post('sort_by')){

				$data['sort_by']=$this->input->post('sort_by');

			}else{

				$data['sort_by']='new_popular';

			}
			
			if(@$cat_id){
 				$data['search_category']=$cat_id;
			}
			
			if(@$this->input->post('search_category')){
				$data['search_category']=$this->input->post('search_category');
			}

if(@$this->input->post('keyword')){
				$data['keyword']=$this->input->post('keyword');
			}
			
			 
			if(@$this->input->post('search_author')){

				$data['search_author']=$this->input->post('search_author');

			}

			 

			 
		$this->data['menus_html']=$this->Home_model->dyanamic_menu_html1($data); 

  			$this->load->view('load_css');

  			

			$this->data['categories']=$this->Store_model->get_search_category($data);

			$this->data['prices']=$this->Store_model->get_search_by_price();

			$this->data['authors']=$this->Store_model->get_search_author($data);

			$this->data['lsliders']=$this->Store_model->get_long_banner();

			$this->data['ssliders']=$this->Store_model->get_short_banner();

			$this->data['books']=$this->Store_model->get_book_list($data);

			$this->data['latest_review']=$this->Store_model->get_latest_review();

			$this->data['cat_id']=$cat_id;

			$this->load->view('f_header',$this->data);

 			$this->load->view('f_store',$this->data);  
 
			$this->load->view('load_js');

 	}
				
 	

}

