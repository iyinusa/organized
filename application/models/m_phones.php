<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_phones extends CI_Model {
	 
		public function reg_phone($data) {
			$this->db->insert('org_phone', $data);
			return $this->db->insert_id();
		}
		
		public function reg_phone_cat($data) {
			$this->db->insert('org_phone_cat', $data);
			return $this->db->insert_id();
		}
		
		public function query_single_phone($data, $cat_id) {
			$query = $this->db->get_where('org_phone', array('id' => $data, 'cat_id' => $cat_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_phone_cat($data, $store_id) {
			$query = $this->db->get_where('org_phone_cat', array('id' => $data, 'store_id' => $store_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_phone_id($data) {
			$query = $this->db->get_where('org_phone', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_phone($data) {
			$query = $this->db->get_where('org_phone', array('cat_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_phone_cat($data) {
			$query = $this->db->get_where('org_phone_cat', array('store_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_phone_cat_id($data) {
			$query = $this->db->get_where('org_phone_cat', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function check_phone_cust($data) {
			$query = $this->db->get_where('org_phone', array('cust_id' => $data));
			return $query->num_rows();
		}
		
		public function update_phone($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_phone', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_phone_cat($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_phone_cat', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_phone($id, $cat_id) {
			$this->db->where('id', $id);
			$this->db->where('cat_id', $cat_id);
			$this->db->delete('org_phone');
			return $this->db->affected_rows();
		}
		
		public function delete_phone_cat($id, $store_id) {
			$this->db->where('id', $id);
			$this->db->where('store_id', $store_id);
			$this->db->delete('org_phone_cat');
			return $this->db->affected_rows();
		}
	}