<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id=$request->input('user_id');
        $firm_id=$request->input('firm_pib');
        $privileges=$request->input('privileges');
        $member=Member::create(['user_id'=>$user_id,'firm_pib'=>$firm_id,'AddedAt'=>new DateTime(),'privileges'=>$privileges]);
        return response()->json(['message' => 'Member added successfully', 'member' => $member], 201);
    }

    // Gets a specific member
    public function show(Request $request)
    {
        $mem = Member::where('firma_pib', $request->route("PIB"))->get();
        return MemberResource::collection($mem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ID,$PIB)
    {
        $result=Member::Where('user_id',$ID)->Where('firm_pib',$PIB)->update(['privileges'=>$request->input('privileges')]);
        if($result==1)return response()->json(['message' => 'Member updated successfully', 'member' => Member::Where('user_id',$ID)->Where('firm_pib',$PIB)->get()], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ID,$PIB)
    {
        $result=Member::Where('user_id',$ID)->Where('firm_pib',$PIB)->delete();
        if($result==1) return response()->json(['message' => 'Member deleted successfully'], 201);
    }
}
