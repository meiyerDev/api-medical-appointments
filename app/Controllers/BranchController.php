<?php

namespace App\Controllers;

use App\Libs\Response;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branch = new Branch;
        $branches = $branch->get();

        $response = new Response([
            'data' => $branches
        ]);

        return $response->toJson();
    }
}
