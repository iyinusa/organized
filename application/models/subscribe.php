<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Subscribe extends CI_Model {
	 
		public function reg_insert($data) {
			$this->db->insert('org_subscribe', $data);
			return $this->db->insert_id();
		}
		
		public function check_subscriber($email) {
			$query = $this->db->get_where('org_subscribe', array('email' => $email));
			return $query->num_rows();
		}
		
		public function all_subscriber() {
			$query = $this->db->get('org_subscribe');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
	}