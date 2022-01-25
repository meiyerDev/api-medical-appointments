<?php

namespace App\Models;

class Date extends Model
{
    protected $table = 'dates';

    #Methods
    public function getAllByDoctorIdAndDate($doctorId, string $date)
    {
        $this->setWhere([
            ['doctor_id', '=', $doctorId],
            ['date_at', '=', $date]
        ]);

        return $this->get();
    }

    public function getAllByBranchIdAndDateAndNotConfirmed($branchId, string $date)
    {
        $this->setJoin("INNER JOIN `patients` ON `dates`.`patient_id` = `patients`.`id` INNER JOIN `branches` ON `dates`.`branch_id` = `branches`.`id` ");

        $this->setWhere([
            ['branch_id', '=', $branchId],
            ['date_at', '>=', $date],
            ['confirmed_at', 'is', NULL]
        ]);

        return $this->get([
            "`dates`.`id`",
            "`branches`.`name` as 'branch_name'",
            "`patients`.`name` as 'patient_name'",
            "`patients`.`email` as 'patient_email'",
            "`dates`.`date_at`",
        ]);
    }
}
