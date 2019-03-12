<?php
class Api extends CI_Controller
{

        public function login()
        {
            $this->load->model('users');
            $this->load->model('tokens');
            $this->load->library('token_service');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(403)
                    ->set_output(json_encode([
                    'errors' => $this->form_validation->error_array()
                    ]));
            }
            else
            {
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                if ($this->users->authenticate($email, $password))
                {
                    $user_id = $this->users->get_user_id_from_email($email);
                    $user    = $this->users->get_user($user_id);

                    $payload = $this->token_service->generate_access_token(
                        $this->config->item('encryption_key'),
                        $this->config->item('base_url'),
                        $this->config->item('jwt_expire_at'), [
                            'user_id' => $user_id
                        ]);

                    $payload['refresh_token'] = $this->token_service->generate_refresh_token(
                        $this->tokens,
                        $user_id,
                        $this->config->item('jwt_refresh_expire_at')
                    );

                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($payload));
                }
                else
                {
                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode([
                        'message' => 'invalid credentials'
                    ]));
                }
            }
        }

        public function register ()
        {
            $this->load->model('users');
            $this->load->model('tokens');
            $this->load->library('token_service');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');

            if ($this->form_validation->run() == FALSE)
            {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(403)
                    ->set_output(json_encode([
                    'errors' => $this->form_validation->error_array()
                    ]));
            }
            else
            {
                $email = $this->input->post('email');
                $password = $this->input->post('password');

                if ($this->users->create_user($email, $password))
                {
                    $user_id = $this->users->get_user_id_from_email($email);

                    $payload = $this->token_service->generate_access_token(
                        $this->config->item('encryption_key'),
                        $this->config->item('base_url'),
                        $this->config->item('jwt_expire_at'), [
                            'user_id' => $user_id
                        ]);

                    $payload['refresh_token'] = $this->token_service->generate_refresh_token(
                        $this->tokens,
                        $user_id,
                        $this->config->item('jwt_refresh_expire_at')
                    );

                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($payload));
                }
                else
                {
                    return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode([
                        'message' => 'invalid credentials'
                    ]));
                }
            }
        }

        public function profile ()
        {
            $this->load->model('users');
            $this->load->model('tokens');
            $this->load->library('token_service');
            $authorization_headers = $this->input->get_request_header('AUTHORIZATION');
            $user = $authorization_headers;
            $user_id = $this->token_service->validate_token ($this->config->item('encryption_key'), $authorization_headers);

            if ($user_id)
            {
                $user = $this->users->get_profile($user_id);
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($user));
            }
            else
            {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(401)
                    ->set_output(json_encode([
                        'message' => 'invalid credentials'
                    ]));
            }
        }

}