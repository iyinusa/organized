<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_expenses extends CI_Model {
	 
		public function reg_expense($data) {
			$this->db->insert('org_expense', $data);
			return $this->db->insert_id();
		}
		
		public function reg_expense_cat($data) {
			$this->db->insert('org_expense_cat', $data);
			return $this->db->insert_id();
		}
		
		public function query_single_expense($data, $cat_id) {
			$query = $this->db->get_where('org_expense', array('id' => $data, 'cat_id' => $cat_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_expense_cat($data, $store_id) {
			$query = $this->db->get_where('org_expense_cat', array('id' => $data, 'store_id' => $store_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_expense_id($data) {
			$query = $this->db->get_where('org_expense', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_expense($data) {
			$query = $this->db->get_where('org_expense', array('cat_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}

		public function query_expense_by_year($cat) {
			$query = $this->db->where('cat_id', $cat);
			$query = $this->db->like('exp_date', date('Y'));
			$query = $this->db->get('org_expense');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_expense_dates($cat, $start, $end) {
			$query = $this->db->where('cat_id', $cat);
			$query = $this->db->where('exp_date >=', $start);
			$query = $this->db->where('exp_date <=', $end);
			$query = $this->db->get('org_expense');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_expense_cat($data) {
			$query = $this->db->get_where('org_expense_cat', array('store_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_expense_cat_id($data) {
			$query = $this->db->get_where('org_expense_cat', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function update_expense($id, $cat_id, $data) {
			$this->db->where('id', $id);
			$this->db->where('cat_id', $cat_id);
			$this->db->update('org_expense', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_expense_cat($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_expense_cat', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_expense($id, $cat_id) {
			$this->db->where('id', $id);
			$this->db->where('cat_id', $cat_id);
			$this->db->delete('org_expense');
			return $this->db->affected_rows();
		}
		
		public function delete_expense_cat($id, $store_id) {
			$this->db->where('id', $id);
			$this->db->where('store_id', $store_id);
			$this->db->delete('org_expense_cat');
			return $this->db->affected_rows();
		}
	}