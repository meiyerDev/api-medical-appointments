<?php

namespace App\Controllers;

use App\Exceptions\Unauthorized;
use App\Libs\Response;
use App\Models\Date;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Exception;

class DateController extends Controller
{
    public function getByAuth()
    {
        $this->onlyAuthenticated();

        $user = $this->getAuth();
        if ($user->isPatient()) {
            $patient = new Patient;
            $patient->setWhere(['user_id', '=', $user->id]);
            $patient = $patient->firstOrFail();

            $date = new Date;
            $date->setWhere(['patient_id', '=', $patient->id]);
        } else {
            $doctor = new Doctor();
            $doctor->setWhere(['user_id', '=', $user->id]);
            $doctor = $doctor->firstOrFail();

            $date = new Date;
            $date->setWhere(['doctor_id', '=', $doctor->id]);
        }

        $dates = $date->get();
        $response = new Response([
            'data' => $dates
        ]);

        return $response->toJson();
    }

    public function store()
    {
        $this->onlyAuthenticated();

        // Validate if user is patient
        $user = $this->getAuth();
        if (!$user->isPatient()) throw new Unauthorized;

        $request = $this->getRequest();
        if ($request->missingAny(['date_at', 'branch_id'])) throw new Exception("Data is Missing", 422);
        if (!$request->get('date_at') || !$request->get('branch_id')) throw new Exception("All fields are required", 422);

        $patient = new Patient;
        $patient = $patient->findOfFailByUserId($user->id);

        $date = new Date([
            'branch_id' => $request->get('branch_id'),
            'date_at' => $request->get('date_at'),
            'patient_id' => $patient->id,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $date->save();

        $response = new Response([
            'data' => $date
        ]);

        return $response->toJson();
    }

    public function getDatesOfDayByAuth()
    {
        $this->onlyAuthenticated();

        // Validate if user is patient
        $user = $this->getAuth();
        if (!$user->isDoctor()) throw new Unauthorized;

        $doctor = new Doctor;
        $doctor = $doctor->findOfFailByUserId($user->id);

        $date = new Date;
        $dates = $date->getAllByDoctorIdAndDate($doctor->id, date('Y-m-d'));

        $response = new Response([
            'data' => $dates
        ]);

        return $response->toJson();
    }

    public function getDatesNotConfirmed()
    {
        $this->onlyAuthenticated();

        // Validate if user is patient
        $user = $this->getAuth();
        if (!$user->isDoctor()) throw new Unauthorized;

        $doctor = new Doctor;
        $doctor = $doctor->findOfFailByUserId($user->id);

        $date = new Date;
        $dates = $date->getAllByBranchIdAndDateAndNotConfirmed($doctor->branch_id, date('Y-m-d'));

        $response = new Response([
            'data' => $dates
        ]);

        return $response->toJson();
    }

    public function confirmDate()
    {
        $this->onlyAuthenticated();

        // Validate if user is patient
        $user = $this->getAuth();
        if (!$user->isDoctor()) throw new Unauthorized;

        $request = $this->getRequest();
        if ($request->missingAny(['date_id'])) throw new Exception("Date Id is Missing", 400);

        $doctor = new Doctor;
        $doctor = $doctor->findOfFailByUserId($user->id);

        $date = new Date;
        $date = $date->find($request->get('date_id'));

        if ($date->doctor_id) throw new Exception("The date has already been confirmed.", 422);

        $date->fill([
            'doctor_id' => $doctor->id,
            'confirmed_at' => date('Y-m-d H:i:s'),
        ]);
        $date->save();

        $response = new Response([
            'data' => $date
        ]);

        return $response->toJson();
    }
}
