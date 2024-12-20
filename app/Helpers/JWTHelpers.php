<?php

// use App\Models\User;
// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;
// use Illuminate\Auth\AuthenticationException;

// // get jwt info from header
// function GetRawJWT()
// {
//     // check if header exists
//     if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
//         throw new AuthenticationException('authorization header not found');
//     }

//     // check if bearer token exists
//     if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
//         throw new AuthenticationException('token not found');
//     }

//     // extract token
//     $jwt = $matches[1];
//     if (!$jwt) {
//         throw new AuthenticationException('could not extract token');
//     }

//     return $jwt;
// }

// function DecodeRawJWT($jwt)
// {
//     // Load the public key for RS256
//     $publicKey = file_get_contents(storage_path('oauth-public.key')); // Adjust the path to your public key file

//     $segments = explode('.', $jwt);
//     $header = json_decode(base64_decode($segments[0]), true);

//     try {
//         $token = JWT::decode($jwt, new Key($publicKey, $header['alg'])); // Use RS256 instead of HS512
//         return $token;
//     } catch (\Exception $e) {
//         throw new AuthenticationException('unauthorized');
//     }
// }

// function GetAndDecodeJWT()
// {
//     $jwt = GetRawJWT();
//     $token = DecodeRawJWT($jwt);

//     return $token;
// }

// function authId()
// {
//     $jwt = GetRawJWT();
//     $token = DecodeRawJWT($jwt);

//     return $token->sub;
// }

// function author()
// {
//     $jwt = GetRawJWT();
//     $token = DecodeRawJWT($jwt);

//     return User::find($token->sub);
// }
