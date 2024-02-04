<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\error;

class PaginationFirmController extends Controller
{
    //
    public function index(Request $request, $perPage, $page = 1)
    {
        $firms=DB::table('firms')->leftjoin('members','firms.PIB','=','members.firm_pib')->where('firms.user_id','=',auth()->id())->orWhere('members.user_id','=',auth()->id())->select('firms.Name','firms.Address','firms.PIB','firms.CreatedAt','firms.user_id','members.user_id as memberid')->paginate($perPage, ['*'], 'page', $page);
        $data=$firms->items();
        $stranice=$firms->lastPage();
        return response()->json(['firms' => $data, 'pages' => $stranice], 201);
    }
    public function indexOwner(Request $request, $perPage, $page = 1)
    {
        $firms=DB::table('firms')->where('firms.user_id','=',auth()->id())->select('firms.Name','firms.Address','firms.PIB','firms.CreatedAt','firms.user_id')->paginate($perPage, ['*'], 'page', $page);
        $data=$firms->items();
        $stranice=$firms->lastPage();
        return response()->json(['firms' => $data, 'pages' => $stranice], 201);
    }
    public function indexMember(Request $request, $perPage, $page = 1)
    {
        $firms=DB::table('firms')->leftjoin('members','firms.PIB','=','members.firm_pib')->where('members.user_id','=',auth()->id())->select('firms.Name','firms.Address','firms.PIB','firms.CreatedAt','firms.user_id','members.user_id as memberid')->paginate($perPage, ['*'], 'page', $page);
        $data=$firms->items();
        $stranice=$firms->lastPage();
        return response()->json(['firms' => $data, 'pages' => $stranice], 201);
    }
}
