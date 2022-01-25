<?php

namespace App\Models;

class User extends Model
{
    protected $table = 'users';

    const ROLE_DOCTOR = 1;
    const ROLE_PATIENT = 2;

    const ROLES_NAMES = [
        1 => 'doctor',
        2 => 'patient'
    ];

    public function getRoleSlug()
    {
        return $this::ROLES_NAMES[$this->role_id];
    }

    #Methods
    public function createPatient()
    {
        $patient = new Patient([
            'name' => $this->name,
            'email' => $this->email,
            'user_id' => $this->id,
        ]);
        $patient->save();
    }

    public function createDoctor($branchId)
    {
        $patient = new Doctor([
            'name' => $this->name,
            'branch_id' => $branchId,
            'user_id' => $this->id,
        ]);
        $patient->save();
    }

    public function firstOrFailByEmail(string $email): self
    {
        $this->setWhere(['email', '=', $email]);
        return $this->firstOrFail();
    }

    public function createToken(): Token
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $date = date("Y-m-d H:i");
        $token = new Token([
            'user_id' => $this->id,
            'status' => 1,
            'token' => $token,
            'date' => $date,
        ]);
        $token->save();
        return $token;
    }

    public function isPatient()
    {
        return $this->role_id == User::ROLE_PATIENT;
    }

    public function isDoctor()
    {
        return $this->role_id == User::ROLE_DOCTOR;
    }
}
