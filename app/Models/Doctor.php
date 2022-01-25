<?php

namespace App\Models;

class Doctor extends Model
{
    protected $table = 'doctors';

    #Methods
    public function findOfFailByUserId($userId)
    {
        $this->setWhere(['user_id', '=', $userId]);

        return $this->firstOrFail();
    }
}
