<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_stores extends CI_Model {
	 
		public function reg_insert($data) {
			$this->db->insert('org_store', $data);
			return $this->db->insert_id();
		}
		
		public function reg_store_staff($data) {
			$this->db->insert('org_store_staff', $data);
			return $this->db->insert_id();
		}
		
		public function query_store_id($data) {
			$query = $this->db->get_where('org_store', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_store_id($data, $user_id) {
			$query = $this->db->get_where('org_store', array('id' => $data, 'user_id' => $user_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_store_staff_id($data, $store_id, $user_id) {
			$query = $this->db->get_where('org_store_staff', array('id' => $data, 'store_id' => $store_id, 'user_id' => $user_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_user_store($data) {
			$query = $this->db->get_where('org_store', array('user_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_user_stores($data) {
			$query = $this->db->get_where('org_store_staff', array('user_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_user_stores_id($data) {
			$query = $this->db->get_where('org_store', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_owner_store($data) {
			$query = $this->db->get_where('org_store_staff', array('owner_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_all_store() {
			$query = $this->db->order_by('store', 'asc');
			$query = $this->db->get('org_store');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_store_staff($store_id) {
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->where('store_id', $store_id);
			$query = $this->db->get('org_store_staff');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function check_by_name($data) {
			$query = $this->db->get_where('org_store', array('name' => $data));
			return $query->num_rows();
		}
		
		public function update_store($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_store', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_store_staff($id, $user_id, $data) {
			$this->db->where('id', $id);
			$this->db->where('user_id', $user_id);
			$this->db->update('org_store_staff', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_store($id, $user_id) {
			$this->db->where('id', $id);
			$this->db->where('user_id', $user_id);
			$this->db->delete('org_store');
			return $this->db->affected_rows();
		}
		
		public function delete_store_staff($id, $owner_id, $store_id) {
			$this->db->where('id', $id);
			$this->db->where('owner_id', $owner_id);
			$this->db->where('store_id', $store_id);
			$this->db->delete('org_store_staff');
			return $this->db->affected_rows();
		}
	}