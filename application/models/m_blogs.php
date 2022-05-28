<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class M_blogs extends CI_Model {
	 
		public function reg_insert($data) {
			$this->db->insert('org_blog', $data);
			return $this->db->insert_id();
		}
		
		public function reg_insert_comment($data) {
			$this->db->insert('org_blog_comment', $data);
			return $this->db->insert_id();
		}
		
		public function query_blog_cat($data) {
			$query = $this->db->get_where('org_industry', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_blog_id($data) {
			$query = $this->db->get_where('org_blog', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_blog_comment($data) {
			$query = $this->db->get_where('org_blog_comment', array('blog_id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_blog_comment_id($data) {
			$query = $this->db->get_where('org_blog_comment', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_blog_slug($data) {
			$query = $this->db->get_where('org_blog', array('slug' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_blog_cat_id($data) {
			$query = $this->db->where('cat_id', $data);
			$query = $this->db->or_where('cat_id', 0);
			$query = $this->db->get('org_blog');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_all_blog() {
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->get('org_blog');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_blog_industry($data) {
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->where('cat_id', $data);
			$query = $this->db->or_where('cat_id', 0);
			$query = $this->db->get('org_blog');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function check_blog($cat_id, $slug) {
			$query = $this->db->get_where('org_blog', array('cat_id' => $cat_id, 'slug' => $slug));
			return $query->num_rows();
		}
		
		public function update_blog($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_blog', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_blog_comment($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_blog_comment', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_blog($id) {
			$this->db->where('id', $id);
			$this->db->delete('org_blog');
			return $this->db->affected_rows();
		}
		
		public function delete_blog_comment($id) {
			$this->db->where('id', $id);
			$this->db->delete('org_blog_comment');
			return $this->db->affected_rows();
		}
	}