<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_products extends CI_Model {
	 
		public function reg_product($data) {
			$this->db->insert('org_product', $data);
			return $this->db->insert_id();
		}
		
		public function reg_product_cat($data) {
			$this->db->insert('org_product_cat', $data);
			return $this->db->insert_id();
		}
		
		public function reg_product_stock($data) {
			$this->db->insert('org_stock', $data);
			return $this->db->insert_id();
		}
		
		public function query_single_product($data, $cat_id) {
			$query = $this->db->get_where('org_product', array('id' => $data, 'cat_id' => $cat_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_product_cat_id($data) {
			$query = $this->db->get_where('org_product_cat', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_product_cat($data, $store_id) {
			$query = $this->db->get_where('org_product_cat', array('id' => $data, 'store_id' => $store_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_product($data) {
			$query = $this->db->get_where('org_product', array('cat_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_stock($data) {
			$query = $this->db->get_where('org_stock', array('store_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_product_qty($data, $pdt_id) {
			$query = $this->db->get_where('org_stock', array('store_id' => $data, 'pdt_id' => $pdt_id));
			$total = 0;
			if($query->num_rows() > 0) {
				$product = $query->result();
				foreach($product as $pdt){
					if($pdt->deduct == 0){$total += $pdt->qty;} else {$total -= $pdt->qty;}
				}
				return $total;
			}
		}
		
		public function query_product_id($data) {
			$query = $this->db->get_where('org_product', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_product_cat($data) {
			$query = $this->db->get_where('org_product_cat', array('store_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_product_stock_id($data, $store_id) {
			$query = $this->db->get_where('org_stock', array('pdt_id' => $data, 'store_id' => $store_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_product_stock($data, $store_id) {
			$query = $this->db->get_where('org_stock', array('id' => $data, 'store_id' => $store_id));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function update_product($id, $cat_id, $data) {
			$this->db->where('id', $id);
			$this->db->where('cat_id', $cat_id);
			$this->db->update('org_product', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_product_cat($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_product_cat', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_product_stock($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_stock', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_product($id, $cat_id) {
			$this->db->where('id', $id);
			$this->db->where('cat_id', $cat_id);
			$this->db->delete('org_product');
			return $this->db->affected_rows();
		}
		
		public function delete_product_cat($id, $store_id) {
			$this->db->where('id', $id);
			$this->db->where('store_id', $store_id);
			$this->db->delete('org_product_cat');
			return $this->db->affected_rows();
		}
		
		public function delete_product_stock($id, $store_id) {
			$this->db->where('id', $id);
			$this->db->where('store_id', $store_id);
			$this->db->delete('org_stock');
			return $this->db->affected_rows();
		}
	}