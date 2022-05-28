<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_invoices extends CI_Model {
	 
		public function reg_insert($data) {
			$this->db->insert('org_invoice', $data);
			return $this->db->insert_id();
		}
		
		public function reg_insert_item($data) {
			$this->db->insert('org_invoice_item', $data);
			return $this->db->insert_id();
		}
		
		public function check_invoice_item($data) {
			$query = $this->db->get_where('org_invoice_item', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_invoice_id($data, $store_id) {
			$query = $this->db->get_where('org_invoice', array('id' => $data, 'store_id' => $store_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_invoice_item_id($data) {
			$query = $this->db->get_where('org_invoice_item', array('invoice_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_invoice_client_id($data, $client_id) {
			$query = $this->db->get_where('org_invoice', array('id' => $data, 'client_id' => $client_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_invoice_client($data) {
			$query = $this->db->get_where('org_invoice', array('client_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_user_invoice($data) {
			$query = $this->db->get_where('org_invoice', array('store_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}

		public function query_user_invoice_by_year($store) {
			$query = $this->db->where('store_id', $store);
			$query = $this->db->like('pay_date', date('Y'));
			$query = $this->db->get('org_invoice');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_user_invoice_by_dates($store, $start, $end) {
			$query = $this->db->where('store_id', $store);
			$query = $this->db->where('pay_date >=', date('Y-m-d', strtotime($start)));
			$query = $this->db->where('pay_date <=', date('Y-m-d', strtotime($end)));
			$query = $this->db->get('org_invoice');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_all_invoice() {
			$query = $this->db->order_by('invoice', 'asc');
			$query = $this->db->get('org_invoice');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function update_invoice($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_invoice', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_invoice_item($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_invoice_item', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_invoice($id, $store_id) {
			$this->db->where('id', $id);
			$this->db->where('store_id', $store_id);
			$this->db->delete('org_invoice');
			return $this->db->affected_rows();
		}
	}