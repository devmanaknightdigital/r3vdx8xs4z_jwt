<?php
use \Firebase\JWT\JWT;

class Token_service
{

    public function generate_access_token($key, $domain, $expireAt, $payload)
    {
        $issuedAt   = time();
        $notBefore  = $issuedAt + 10;
        $expire = $notBefore + $expireAt;

        $token = [
            'iss' => $domain,
            'aud' => $domain,
            'iat'  => $issuedAt,           // Issued at: time when the token was generated
            'nbf'  => $notBefore,          // Not before
            'exp'  => $expire,             // Expire
            'data' => $payload
        ];

        return [
            'token' => JWT::encode($token, $key, 'HS256'),
            'expire_at' => $expire
        ];
    }

    /**
     * Generate Refresh Token
     * @param $repo
     * @param $user_id
     * @param $ttl
     * @return string
     */
    public function generate_refresh_token($repo, $user_id, $ttl)
    {
        $token_key = $repo->generate_key(25);

        $token_exist = $repo->token_exist_by_user($user_id, 'r');

        if ($token_exist) {
            $repo->delete_token($token_exist->id);
        }

        $token = $repo->create_token([
            'token' => $token_key,
            'data' =>  json_encode([
                'user_id' => $user_id,
                'date' => date('Y-m-d')
            ]),
            'type' => 'r',
            'user_id' => $user_id,
            'status' => 1,
            'ttl' => $ttl,
            'created_at' => date('Y-m-j H:i:s'),
            'expire_at' => date('Y-m-j H:i:s', time() + $ttl)
        ]);

        if (!$token)
        {
            $token = '';
        }
        else
        {
            $token = $token_key;
        }

        return $token;
    }

    public function validate_token ($key, $authorization_token)
    {
        if (strlen($authorization_token) > 0) {
            $jwt = str_replace('Bearer ', '', $authorization_token);
            try {
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                return (int)$decoded->data->user_id;
            } catch (\Throwable $th) {
                return FALSE;
            }

        }

        return FALSE;
    }
}