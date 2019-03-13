<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Token Model
 * @author manaknight INC.
 *
 */
class Tokens extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Get Token
	 *
	 * @param integer $id
	 * @return token
	 */
	public function get_token($id)
    {
		$this->db->from('tokens');
        $this->db->where('id', $id, TRUE);
        return $this->db->get()->row();
	}

	/**
	 * Delete Token
	 *
	 * @param integer $id
	 * @return token
	 */
	public function delete_token($id)
    {
        $this->db->where('id', $id, TRUE);
        return $this->db->delete('tokens');
	}

	/**
	 * Get Token by user id
	 *
	 * @param integer $user_id
	 * @param string $type
	 * @return token
	 */
	public function token_exist_by_user($user_id, $type='r')
    {
		$this->db->from('tokens');
        $this->db->where('user_id', $user_id, TRUE);
        $this->db->where('type', $type, TRUE);
        return $this->db->get()->row();
	}

	/**
	 * Get Token by user by type
	 *
	 * @param integer $user_id
	 * @param string $type
	 * @return token
	 */
	public function token_exist($token, $type='r')
    {
		$this->db->from('tokens');
        $this->db->where('token', $token, TRUE);
        $this->db->where('type', $type, TRUE);
        return $this->db->get()->row();
	}

	/**
	 * Get all Token
	 *
	 * @return array token
	 */
	public function get_tokens()
    {
		$this->db->from('tokens');
        return $this->db->get()->result();
	}

	/**
	 * Create Token
	 *
	 * @param array $data
	 * @return Token
	 */
	public function create_token($data)
	{
		return $this->db->insert('tokens', $data, TRUE);
	}

	/**
	 * Edit Token
	 * @param array $data
	 * @param integer $id
	 * @return bool
	 */
	public function edit_token($data, $id)
	{
		$this->db->where('id', $id, TRUE);
		return $this->db->update('tokens', $data);
	}

	/**
	 * Generate a new key
	 * @param $length
	 * @return string
	 */
	public function generate_key ($length)
	{
		return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
	}
}