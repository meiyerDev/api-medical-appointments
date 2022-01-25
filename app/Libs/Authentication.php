<?php

namespace App\Libs;

use App\Exceptions\Unauthenticated;
use App\Models\Token;
use App\Models\User;

class Authentication
{
    /** @var Request */
    private $request;

    /** @var User */
    private $user;

    function __construct(Request $request)
    {
        $this->request = $request;
        $this->user = null;
        $this->createSession();
    }

    private function createSession()
    {
        $bearerToken = $this->request->getHeader('Authorization');
        $bearerToken = str_replace("Bearer ", "", $bearerToken);

        $token = new Token;
        $token->setWhere(['token', '=', $bearerToken]);
        $token = $token->first();

        if ($token) {
            $user = new User;
            $user = $user->find($token->user_id);

            $this->setAuth($user);
            $this->request->addUser([
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role_id' => $user->role_id,
            ]);
        }
    }

    private function setAuth(User $user)
    {
        $this->user = $user;
    }

    public function getAuth()
    {
        return $this->user;
    }

    public function throwIfUnauthenticated()
    {
        if ($this->user) return false;
        throw new Unauthenticated;
    }
}
