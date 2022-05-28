<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crud extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    ////////////////// CLEAR CACHE ///////////////////
	public function clear_cache() {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
	
	//////////////////// C - CREATE ///////////////////////
	public function create($table, $data) {
		$this->db->insert($table, $data);
		return $this->db->insert_id();	
	}
	
	//////////////////// R - READ /////////////////////////
	public function read($table, $limit='', $offset='') {
		$query = $this->db->order_by('id', 'DESC');
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function read_order($table, $field, $type, $limit='', $offset='') {
		$query = $this->db->order_by($field, $type);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function read_single($field, $value, $table, $limit='', $offset='') {
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->where($field, $value);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function read_single_order($field, $value, $table, $or_field, $or_value, $limit='', $offset='') {
		$query = $this->db->order_by($or_field, $or_value);
		$query = $this->db->where($field, $value);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function read_like($field, $value, $table, $limit='', $offset='') {
		$query = $this->db->like($field, $value);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function read_like2($field, $value, $field2, $value2, $table, $limit='', $offset='') {
		$query = $this->db->like($field, $value);
		$query = $this->db->or_like($field2, $value2);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function read_like3($field, $value, $field2, $value2, $field3, $value3, $table, $limit='', $offset='') {
		$query = $this->db->like($field, $value);
		$query = $this->db->or_like($field2, $value2);
		$query = $this->db->or_like($field3, $value3);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function read_field($field, $value, $table, $call) {
		$return_call = '';
		$getresult = $this->read_single($field, $value, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

	public function read_field2($field, $value, $field2, $value2, $table, $call) {
		$return_call = '';
		$getresult = $this->read2($field, $value, $field2, $value2, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}

	public function read_field3($field, $value, $field2, $value2, $field3, $value3, $table, $call) {
		$return_call = '';
		$getresult = $this->read3($field, $value, $field2, $value2, $field3, $value3, $table);
		if(!empty($getresult)) {
			foreach($getresult as $result)  {
				$return_call = $result->$call;
			}
		}
		return $return_call;
	}
	
	public function read2($field, $value, $field2, $value2, $table, $limit='', $offset='') {
		$query = $this->db->order_by('id', 'desc');
		$query = $this->db->where($field, $value);
		$query = $this->db->where($field2, $value2);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}

	public function read2_order($field, $value, $field2, $value2, $table, $or_field, $or_value, $limit='', $offset='') {
		$query = $this->db->order_by($or_field, $or_value);
		$query = $this->db->where($field, $value);
		$query = $this->db->where($field2, $value2);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function read3($field, $value, $field2, $value2, $field3, $value3, $table, $limit='', $offset='') {
		$query = $this->db->order_by('id', 'DESC');
		$query = $this->db->where($field, $value);
		$query = $this->db->where($field2, $value2);
		$query = $this->db->where($field3, $value3);
		if($limit && $offset) {
			$query = $this->db->limit($limit, $offset);
		} else if($limit) {
			$query = $this->db->limit($limit);
		}
		$query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function check($field, $value, $table){
		$query = $this->db->where($field, $value);
		$query = $this->db->get($table);
		return $query->num_rows();
	}
	
	public function check2($field, $value, $field2, $value2, $table){
		$query = $this->db->where($field, $value);
		$query = $this->db->where($field2, $value2);
		$query = $this->db->get($table);
		return $query->num_rows();
	}
	
	public function check3($field, $value, $field2, $value2, $field3, $value3, $table){
		$query = $this->db->where($field, $value);
		$query = $this->db->where($field2, $value2);
		$query = $this->db->where($field3, $value3);
		$query = $this->db->get($table);
		return $query->num_rows();
	}
	
	//////////////////// U - UPDATE ///////////////////////
	public function update($field, $value, $table, $data) {
		$this->db->where($field, $value);
		$this->db->update($table, $data);
		return $this->db->affected_rows();	
	}
	
	//////////////////// D - DELETE ///////////////////////
	public function delete($field, $value, $table) {
		$this->db->where($field, $value);
		$this->db->delete($table);
		return $this->db->affected_rows();	
	}
	//////////////////// END DATABASE CRUD ///////////////////////
	
	//////////////////// DATATABLE AJAX CRUD ///////////////////////
	public function datatable_query($table, $column_order, $column_search, $order, $where='') {
		// where clause
		if(!empty($where)) {
			foreach($where as $k=>$v) {
				$this->db->where($k, $v);
			}
			//$this->db->where(key($where), $where[key($where)]);
		}
		
		$this->db->from($table);
 
		// here combine like queries for search processing
		$i = 0;
		if($_POST['search']['value']) {
			foreach($column_search as $item) {
				if($i == 0) {
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				
				$i++;
			}
		}
		 
		// here order processing
		if(isset($_POST['order'])) { // order by click column
			$this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else { // order by default defined
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
 
	public function datatable_load($table, $column_order, $column_search, $order, $where='') {
		$this->datatable_query($table, $column_order, $column_search, $order, $where);
		
		if($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		
		$query = $this->db->get();
		return $query->result();
	}
 
	public function datatable_filtered($table, $column_order, $column_search, $order, $where='') {
		$this->datatable_query($table, $column_order, $column_search, $order, $where);
		$query = $this->db->get();
		return $query->num_rows();
	}
 
	public function datatable_count($table, $where='') {
		$this->db->select("*");
		
		// where clause
		if(!empty($where)) {
			$this->db->where(key($where), $where[key($where)]);
		}
		
		$this->db->from($table);
		return $this->db->count_all_results();
	} 
	//////////////////// END DATATABLE AJAX CRUD ///////////////////

	public function query_invoice_by_year($store) {
		$query = $this->db->order_by('id', 'desc');
		$query = $this->db->where('store_id', $store);
		$query = $this->db->like('pay_date', date('Y'));
		$query = $this->db->get('org_invoice');
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function query_invoice_by_dates($store, $start, $end) {
		$query = $this->db->order_by('id', 'desc');
		$query = $this->db->where('store_id', $store);
		$query = $this->db->where('pay_date >=', date('Y-m-d', strtotime($start)));
		$query = $this->db->where('pay_date <=', date('Y-m-d', strtotime($end)));
		$query = $this->db->get('org_invoice');
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}

	public function query_vat_by_year($store) {
		$query = $this->db->order_by('id', 'desc');
		$query = $this->db->where('store_id', $store);
		$query = $this->db->like('pay_date', date('Y'));
		$query = $this->db->get('org_vat_payment');
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	public function query_vat_by_dates($store, $start, $end) {
		$query = $this->db->order_by('id', 'desc');
		$query = $this->db->where('store_id', $store);
		$query = $this->db->where('pay_date >=', date('Y-m-d', strtotime($start)));
		$query = $this->db->where('pay_date <=', date('Y-m-d', strtotime($end)));
		$query = $this->db->get('org_vat_payment');
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	
	//////////////////// NOTIFICATION CRUD ///////////////////////
	public function msg($type = '', $text = ''){
		if($type == 'success'){
			$icon = 'si si-check';
			$icon_text = 'Successful!';
			$icon_color = 'text-white';
		} else if($type == 'info'){
			$icon = 'si si-info';
			$icon_text = 'Head up!';
			$icon_color = '';
		} else if($type == 'warning'){
			$icon = 'ft ft-exclamation-triangle';
			$icon_text = 'Please check!';
			$icon_color = '';
		} else if($type == 'danger'){
			$icon = 'si si-close';
			$icon_text = 'Oops!';
			$icon_color = 'text-white';
		}
		
		return '
			<div class="col-xs-12">
				<div class="alert bg-'.$type.' alert-icon-left alert-arrow-left alert-dismissible mb-2 '.$icon_color.' text-white" role="alert">
					<span class="alert-icon"><i class="'.$icon.'"></i></span>
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<strong>'.$icon_text.' -</strong> '.$text.' 
				</div>
			  </div>
			</div>
		';	
	}
	//////////////////// END NOTIFICATION CRUD ///////////////////////
	
	//////////////////// EMAIL CRUD ///////////////////////
	public function send_email($to, $from, $subject, $body_msg, $name, $subhead) {
		//clear initial email variables
		$this->email->clear(); 
		$email_status = '';
		
		$this->email->to($to);
		$this->email->from($from, $name);
		$this->email->subject($subject);
						
		$mail_data = array('message'=>$body_msg, 'subhead'=>$subhead);
		$this->email->set_mailtype("html"); //use HTML format
		$mail_design = $this->load->view('designs/email_template', $mail_data, TRUE);
				
		$this->email->message($mail_design);
		if(!$this->email->send()) {
			$email_status = FALSE;
		} else {
			$email_status = TRUE;
		}
		
		return $email_status;
	}
	//////////////////// END EMAIL CRUD ///////////////////////
	
	//////////////////// APP NOTIFY CRUD ///////////////////////
	public function notify($user_id, $user, $email, $phone, $item_id, $item, $title, $details, $type, $hash) {
		// register notification
		$not_data = array(
			'user_id' => $user_id,
			'nhash' => $hash,
			'item_id' => $item_id,
			'item' => $item,
			'new' => 1,
			'title' => $title,
			'details' => $details,
			'type' => $type,
			'reg_date' => date(fdate)
		);
		$not_ins = $this->create('ka_notify', $not_data);
		if($not_ins){
			// send email
			if($type == 'email'){
				$email_result = '';
				$from = app_email;
				$subject = $title;
				$name = app_name;
				$sub_head = $title.' Notification';
				
				$body = '
					<div class="mname">Hi '.ucwords($user).',</div><br />You have new '.$title.' notification,<br /><br />
					'.$details.'<br /><br />
					Warm Regards.
				';
				
				$email_result = $this->send_email($email, $from, $subject, $body, $name, $sub_head);
			} else {
				// send sms	
			}
		}
	}
	//////////////////// END APP NOTIFY CRUD ///////////////////////
	
	//////////////////// IMAGE CRUD ///////////////////////
	public function img_upload($log_id, $name, $path, $file='') {
		$img_id = 0;
		if(empty($name)) {$stamp = time().rand();} else {$stamp = $name;}
		$save_path = '';
		$msg = '';
		
		if (!is_dir($path))
			mkdir($path, 0755);

		$pathMain = './'.$path;
		if (!is_dir($pathMain))
			mkdir($pathMain, 0755);

		if($file == ''){$file = 'pics';}
		$result = $this->do_upload($file, $pathMain);

		if (!$result['status']){
			$msg = $this->msg('danger', 'Upload Failed');
		} else {
			$save_path = $path . '/' . $result['upload_data']['file_name'];
			
			//if size not up to 400px above
			if($result['image_width'] >= 400){
				if($result['image_width'] >= 400 || $result['image_height'] >= 400) {
					if($this->resize_image($pathMain . '/' . $result['upload_data']['file_name'], $stamp .'-303.gif','400','400', $result['image_width'], $result['image_height'])){
						$resize400 = $pathMain . '/' . $stamp.'-303.gif';
						$resize400_dest = $resize400;
						
						if($this->crop_image($resize400, $resize400_dest,'303','220')){
							$save_path303 = $path . '/' . $stamp .'-303.gif';
						}
					}
				}
					
				if($result['image_width'] >= 200 || $result['image_height'] >= 200){
					if($this->resize_image($pathMain . '/' . $result['upload_data']['file_name'], $stamp .'-150.gif','200','200', $result['image_width'], $result['image_height'])){
						$resize100 = $pathMain . '/' . $stamp.'-150.gif';
						$resize100_dest = $resize100;	
						
						if($this->crop_image($resize100, $resize100_dest,'150','150')){
							$save_path150 = $path . '/' . $stamp .'-150.gif';
						}
					}
				}
				
				//save picture in system
				$pics_data = array(
					'user_id' => $log_id,
					'pics' => $save_path,
					'pics_medium' => $save_path303,
					'pics_small' => $save_path150
				);
				$pics_ins = $this->create('img', $pics_data);
				// update in user table
				if($pics_ins) {
					$img_id = $pics_ins;
					$save_path = $save_path303;
				}
			} else {
				$msg = $this->msg('warning', 'Width Size: 400px');
			}
		}

		return (object)array('id'=>$img_id, 'msg'=>$msg, 'path'=>$save_path);
	}
	
	public function do_upload($htmlFieldName, $path, $name='', $ext='gif|jpg|jpeg|png|tif|bmp') {
		if($name == ''){$name = time();}
		$config['file_name'] = $name;
        $config['upload_path'] = $path;
        $config['allowed_types'] = $ext;
        $config['max_size'] = '10000';
        $config['max_width'] = '6000';
        $config['max_height'] = '6000';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        unset($config);
        
		if (!$this->upload->do_upload($htmlFieldName)){
            return array('error' => $this->upload->display_errors(), 'status' => 0);
        } else {
            $up_data = $this->upload->data();
			return array('status' => 1, 'upload_data' => $this->upload->data(), 'image_width' => $up_data['image_width'], 'image_height' => $up_data['image_height'], 'size' => $up_data['file_size'], 'ext' => $up_data['file_ext']);
        }
    }
	
	public function resize_image($sourcePath, $desPath, $width = '500', $height = '500', $real_width, $real_height) {
        $this->image_lib->clear();
		$config['image_library'] = 'gd2';
        $config['source_image'] = $sourcePath;
        $config['new_image'] = $desPath;
        $config['quality'] = '100%';
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['thumb_marker'] = '';
		$config['width'] = $width;
        $config['height'] = $height;
		
		$dim = (intval($real_width) / intval($real_height)) - ($config['width'] / $config['height']);
		$config['master_dim'] = ($dim > 0)? "height" : "width";
		
		$this->image_lib->initialize($config);
 
        if ($this->image_lib->resize())
            return true;
        return false;
    }
	
	public function crop_image($sourcePath, $desPath, $width = '320', $height = '320') {
        $this->image_lib->clear();
        $config['image_library'] = 'gd2';
        $config['source_image'] = $sourcePath;
        $config['new_image'] = $desPath;
        $config['quality'] = '100%';
        $config['maintain_ratio'] = FALSE;
        $config['width'] = $width;
        $config['height'] = $height;
		$config['x_axis'] = '20';
		$config['y_axis'] = '20';
        
		$this->image_lib->initialize($config);
 
        if ($this->image_lib->crop())
            return true;
        return false;
    }
	//////////////////// END IMAGE LIBRARY ///////////////////////
	
	//////////////////// REST API CRUD ///////////////////////
	public function rest_sandbox($link) {
		$api = base_url('api/v1/'.$link); // TEST
		//$api = 'https://api.sabre.com/'.$link; // LIVE
		return $api;
	}
	
	public function rest_token() {
		// create a new cURL resource
		$curl = curl_init();

		return '5E8EDD851D2FDFBD7415232C67367CC3';

		// parameters
		$api_link = $this->rest_sandbox('auth');
		$api_key = 't_Hjdflslsds';
		$api_secret = 't_HHgkskdshsdkm';
		$curl_data = array('apiKey'=>$api_key, 'apiSecret'=>$api_secret);
		$curl_data = json_encode($curl_data);
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';
		$chead[] = 'Content-Length: '.strlen($curl_data);

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_POST, 1);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return json_decode($result);
	}
	
	public function rest_create($endpoint, $token, $data, $file=false) {
		// create a new cURL resource
		$curl = curl_init();
		
		// post data
		$api_link = $this->rest_sandbox($endpoint);

		if($file == false){
			$curl_data = array('post_data' => $data);
			$curl_data = json_encode($curl_data);
		} else {
			$curl_data = $data; // allow file upload using CurlFile, passed into $data
		}
		
		$chead = array();
		$chead[] = 'Authorization: '.$token;
		if($file == false){
			$chead[] = 'Content-Type: application/json';
		}

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}

	public function rest_file_create($endpoint, $token, $data) {
		// create a new cURL resource
		$curl = curl_init();
		
		// post data
		$api_link = $this->rest_sandbox($endpoint);
		
		$chead = array();
		$chead[] = 'Authorization: '.$token;
		//$chead[] = 'Content-Type: application/json';

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	
	public function rest_read($endpoint, $token, $limit='', $offset='', $user_id='', $user_type='', $category='', $search='', $sort='') {
		// create a new cURL resource
		$curl = curl_init();
		
		// prepare for filter
		$filter = '';
		if($limit || $offset) {
			$filter = '?limit='.$limit.'&offset='.$offset;
		} 
		if($user_id) {
			if($limit || $offset) {
				$filter .= '&user_id='.$user_id.'&user_type='.$user_type.'&category='.$category.'&search='.$search.'&sort='.$sort;
			} else {
				$filter = '?user_id='.$user_id.'&user_type='.$user_type.'&category='.$category.'&search='.$search.'&sort='.$sort;
			}
		}

		// parameters
		$api_link = $this->rest_sandbox($endpoint.$filter);
		
		// if data post
		if(!empty($data)){$curl_data = json_encode($data);} else {$curl_data = '';}
		
		$chead = array();
		$chead[] = 'Authorization: '.$token;
		$chead[] = 'Content-Type: application/json';

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	
	public function rest_update($endpoint, $token, $data) {
		// create a new cURL resource
		$curl = curl_init();
		
		// post data
		$api_link = $this->rest_sandbox($endpoint);
		$curl_data = array('post_data' => $data);
		$curl_data = json_encode($curl_data);
		
		$chead = array();
		$chead[] = 'Authorization: '.$token;
		$chead[] = 'Content-Type: application/json';

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	
	public function rest_delete($endpoint, $token) {
		// create a new cURL resource
		$curl = curl_init();
		
		// post data
		$api_link = $this->rest_sandbox($endpoint);
		
		$chead = array();
		$chead[] = 'Authorization: '.$token;
		$chead[] = 'Content-Type: application/json';

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	}
	//////////////////// END REST API CRUD //////////////////////

	//////////////////// RavePAY CRUD ///////////////////////
	public function rave_key($server, $type) {
		$key = '';
		if($server == 'test') {
			if($type == 'public') {
				$key = 'FLWPUBK-dec2f656b3c2d47c84298707c7350f1c-X';
			} else if($type == 'secret') {
				$key = 'FLWSECK-82137be3ef8863dac5a8043b6ae78843-X';
			}
		} else if($server == 'live') {
			if($type == 'public') {
				$key = 'FLWPUBK-e0a0d9e617caaa7b4242765fde2dc108-X';
			} else if($type == 'secret') {
				$key = 'FLWSECK-278a17f95e3bc8246c53ae1c076a9e93-X';
			}
		}
		
		return $key;
	}
	
	public function rave_verify($data, $server='') {
		// create a new cURL resource
		$curl = curl_init();

		// parameters
		if($server == 'live') {
			$api_link = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/verify';
		} else {
			$api_link = 'http://flw-pms-dev.eu-west-1.elasticbeanstalk.com/flwv3-pug/getpaidx/api/verify';
		}
		$curl_data = json_encode($data);
		
		$chead = array();
		$chead[] = 'Content-Type: application/json';

		// set URL and other appropriate options
		curl_setopt($curl, CURLOPT_URL, $api_link);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $chead);
		curl_setopt($curl, CURLOPT_POST, 1);
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// grab URL and pass it to the browser
		$result = curl_exec($curl);

		// close cURL resource, and free up system resources
		curl_close($curl);

		return $result;
	
	}
	//////////////////// END RavePAY API CRUD ///////////////////////

	//////////////////// Google Distance Matrix ///////////////////////
	function google_coordinate($address) {
		$address = urlencode($address);
		$link = "http://maps.google.com/maps/api/geocode/json?address=".$address;

		$response = @file_get_contents($link);
		$resp = json_decode($response, true);

		$lat = ''; $lng = '';
		if($resp['status'] == 'OK') {
			$lat = $resp['results'][0]['geometry']['location']['lat'];
			$lng = $resp['results'][0]['geometry']['location']['lng'];
		}
		
		// return distance
		if($lat && $lng) {
			return (object)array('lat'=>$lat, 'lng'=>$lng);
		}
	}
	
	function google_distance($source, $destination, $type='distance') {
		// address or cordinate (e.g. 6.6194,3.5105)
		// address or cordinate (e.g. 6.5962,3.3918)
		$source = urlencode($source);
		$destination = urlencode($destination);
		$link = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=".$source."&destinations=".$destination."&mode=driving&sensor=false";

		$response = @file_get_contents($link);
		$resp = json_decode($response, true);

		$distance = ''; $duration = '';
		if($resp['status'] == 'OK') {
			if($resp['rows'][0]['elements'][0]['status'] == 'OK') {
				$distance = $resp['rows'][0]['elements'][0]['distance']['text'];
				$duration = $resp['rows'][0]['elements'][0]['duration']['text'];
			}
		}
		
		// return distance
		if($distance && $type == 'distance') {
			return $distance;
		}

		// return duration
		if($duration && $type == 'duration') {
			return $duration;
		}
	}
	//////////////////// End Google Distance Matrix ///////////////////////
	
	//////////////////// MUNITES TO HOURS ///////////////////////
	public function to_hours($time, $format = '%02d:%02d') {
		if ($time < 1) {
			return;
		}
		$hours = floor(($time) / 60);
		$minutes = ($time % 60);
		return sprintf($format, $hours, $minutes);
	}
	//////////////////// END MUNITES TO HOURS ///////////////////////
	
	//////////////////// DATETIME ///////////////////////
	public function get_date($date, $format='') {
		if($format == 'date') {
			$newdate = date('D, M d', strtotime($date));
		} else if($format == 'time') {
			$newdate = date('h:i A', strtotime($date));
		} else {
			$newdate = date('D, M d, Y h:i A', strtotime($date));
		}
		return $newdate;
	}
	
	public function date_diff($now, $end, $type) {
		$now = new DateTime($now);
		$end = new DateTime($end);
		$date_left = $end->getTimestamp() - $now->getTimestamp();
		
		if($type == 'seconds') {
			if($date_left <= 0){$date_left = 0;}
		} else if($type == 'minutes') {
			$date_left = $date_left / 60;
			if($date_left <= 0){$date_left = 0;}
		} else if($type == 'hours') {
			$date_left = $date_left / (60*60);
			if($date_left <= 0){$date_left = 0;}
		} else if($type == 'days') {
			$date_left = $date_left / (60*60*24);
			if($date_left <= 0){$date_left = 0;}
		} else {
			$date_left = $date_left / (60*60*24*365);
			if($date_left <= 0){$date_left = 0;}
		}	
		
		return $date_left;
	}
	//////////////////// END DATETIME ///////////////////////

	//////////////////// CURRENCY ///////////////////////
	public function currency($company_id, $value) {
		$country_id = $this->read_field('id', $company_id, 'company', 'country_id');
		$country = $this->read_field('id', $country_id, 'country', 'name');
		$get_currency = $this->read_field2('company_id', $company_id, 'type', 'currency', 'setting', 'value');
		$get_currency = json_decode($get_currency);
		$position = $get_currency->position;
		$decimal = $get_currency->decimal;
		$symbol = $get_currency->symbol;

		$symbol = explode('|', $symbol);
		$symbol = $symbol[0];

		// get currency by country name
		$pull_currency = _countryDb($country, 'currency');
		if($symbol == 'code') {
			$symbol = $pull_currency['code'];
		} else {
			$symbol = $pull_currency['symbol'];
		}
		
		$value = number_format((float)$value, (int)$decimal);
		if($position == 'prefix') {
			$value = $symbol.' '.$value;
		} else {
			$value = $value.' '.$symbol;
		}

		return $value;
	}
	
	public function user_types($roles) {
		$user_type = '';

		$role_array = json_decode($roles);
		if(!empty($role_array)) {
			foreach(json_decode($roles) as $key=>$value) {
				$type = $this->read_field('id', $value, 'access_role', 'name');
				$user_type .= $type.', ';
			}
			if($user_type != '') {$user_type = rtrim($user_type, ', ');}
		}

		return $user_type;
	}

	public function filter_timeline($limit='', $offset='', $country='', $user_type='', $category='', $search='', $sort='') {
		// build query
		if(strtolower($sort) == 'trending') {
			$query = $this->db->order_by('winks', 'DESC');
		} else {
			$query = $this->db->order_by('id', 'DESC');
		}

		if(!empty($country)) {$query = $this->db->where('country_id', $country);}
		
		if(!empty($user_type)) {
			$ut = explode('-', $user_type);
			for($i=0; $i<count($ut); $i++) {
				$query = $this->db->or_like('role_id', $ut[$i]);
			}
		}

		if(!empty($category)) {
			$cat = explode('-', $category);
			for($i=0; $i<count($cat); $i++) {
				$query = $this->db->or_like('category', $cat[$i]);
			}
		}

		if(!empty($search)) {$query = $this->db->like('title', $search); $query = $this->db->or_like('details', $search);}
		
		if(!empty($limit) && !empty($offset)) {
			$query = $this->db->limit($limit, $offset);
		} else if(!empty($limit)) {
			$query = $this->db->limit($limit);
		}

		$query = $this->db->get('post');
		if($query->num_rows() > 0) {
			return $query->result();
		}
	}
	//////////////////// END ///////////////////////

	//////////////////// SUBSCRIPTION ///////////////////////
	public function subs($user_id, $type) {
		$resp['status'] = 'true';
		$resp['msg'] = '';
		
		// Starter
		$starter_outlet = 1; $starter_contact = 1; $starter_service = 15; $starter_product = 15;

		// Business
		$business_outlet = 3; $business_contact = 75; $business_service = 50; $business_product = 50;

		// check if expired
		if($this->read_field('user_id', $user_id, 'premium', 'status') == 0) {
			$resp['status'] = 'false';
			$resp['msg'] = 'Subscription Expired';
			return (object)$resp;
		}

		$outlet_counts = count($this->read_single('user_id', $user_id, 'store'));
	
		$contact_counts = 0; $product_counts = 0; $service_counts = 0;
		$allpdtstore = $this->read_single('user_id', $user_id, 'store');
		if(!empty($allpdtstore)) {
			foreach($allpdtstore as $aps) {
				$contact_counts += count($this->read_single('store_id', $aps->id, 'customer'));

				$allpdtcat = $this->read_single('store_id', $aps->id, 'product_cat');
				if(!empty($allpdtcat)) {
					foreach($allpdtcat as $apc) {
						$product_counts += count($this->read_single('cat_id', $apc->id, 'product'));
					}
				}
				$allservcat = $this->read_single('store_id', $aps->id, 'service_cat');
				if(!empty($allservcat)) {
					foreach($allservcat as $asc) {
						$service_counts += count($this->read_single('cat_id', $asc->id, 'service'));
					}
				}
			}
		}

		$sub_type = $this->read_field('user_id', $user_id, 'premium', 'type');

		// check starter or free
		if($sub_type == 'Starter' || $sub_type == 'Free') {
			// check outlet
			if($type == 'outlet') {
				if($outlet_counts >= $starter_outlet) {
					$resp['status'] = 'false';
					$resp['msg'] = 'Outlet Quota Exceeded';
					return (object)$resp;
				}
			}
			// check contact
			if($type == 'contact') {
				if($contact_counts >= $starter_contact) {
					$resp['status'] = 'false';
					$resp['msg'] = 'Contact Quota Exceeded';
					return (object)$resp;
				}
			}
			// check contact
			if($type == 'product') {
				if($product_counts >= $starter_product) {
					$resp['status'] = 'false';
					$resp['msg'] = 'Product Quota Exceeded';
					return (object)$resp;
				}
			}
			// check contact
			if($type == 'service') {
				if($service_counts >= $starter_service) {
					$resp['status'] = 'false';
					$resp['msg'] = 'Service Quota Exceeded';
					return (object)$resp;
				}
			}
		}

		// check business
		if($sub_type == 'Business') {
			// check outlet
			if($type == 'outlet') {
				if($outlet_counts >= $business_outlet) {
					$resp['status'] = 'false';
					$resp['msg'] = 'Outlet Quota Exceeded';
					return (object)$resp;
				}
			}
			// check contact
			if($type == 'contact') {
				if($contact_counts >= $business_contact) {
					$resp['status'] = 'false';
					$resp['msg'] = 'Contact Quota Exceeded';
					return (object)$resp;
				}
			}
			// check contact
			if($type == 'product') {
				if($product_counts >= $business_product) {
					$resp['status'] = 'false';
					$resp['msg'] = 'Product Quota Exceeded';
					return (object)$resp;
				}
			}
			// check contact
			if($type == 'service') {
				if($service_counts >= $business_service) {
					$resp['status'] = 'false';
					$resp['msg'] = 'Service Quota Exceeded';
					return (object)$resp;
				}
			}
		}

		return (object)$resp;
	}
	//////////////////// END  ///////////////////////

	//////////////////// MODULE ///////////////////////
	public function module($role, $module, $type) {
		$result = 0;
		
		$mod_id = $this->read_field('link', $module, 'access_module', 'id');

		// iterate roles
		$role_array = json_decode($role);
		if(!empty($role_array)) {
			foreach(json_decode($role) as $key=>$value) {
				if($result == 1){break;}
				$crud = $this->read_field('role_id', $value, 'access', 'crud');
				if($mod_id) {
					if(!empty($crud)) {
						$crud = json_decode($crud);
						foreach($crud as $cr) {
							$cr = explode('.', $cr);
							if($mod_id == $cr[0]) {
								if($type == 'create'){$result = $cr[1];}
								if($type == 'read'){$result = $cr[2];}
								if($type == 'update'){$result = $cr[3];}
								if($type == 'delete'){$result = $cr[4];}
								break;
							}
						}
					}
				}
			}
		}
		
		return $result;
	}
	public function mod_read($role, $module) {
		$rs = $this->module($role, $module, 'read');
		return $rs;
	}
	public function roles($role) {
		// iterate roles
		foreach(json_decode($role) as $key=>$value) {
			$all_role[] = $this->read_field('id', $value, 'access_role', 'name');
		}
		
		if(!empty($all_role)) {
			return $all_role;
		} else {
			return;
		}
		
	}
	//////////////////// END MODULE ///////////////////////
	
}
