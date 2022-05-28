<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Users extends CI_Model {
	 
		public function reg_insert($data) {
			$this->db->insert('org_users', $data);
			return $this->db->insert_id();
		}
		
		public function reg_activity($data) {
			$this->db->insert('org_activity', $data);
			return $this->db->insert_id();
		}
		
		public function reg_notification($data) {
			$this->db->insert('org_notification', $data);
			return $this->db->insert_id();
		}
		
		public function reg_img($data) {
			$this->db->insert('org_img', $data);
			return $this->db->insert_id();
		}
		
		public function reg_industry($data) {
			$this->db->insert('org_industry', $data);
			return $this->db->insert_id();
		}
		
		public function query_user($email, $pass) {
			$query = $this->db->get_where('org_users', array('email' => $email, 'password' => $pass));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_user_by_email($email) {
			$query = $this->db->get_where('org_users', array('email' => $email));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_single_user_id($data) {
			$query = $this->db->get_where('org_users', array('id' => $data));
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_all_user() {
			$query = $this->db->order_by('firstname', 'asc');
			$query = $this->db->get('org_users');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_industry() {
			$query = $this->db->order_by('industry', 'asc');
			$query = $this->db->get('org_industry');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_user_industry($data) {
			$query = $this->db->where('id', $data);
			$query = $this->db->get('org_industry');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_activity() {
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->get('org_activity');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_store_activity($data) {
			$query = $this->db->where('store_id', $data);
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->get('org_activity');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_notify_user($id) {
			$query = $this->db->where('receive_id', $id);
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->get('org_notification');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_notify_user_unread($id) {
			$query = $this->db->where('receive_id', $id);
			$query = $this->db->where('status', 0);
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->get('org_notification');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_notify_by_date($id, $date) {
			$query = $this->db->where('receive_id', $id);
			$query = $this->db->where('date_group', $date);
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->get('org_notification');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_notify_group_date($id) {
			$query = $this->db->where('receive_id', $id);
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->group_by('date_group');
			$query = $this->db->get('org_notification');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_all_theme() {
			$query = $this->db->order_by('name', 'asc');
			$query = $this->db->get('org_theme');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_user_img_id($id, $user_id) {
			$query = $this->db->where('id', $id);
			$query = $this->db->where('user_id', $user_id);
			$query = $this->db->get('org_img');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_img_id($id) {
			$query = $this->db->where('id', $id);
			$query = $this->db->get('org_img');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function check_by_email($email) {
			$query = $this->db->get_where('org_users', array('email' => $email));
			return $query->num_rows();
		}
		
		public function check_user($email, $pass) {
			$query = $this->db->get_where('org_users', array('email' => $email, 'password' => $pass));
			return $query->num_rows();
		}
		
		public function check_activation($stamp, $email) {
			$query = $this->db->get_where('org_users', array('regstamp' => $stamp, 'email' => $email, 'activate' => 0));
			return $query->num_rows();	
		}
		
		public function check_reset($stamp, $email) {
			$query = $this->db->get_where('org_users', array('reset_stamp' => $stamp, 'email' => $email, 'reset' => 1));
			return $query->num_rows();	
		}
		
		public function check_industry_name($data) {
			$query = $this->db->get_where('org_industry', array('industry' => $data));
			return $query->num_rows();
		}
		
		public function activate($email, $data) {
			$this->db->where('email', $email);
			$this->db->update('org_users', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_user($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_users', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_industry($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_industry', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_notification($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_notification', $data);
			return $this->db->affected_rows();	
		}
		
		public function delete_user($id) {
			$this->db->where('id', $id);
			$this->db->delete('org_users');
			return $this->db->affected_rows();
		}
		
		public function delete_industry($id) {
			$this->db->where('id', $id);
			$this->db->delete('org_industry');
			return $this->db->affected_rows();
		}
		
		public function delete_notification($id, $user) {
			$this->db->where('id', $id);
			$this->db->where('receive_id', $user);
			$this->db->delete('org_notification');
			return $this->db->affected_rows();
		}
		
		/////////// PREMIUM ////////////////
		public function reg_premium($data) {
			$this->db->insert('org_premium', $data);
			return $this->db->insert_id();
		}
		
		public function check_premium($id) {
			$query = $this->db->get_where('org_premium', array('user_id' => $id));
			return $query->num_rows();
		}
		
		public function query_premium($data) {
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->get('org_premium');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_premium_user_group() {
			$query = $this->db->order_by('id', 'desc');
			$query = $this->db->group_by('user_id');
			$query = $this->db->get('org_premium');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_premium_id($data) {
			$this->db->where('id', $data);
			$query = $this->db->get('org_premium');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_premium_user($data) {
			$this->db->where('user_id', $data);
			$query = $this->db->get('org_premium');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function query_premium_user_id($id, $user) {
			$this->db->where('id', $id);
			$this->db->where('user_id', $user);
			$query = $this->db->get('org_premium');
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		
		public function update_premium($id, $data) {
			$this->db->where('id', $id);
			$this->db->update('org_premium', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_premium_user($id, $user_id, $data) {
			$this->db->where('id', $id);
			$this->db->where('user_id', $user_id);
			$this->db->update('org_premium', $data);
			return $this->db->affected_rows();	
		}
		
		public function update_premium_user_id($id, $data) {
			$this->db->where('user_id', $id);
			$this->db->update('org_premium', $data);
			return $this->db->affected_rows();	
		}
	}