<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_services extends CI_Model {
	 
		public function reg_service($data) {
			$this->db->insert('org_service', $data);
			return $this->db->insert_id();
		}
		
		public function reg_service_cat($data) {
			$this->db->insert('org_service_cat', $data);
			return $this->db->insert_id();
		}
		
		public function query_single_service($data, $cat_id) {
			$query = $this->db->get_where('org_service', array('id' => $data, 'cat_id' => $cat_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_service_cat($data, $store_id) {
			$query = $this->db->get_where('org_service_cat', array('id' => $data, 'store_id' => $store_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_service_id($data) {
			$query = $this->db->get_where('org_service', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_service($data) {
			$query = $this->db->get_where('org_service', array('cat_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_service_cat($data) {
			$query = $this->db->get_where('org_service_cat', array('store_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_service_cat_id($data) {
			$query = $this->db->get_where('org_service_cat', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function update_service($id, $cat_id, $data) {
			$this->db->where('id', $id);
			$this->db->where('cat_id', $cat_id);
			$this->db->update('org_service', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_service_cat($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_service_cat', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_service($id, $cat_id) {
			$this->db->where('id', $id);
			$this->db->where('cat_id', $cat_id);
			$this->db->delete('org_service');
			return $this->db->affected_rows();
		}
		
		public function delete_service_cat($id, $store_id) {
			$this->db->where('id', $id);
			$this->db->where('store_id', $store_id);
			$this->db->delete('org_service_cat');
			return $this->db->affected_rows();
		}
	}