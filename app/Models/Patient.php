<?php

namespace App\Models;

class Patient extends Model
{
    protected $table = 'patients';

    #Methods
    public function findOfFailByUserId($userId)
    {
        $this->setWhere(['user_id', '=', $userId]);

        return $this->firstOrFail();
    }
}
