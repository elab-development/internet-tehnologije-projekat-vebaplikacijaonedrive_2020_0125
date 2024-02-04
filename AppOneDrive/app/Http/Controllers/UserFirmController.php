<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use Illuminate\Http\Request;

class UserFirmController extends Controller
{
    public function index($id)
    {
        $firms=Firm::where('user_id',$id)->get();
        return response()->json(['message' => 'Firms created by User', 'firms' => $firms], 200);
    }
}
