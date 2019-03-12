<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User Management Model
 * @author manaknight
 *
 */
class Users extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * create_user function.
	 *
	 * @access public
	 * @param mixed $email
	 * @param mixed $password
	 * @param mixed $plan
	 * @return bool true on success, false on failure
	 */
	public function create_user($email, $password)
	{
		$data = [
			'email'      => $email,
			'password'   => $this->hash_password($password),
			'description' => ''
		];

		if ($this->db->insert('users', $data, TRUE))
        {
            return $this->db->insert_id();
        }
        else
        {
            return FALSE;
        }

	}

	/**
	 * resolve_user_login function.
	 *
	 * @access public
	 * @param mixed $email
	 * @param mixed $password
	 * @param bool $isAdmin false
	 * @return bool true on success, false on failure
	 */
	public function authenticate($email, $password)
	{

		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('email', $email, TRUE);

		$hash = $this->db->get()->row('password');
        error_log($hash);
		return $this->verify_password_hash($password, $hash);
	}

	/**
	 * Get Profile
	 *
	 * @access public
	 * @param mixed $id
	 * @return mixed $users
	 */
	public function get_profile($id)
	{
		$this->db->from('users');
		$this->db->where('id', $id, TRUE);
        $result =  $this->db->get()->row();
        unset($result->password);
		return $result;
	}

	/**
	 * get_user_id_from_username function.
	 *
	 * @access public
	 * @param mixed $email
	 * @return int the user id
	 */
	public function get_user_id_from_email($email)
	{
		$this->db->from('users');
		$this->db->where('email', $email, TRUE);
		return $this->db->get()->row('id');
	}

	/**
	 * get_user_from_email function.
	 *
	 * @access public
	 * @param mixed $email
	 * @return object user
	 */
	public function get_user_from_email($email)
	{
		$this->db->from('users');
		$this->db->where('email', $email, TRUE);
		return $this->db->get()->row();
	}

	/**
	 * get_user function
	 *
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function get_user($user_id)
	{

		$this->db->from('users');
		$this->db->where('id', $user_id, TRUE);
		return $this->db->get()->row();

	}

	/**
	 * hash_password function.
	 *
	 * @access private
	 * @param mixed $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password)
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

	/**
	 * verify_password_hash function.
	 *
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash)
	{
		return password_verify($password, $hash);
	}
}