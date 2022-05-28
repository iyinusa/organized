<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_customers extends CI_Model {
	 
		public function reg_store_customer($data) {
			$this->db->insert('org_store_customer', $data);
			return $this->db->insert_id();
		}
		
		public function reg_customer($data) {
			$this->db->insert('org_customer', $data);
			return $this->db->insert_id();
		}
		
		public function query_single_customer_id($data, $store_id, $user_id) {
			$query = $this->db->get_where('org_store_customer', array('id' => $data, 'store_id' => $store_id, 'user_id' => $user_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_store_customer($store_id, $user_id) {
			$query = $this->db->get_where('org_store_customer', array('store_id' => $store_id, 'user_id' => $user_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_customer_group() {
			$query = $this->db->get('org_customer_group');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_customer_id($data, $store_id, $user_id) {
			$query = $this->db->get_where('org_customer', array('id' => $data, 'store_id' => $store_id, 'user_id' => $user_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_owner_customer($data) {
			$query = $this->db->get_where('org_store_customer', array('store_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_group_id($data) {
			$query = $this->db->get_where('org_customer_group', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_customer($data) {
			$query = $this->db->get_where('org_customer', array('store_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_customer_alpha($store_id, $data) {
			$query = $this->db->where('store_id', $store_id);
			$query = $this->db->like('firstname', $data, 'after');
			$query = $this->db->get('org_customer');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_customer_store_user($data, $user_id) {
			$query = $this->db->get_where('org_customer', array('store_id' => $data, 'user_id' => $user_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_store_customer($store_id) {
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->where('store_id', $store_id);
			$query = $this->db->get('org_store_customer');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function update_store_customer($id, $user_id, $data) {
			$this->db->where('id', $id);
			$this->db->where('user_id', $user_id);
			$this->db->update('org_store_customer', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_customer($id, $user_id, $data) {
			$this->db->where('id', $id);
			$this->db->where('user_id', $user_id);
			$this->db->update('org_customer', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_store_customer($id, $store_id, $user_id) {
			$this->db->where('id', $id);
			$this->db->where('store_id', $store_id);
			$this->db->where('user_id', $user_id);
			$this->db->delete('org_store_customer');
			return $this->db->affected_rows();
		}
		
		public function delete_customer($id, $store_id, $user_id) {
			$this->db->where('id', $id);
			$this->db->where('store_id', $store_id);
			$this->db->where('user_id', $user_id);
			$this->db->delete('org_customer');
			return $this->db->affected_rows();
		}
	}