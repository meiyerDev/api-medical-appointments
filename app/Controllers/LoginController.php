<?php

namespace App\Controllers;

use App\Libs\Response;
use App\Models\User;
use Exception;

class LoginController extends Controller
{
    public function registerPatient()
    {
        $request = $this->getRequest();
        if ($request->missingAny(['name', 'email', 'password'])) throw new Exception("Data is Missing", 422);
        if (!$request->get('name') || !$request->get('email') || !$request->get('password')) throw new Exception("All fields are required", 422);

        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => md5($request->get('password')),
            'role_id' => User::ROLE_PATIENT,
        ]);
        $user->save();
        $user->createPatient();

        $response = new Response([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleSlug(),
                ]
            ]
        ], 201);

        return $response->toJson();
    }

    public function registerDoctor()
    {
        $request = $this->getRequest();
        if ($request->missingAny(['name', 'email', 'password', 'branch_id'])) throw new Exception("Data is Missing", 422);
        if (!$request->get('name') || !$request->get('email') || !$request->get('password') || !$request->get('branch_id')) throw new Exception("All fields are required", 422);

        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => md5($request->get('password')),
            'role_id' => User::ROLE_DOCTOR,
        ]);
        $user->save();
        $user->createDoctor($request->get('branch_id'));

        $response = new Response([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleSlug(),
                    'branch_id' => $request->get('branch_id'),
                ]
            ]
        ], 201);

        return $response->toJson();
    }

    public function login()
    {
        $request = $this->getRequest();
        if ($request->missingAny(['email', 'password'])) throw new Exception("Data is Missing", 422);
        if (!$request->get('email') || !$request->get('password')) throw new Exception("Both fields are required", 422);

        try {
            $user = new User();
            $user = $user->firstOrFailByEmail($request->get('email'));
            if ($user->password != md5($request->get('password'))) throw new \App\Exceptions\ModelNotFoundException;
        } catch (\App\Exceptions\ModelNotFoundException $th) {
            throw new Exception("Your credentials are invalid", 422);
        }

        $token = $user->createToken();
        $response = new Response([
            'data' => [
                'token' => $token,
            ]
        ]);

        return $response->toJson();
    }
}
