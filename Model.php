<?php



defined('BASEPATH') OR exit('No direct script access allowed');







class Store_model extends CI_Model { 







	public function __construct()



        {



                $this->load->database();



        }



		



	public function get_search_category(){



	



		$return_data=array();



			$this->load->library('session');



			$this->db->select( '  c.id ,c.category_name ,(select  count(b.id) FROM tbl_book as b where b.category_id = c.id  AND b.status = 1 ) as total_records ');



			$this->db->from(' `tbl_book_category` as c ');



 			$whquery = ' (select  count(b.id) FROM tbl_book as b where b.category_id = c.id  AND b.status = 1 )> 0  '; 



         	$this->db->where($whquery); 



 			$this->db->order_by('c.category_name', 'asc');



   			$query = $this->db->get();



			$return_rs=$query->result();



  			return $return_rs;



	}



	



	public function get_search_author($data=false){



	 



		$return_data=array();



			$this->load->library('session');



			$this->db->select( '  c.id ,c.name , c.image_alt ,(select  count(b.id) FROM tbl_book as b where b.author_id = c.id  AND b.status = 1 ) as total_records ');



			$this->db->from(' `tbl_author` as c ');



 			$whquery = ' ((select  count(b.id) FROM tbl_book as b where b.author_id = c.id  AND b.status = 1 )> 0 ) '; 
                        if(strlen($data['search_category'])){
                          $whquery =$whquery ." AND  ( c.id  IN ( select author_id from tbl_book where category_id IN ( ".$data['search_category']." ) ))";
                        }



         	$this->db->where($whquery); 



 			$this->db->order_by('c.name', 'asc');

$this->db->group_by('c.name' );


   			$query = $this->db->get();



			$return_rs=$query->result();



  			return $return_rs;



	}



	



	public function get_search_by_price(){



	



		$return_data=array();



			$this->load->library('session');



			$setting=Store_model::get_setting();



			$prices=explode('@',$setting->search_price);



			for($i=0;$i<count($prices);$i++){



				$return=array();



				$show_p=explode('-',$prices[$i]);



				if($i==0){



					$return_rs['min']=$show_p[0];



				}



				if(($i+1)==(count($prices))){



					$return_rs['max']=$show_p[1];



				}



				$this->db->select( ' count(b.id) as total_records ');



				$this->db->from(' tbl_book as b ');



				$whquery = '( price >= "'.$show_p[0].'") AND ( price <= "'.$show_p[1].'" )'; 



				$this->db->where($whquery);



				$query = $this->db->get();



				$data_rn= $query->row();



				



				$return['show_price']='$'.$show_p[0].' - $'.$show_p[1];



				$return['price']=$prices[$i];



				$return['no_records']=$data_rn->total_records;



				$return_rs['prices'][]=$return;



				



			}



   			return $return_rs;



	}



	



	 



	 public function get_setting(){



	 		$this->db->select('*');



			$this->db->from('tbl_setting');



			$this->db->where('id','1');



			$query = $this->db->get();



			return $data_rn= $query->row();



	 }



	 



	 public function get_long_banner(){



	



		$return_data=array();



			$this->load->library('session');



			$this->db->select( '  * ');



			$this->db->from(' tbl_banner ');



 			$whquery = ' status = 1 AND banner_type ="long_banner"'; 



         	$this->db->where($whquery); 



 			$this->db->order_by('created_date', 'DESC');



   			$query = $this->db->get();



			$return_rs=$query->result();



  			return $return_rs;



	}



	public function get_short_banner(){



	



		$return_data=array();



			$this->load->library('session');



			$this->db->select( '  * ');



			$this->db->from(' tbl_banner ');



 			$whquery = ' status = 1 AND banner_type ="short_banner"'; 



         	$this->db->where($whquery); 



 			$this->db->order_by('created_date', 'DESC');



   			$query = $this->db->get();



			$return_rs=$query->result();



  			return $return_rs;



	}



	public function get_book_detail($data){



		$this->load->library('session');



		$this->db->select( ' b.* ,u.name as author_name   ,u.image_alt , s.sign ,  b.tax_group_id,t.tax_id  , c.category_name , ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as book_image  ');



  		$this->db->from('tbl_book as b');

                $this->db->join('tbl_book_category c', 'c.id = b.category_id', 'left');


		$this->db->join('tbl_author u', 'u.id = b.author_id', 'left');



		$this->db->join('tbl_taxes_group t', 't.id = b.tax_group_id', 'left');



 		$this->db->join('tbl_currency s', 's.id = b.currency_id', 'left');



 		$this->db->where('b.status', '1'); 



		$this->db->where('b.id', $data['id']);



		$query = $this->db->get();



		return $data_rn= $query->row(); 



	}


	function get_meta_data($data1)
	{

		$this->load->library('session');

		$this->db->select('meta_title, meta_keywords, meta_description');

		$this->db->where('id', $data1['id']);

  		$this->db->from( ' tbl_book ');

		$query = $this->db->get();

		return $data_rn= $query->row(); 

	}

	function get_meta_data_cat($cat_id)
	{
		$this->load->library('session');

		$this->db->select('meta_title, meta_keywords, meta_description');

		$this->db->where('id',$cat_id);

  		$this->db->from( ' tbl_book_category ');

		$query = $this->db->get();

		return $data_rn= $query->row(); 
	}



 
	



	public function get_book_detail_images($data){



		$this->load->library('session');



		$this->db->select( ' *');



  		$this->db->from('tbl_book_images');



 		$this->db->where('book_id', $data['id']);



		$query = $this->db->get();



		$return_rs=$query->result();



  		return $return_rs;



	}



	public function get_related_book_list($data){
 			$this->load->library('session');
 			$this->db->select( ' * ');
			$this->db->from('tbl_book ');
 			$this->db->where('id', $data['id']);
 			$query = $this->db->get();
 			$data_rn= $query->row();
 			$this->db->select( ' b.id , b.alias_name, b.name , b.average_rate , b.price, b.overview , b.image_alt , ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image ,u.name as author_name   , s.sign');
   			$this->db->from('tbl_book as b');
 			$this->db->join('tbl_author u', 'u.id = b.author_id', 'left');
  			$this->db->join('tbl_currency s', 's.id = b.currency_id', 'left');
  			$this->db->where('b.status', '1'); 
 			$whquery=" b.id !='".$data_rn->id."' AND b.category_id ='".$data_rn->category_id."'";
 			$this->db->where($whquery);  
 			$this->db->order_by('b.created_date , b.average_rate', 'DESC');
 			$this->db->limit( 10 );
 			$query = $this->db->get();
 			$return_rs=$query->result();
  			return $return_rs;
 	}



	



	public function get_featured_book_list(){



			$this->load->library('session');



 			$this->db->select( ' b.id ,b.alias_name,  b.name , b.average_rate , b.price, b.overview ,b.image_alt, ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image ,u.name as author_name   , s.sign');



  			$this->db->from('tbl_book as b');



			$this->db->join('tbl_author u', 'u.id = b.author_id', 'left');



 			$this->db->join('tbl_currency s', 's.id = b.currency_id', 'left');



 			$this->db->where('b.status', '1'); 



			$this->db->where('b.is_featured', '1'); 



 			$this->db->order_by('b.created_date , b.average_rate', 'DESC');



			$this->db->limit( 15 );



			$query = $this->db->get();



			$return_rs=$query->result();



			return $return_rs;



				 



	}



	



	public function get_most_rated_book_list(){



			$this->load->library('session');



 			$this->db->select( ' b.id , b.alias_name, b.name , b.average_rate , b.price, b.overview , b.image_alt , ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image ,u.name as author_name   , s.sign');



  			$this->db->from('tbl_book as b');



			$this->db->join('tbl_author u', 'u.id = b.author_id', 'left');



 			$this->db->join('tbl_currency s', 's.id = b.currency_id', 'left');



 			$this->db->where('b.status', '1'); 



  			$this->db->order_by(' b.average_rate', 'DESC');



			$this->db->limit( 3 );



			$query = $this->db->get();



			$return_rs=$query->result();



			return $return_rs;



				 



	}



	



	public function get_tax_price_product($data){



		$this->load->library('session');



		$this->db->select( ' SUM(total_amount) as total_amount  ');



  		$this->db->from( ' tbl_taxes ');



 		$whquery= " id IN (".$data['tax_id'].")";



		$this->db->where($whquery); 



		$query = $this->db->get();



		return $data_rn= $query->row(); 



	}



	



	public function get_book_list($data)
	{
			$start=$data['start'];  
			$no_reord=$data['no_of_rc'];
			$return_data=array();
			$this->load->library('session');
			$this->db->select( ' b.id ,  b.name , b.average_rate , b.price, b.overview ,b.alias_name ,b.image_alt, ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image ,u.name as author_name   , s.sign');
  			$this->db->from('tbl_book as b');
			$this->db->join('tbl_author u', 'u.id = b.author_id', 'left');
 			$this->db->join('tbl_currency s', 's.id = b.currency_id', 'left');
 			$this->db->where('b.status', '1'); 
			if($data['sort_by']=='new_popular'){
 				$this->db->order_by('b.created_date , b.average_rate', 'DESC');
			}elseif($data['sort_by']=='low_high'){
				$this->db->order_by('b.price', 'ASC');
			}elseif($data['sort_by']=='high_low'){
				$this->db->order_by('b.price', 'DESC');
			}elseif($data['sort_by']=='reviews'){
				$this->db->order_by('b.average_rate', 'DESC');
			}elseif($data['sort_by']=='a_z'){
				$this->db->order_by('b.name', 'ASC');
			}elseif($data['sort_by']=='z_a'){
				$this->db->order_by('b.name', 'DESC');
			}
			if(strlen(@$data['search_category'])){
				$whquery= " b.category_id IN (".$data['search_category'].")";
				$this->db->where($whquery); 
 			}
                        if(strlen(@$data['keyword'])){
				$whquery= " (( b.name like '%".$data['keyword']."%' )OR( u.name like '%".$data['keyword']."%' )OR( b.tags like '%".$data['keyword']."%' ))  ";
				$this->db->where($whquery); 
 			}
			if(strlen(@$data['search_author'])){
 				$whquery= " b.author_id IN (".$data['search_author'].")";
				$this->db->where($whquery); 
			}
			if(@$data['search_price_slider']){
				 $whquery="";
				$price1=explode('-',$data['search_price_slider']);
				$whquery = $whquery.'((  b.price >= "'.$price1[0].'" )AND( b.price <= "'.$price1[1].'" ))'; 
				$this->db->where($whquery); 
				$return_rs['max_value']= $price1[0];
				$return_rs['min_value']= $price1[1];
				$return_rs['search_price_slider']= $data['search_price_slider'];
			} 
			if(@$data['search_price']){
				 $whquery="";
				$prices_arrs=explode(',',$data['search_price']);
				$k=0;
				foreach($prices_arrs as $prices_arr){
				if($k>0){
					$whquery=$whquery." OR ";
				}
				$price=explode('-',$prices_arr);
				$whquery = $whquery.'((  b.price >= "'.$price[0].'" )AND( b.price <= "'.$price[1].'" ))'; 
         		$k++;
				}
				$whquery="(".$whquery.")";
				$this->db->where($whquery); 
			}
 			$this->db->limit( $no_reord , $start );
   			$query = $this->db->get();
			$return_rs['records']=$query->result();
			$this->db->select( ' b.id ,  b.name  , b.alias_name, b.average_rate , b.price, b.overview , b.image_alt ,( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image ,u.name as author_name   , s.sign');
  			$this->db->from('tbl_book as b');
			$this->db->join('tbl_author u', 'u.id = b.author_id', 'left');
 			$this->db->join('tbl_currency s', 's.id = b.currency_id', 'left');
 			$this->db->where('b.status', '1'); 
			if($data['sort_by']=='new_popular'){
 				$this->db->order_by('b.created_date , b.average_rate', 'DESC');
			}elseif($data['sort_by']=='low_high'){
				$this->db->order_by('b.price', 'ASC');
			}elseif($data['sort_by']=='high_low'){
				$this->db->order_by('b.price', 'DESC');
			}elseif($data['sort_by']=='reviews'){
				$this->db->order_by('b.average_rate', 'DESC');
			}
			if(strlen(@$data['search_category'])){
				$whquery= " b.category_id IN (".$data['search_category'].")";
				$this->db->where($whquery); 
 			}
			if(strlen(@$data['keyword'])){
				$whquery= " (( b.name like '%".$data['keyword']."%' )OR( u.name like '%".$data['keyword']."%' )OR( b.tags like '%".$data['keyword']."%' ))  ";
				$this->db->where($whquery); 
 			}
			if(strlen(@$data['search_author'])){
 				$whquery= " b.author_id IN (".$data['search_author'].")";
				$this->db->where($whquery); 
			}
			if(@$data['search_price_slider']){
				 $whquery="";
				$price1=explode('-',$data['search_price_slider']);
				$whquery = $whquery.'((  b.price >= "'.$price1[0].'" )AND( b.price <= "'.$price1[1].'" ))'; 
				$this->db->where($whquery); 
				$return_rs['min_value']= $price1[0];
				$return_rs['max_value']= $price1[1];
				$return_rs['search_price_slider']= $data['search_price_slider'];
			} 
			if(@$data['search_price']){
				 $whquery="";
				$prices_arrs=explode(',',$data['search_price']);
				$k=0;
				foreach($prices_arrs as $prices_arr){
				if($k>0){
					$whquery=$whquery." OR ";
				}
				$price=explode('-',$prices_arr);
				$whquery = $whquery.'((  b.price >= "'.$price[0].'" )AND( b.price <= "'.$price[1].'" ))'; 
         		$k++;
				}
				$whquery="(".$whquery.")";
				$this->db->where($whquery); 
			}
   			$query = $this->db->get();
			$return_rs['total_records']= $query->num_rows();
			$return_rs['sort_by']= $data['sort_by'];
			$return_rs['search_author']= @$data['search_author'];
			$return_rs['search_category']= @$data['search_category'];
			$return_rs['search_price']= @$data['search_price'];
			$return_rs['search_price_slider']= @$data['search_price_slider'];
			$return_rs['show_per_page']= $no_reord;
 			$return_rs['from']= ($start+1);
			$return_rs['current_rc']= $return_rs['from'];
			if($return_rs['total_records']<(($start)+$no_reord)){
				$return_rs['to']=$return_rs['total_records'];
			}else{
 				$return_rs['to']=($start+$no_reord);
			}
  			return $return_rs;
	}



	



	function get_book_add_review($data){



		$return_val=0;



		$this->load->library('session');



 			if(@$this->session->userdata('book_frant_login_user_id')){



				$this->db->select( ' c.* ');



				$this->db->from( ' tbl_user_cart as c ');



				$this->db->join('tbl_user_cart_detail d', 'c.id = d.cart_id', 'left');



				$whquery= "  c.user_id ='".$this->session->userdata('book_frant_login_user_id')."' AND c.user_type like 'register' AND c.payment_status like 'Completed' AND d.book_id like '".$data['id']."'";



				$this->db->where($whquery); 



				$query = $this->db->get();



				$data_rn= $query->row(); 



				if(@$data_rn->id){



					$this->db->select( ' *  ');



					$this->db->from( ' tbl_user_book_reviews ');



					$whquery= "  user_id like '".$this->session->userdata('book_frant_login_user_id')."' AND book_id like '".$data['id']."'";



					$this->db->where($whquery); 



					$query = $this->db->get();



					$data_rs= $query->row(); 



					if(@$data_rs->id){



						$return_val=2;



					}else{



						$return_val=1;



					}



				}else{



					$return_val=0;



				}



				



			}else{



				$return_val=0;



			}



		return $return_val;



	}



	



	function get_book_wishlist($data){



		$return_val=0;



		$this->load->library('session');



 			if(@$this->session->userdata('book_frant_login_user_id')){



				$this->db->select( ' * ');



				$this->db->from( ' tbl_user_book_wishlist ');



				$whquery= "  user_id ='".$this->session->userdata('book_frant_login_user_id')."' AND  book_id like '".$data['id']."'";



				$this->db->where($whquery); 



				$query = $this->db->get();



				$data_rn= $query->row(); 



				if(@$data_rn->id){



					$return_val=1;



				}else{



					$return_val=0;



				}



			}else{



				$return_val=0;



			}



		return $return_val;



	



	



	}


 

	function get_featured_author()

	{

		$this->db->select("  a.* ,  (select count(b.id) from tbl_book as b where  b.author_id = a.id and b.status = '1' ) as count_book ");

		$this->db->from('tbl_author a');
		
		$this->db->where('is_featured', 1);
		
		$this->db->where('a.status', 1);
		
		$this->db->order_by('count_book ', 'DESC');
  		 

		$query = $this->db->get();

		return $query->result();

	}



	function get_author_book_image($data)

	{

		$this->db->select("b.id, b.name as b_name, b.price, b.average_rate , b.id ,b.alias_name, b.image_alt, ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image , b.overview");

		$this->db->from('tbl_book b');
 		
		$this->db->where('b.status', 1);
		
		$this->db->where('b.author_id', $data['author_id']);

		$this->db->limit(5);

		$query = $this->db->get();

		return $query->result();

	}



	function get_most_purchase_books()

	{

		$this->db->select("a.*, b.name as b_name, b.price, b.average_rate , b.id  ,b.alias_name,b.image_alt,  ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image, (select count(pu.book_id) from tbl_user_cart_detail as pu where pu.book_id = b.id ) as purchase_count ");

		$this->db->from('tbl_author a');

		$this->db->join('tbl_book b', 'a.id = b.author_id', 'left');
 		
		$this->db->where('b.status', 1);

		$this->db->limit(5);
		
		$this->db->order_by('purchase_count ', 'DESC');
		
		$query = $this->db->get();

		return $query->result();

	}



	function get_most_purchase_books_footer()

	{

		$this->db->select(" a.*, b.alias_name , b.name as b_name, b.price, b.average_rate , b.id  , b.image_alt,( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image , (select count(pu.book_id) from tbl_user_cart_detail as pu where pu.book_id = b.id ) as purchase_count ");

		$this->db->from('tbl_author a');

		$this->db->join('tbl_book b', 'a.id = b.author_id', 'left');
 		
		$this->db->where('b.status', 1);

		$this->db->limit(6);
		
		$this->db->order_by('purchase_count ', 'DESC');
		
		$query = $this->db->get();

		return $query->result();

	}

	

	function get_top_rated_book_footer()

	{

		$this->db->select("a.*, b.name as b_name, b.price, b.average_rate , b.id ,b.alias_name,b.image_alt,  ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image");

		$this->db->from('tbl_author a');

		$this->db->join('tbl_book b', 'a.id = b.author_id', 'left');
 
		$this->db->where('b.status', 1);

		$this->db->limit(6);
		
		$this->db->order_by('b.average_rate ', 'DESC');

		$query = $this->db->get();

		return $query->result();

	}



	function get_latest_review()

	{

		$this->db->select("a.*, b.name as b_name, b.price, b.average_rate, b.overview,  d.user_name ,b.id, b.alias_name,b.image_alt, ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image");

		$this->db->from('tbl_author a');

		$this->db->join('tbl_book b', 'a.id = b.author_id', 'left');
   
		$this->db->join('tbl_user_book_reviews d', 'b.id = d.book_id', 'left');
    
		$this->db->where('d.status', 1);

		$this->db->limit(5);
		
		$this->db->order_by('d.created_date ', 'DESC');

		$query = $this->db->get();

		return $query->result();

	}



	function get_testimonials()

	{

		$this->db->select("*");

		$this->db->from('tbl_testimonial');

		$query = $this->db->get();

		return $query->result();

	}



	function get_latest_review_details($data)

	{

		$this->db->select("a.*, b.name as b_name, b.price,b.alias_name,b.image_alt, b.overview, b.average_rate, d.user_name, d.summary, d.review ,b.id,  ( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image");

		$this->db->from('tbl_author a');

		$this->db->join('tbl_book b', 'a.id = b.author_id', 'left');
 
		$this->db->join('tbl_user_book_reviews d', 'b.id = d.book_id', 'left');

 
        $this->db->where('d.status', 1);
			   
		$this->db->order_by('d.created_date ', 'DESC');

		$this->db->limit(3);

		$query = $this->db->get();

		return $query->result();

	}



	function get_customer_reviews($data)

	{

		$this->db->select("a.*, b.name as b_name, b.alias_name ,b.overview, b.average_rate, d.user_name, d.review , b.id , u.name , d.created_date , d.star , b.image_alt");

		$this->db->from('tbl_author a');

		$this->db->join('tbl_book b', 'a.id = b.author_id', 'left');

		$this->db->join('tbl_user_book_reviews d', 'b.id = d.book_id', 'left');
		$this->db->join('tbl_frant_users u', 'u.id = d.user_id', 'left');

        $this->db->where('d.status', 1);
 		$this->db->where('b.id', $data['id']);
		$this->db->limit(3);
		$this->db->order_by('d.created_date ', 'DESC');
		$query = $this->db->get();
return $query->result();

	}
	function get_customer_all_reviews($data)

	{

		$this->db->select("a.*, b.name as b_name, b.overview, b.average_rate, d.user_name, d.review , b.id , u.name , d.created_date , d.star  , b.image_alt");

		$this->db->from('tbl_author a');

		$this->db->join('tbl_book b', 'a.id = b.author_id', 'left');

		$this->db->join('tbl_user_book_reviews d', 'b.id = d.book_id', 'left');
		$this->db->join('tbl_frant_users u', 'u.id = d.user_id', 'left');

        $this->db->where('d.status', 1);
 		$this->db->where('b.id', $data['id']);
 		$this->db->order_by('d.created_date ', 'DESC');
		$query = $this->db->get();

		return $query->result();

	}

	function save_enquiry($data)

	{

		$this->db->insert('tbl_contactus',$data);

		return $this->db->insert_id();

	}

        public function get_reviews_counts($data=false){

              	$this->db->select( ' *');

  		$this->db->from('tbl_user_book_reviews');

		$this->db->where('status', '1'); 

		$this->db->where('book_id', $data['id']); 

		$query = $this->db->get();

		return $query->num_rows();

        }


public function get_sp_book_detail_here($data=false){

$this->db->select( ' b.id ,  b.name , b.average_rate , b.price, b.overview , b.alias_name , b.image_alt,( select image from tbl_book_images where book_id = b.id order by is_default desc limit 0 , 1 ) as image ,u.name as author_name   , s.sign');
   			$this->db->from('tbl_book as b');
 			$this->db->join('tbl_author u', 'u.id = b.author_id', 'left');
  			$this->db->join('tbl_currency s', 's.id = b.currency_id', 'left');
  			$this->db->where('b.status', '1'); 
 			$whquery=" b.id ='".$data['id']."' ";
 			$this->db->where($whquery);  
			
			$query = $this->db->get();
			return $data_rn= $query->row(); 

}


function get_cart_is_purchase_data($data){

 		$return_val=0;

 		$this->load->library('session');
  			if(@$this->session->userdata('book_frant_login_user_id')){
 				$this->db->select( ' c.* ');
 				$this->db->from( ' tbl_user_cart as c ');
 				$this->db->join('tbl_user_cart_detail d', 'c.id = d.cart_id', 'left');
 				$whquery= "  c.user_id ='".@$this->session->userdata('book_frant_login_user_id')."' AND c.user_type like 'register' AND c.payment_status like 'Completed' AND d.book_id like '".@$data['id']."'";
 				$this->db->where($whquery); 
				$this->db->order_by('c.created_date ', 'DESC');
 				$query = $this->db->get();
 				$data_rn= $query->row(); 
				return $data_rn;
			}else{
 		return $return_val;
		}



	}

	function get_meta_home()
	{
		$this->db->select('*');
		$this->db->where('page_type','Home Page');
		$this->db->from('tbl_meta_data');
		$query = $this->db->get();
		return $query->row();
	}

	function get_meta_contact()
	{
		$this->db->select('*');
		$this->db->where('page_type','Contact Us');
		$this->db->from('tbl_meta_data');
		$query = $this->db->get();
		return $query->row();
	}

	function get_meta_login()
	{
		$this->db->select('*');
		$this->db->where('page_type','Login & Sign up');
		$this->db->from('tbl_meta_data');
		$query = $this->db->get();
		return $query->row();
	}

	function get_meta_privacy_and_policies()
	{
		$this->db->select('*');
		$this->db->where('page_type','Privacy & Policies');
		$this->db->from('tbl_meta_data');
		$query = $this->db->get();
		return $query->row();
	}

	function get_meta_profile()
	{
		$this->db->select('*');
		$this->db->where('page_type','My Profile');
		$this->db->from('tbl_meta_data');
		$query = $this->db->get();
		return $query->row();
	}

	function get_meta_order()
	{
		$this->db->select('*');
		$this->db->where('page_type','My Orders');
		$this->db->from('tbl_meta_data');
		$query = $this->db->get();
		return $query->row();
	}

	function get_meta_data_online()
	{
		$this->db->select('*');
		$this->db->where('page_type','Online Store');
		$this->db->from('tbl_meta_data');
		$query = $this->db->get();
		return $query->row();
	}
	

 }



