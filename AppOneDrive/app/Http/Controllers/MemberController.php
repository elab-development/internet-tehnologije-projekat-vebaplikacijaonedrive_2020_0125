<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'firm_pib' => 'required|string|size:9',
            'privileges' => [
                'required',
                'string',
                'max:255',
                Rule::in(['Read', 'Write']),
            ]
            ,
        ]);
 
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $user_id=$request->input('user_id');
        $firm_id=$request->input('firm_pib');
        $privileges=$request->input('privileges');

        try{
            $member=Member::create(['user_id'=>$user_id,'firm_pib'=>$firm_id,'AddedAt'=>new DateTime(),'privileges'=>$privileges]);
            return response()->json(['message' => 'Member added successfully', 'member' => $member], 201);
        }
        catch(\Exception $ex){
            return response()->json(['message' => 'Error while adding user to firm'], 400);
        }
        
    }

    // Gets a specific members
    public function show(Request $request)
    {
        $pib = $request->route('PIB');
        $validator = Validator::make(['PIB' => $pib], [
            'PIB' => 'required|string|size:9',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $mem = Member::where('firm_pib', $pib)->with('User')->get();
        if($mem->count()==0)return response()->json(["message"=>"Firm not found"],404);
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
        $validator = Validator::make(['ID'=>$ID,'PIB' => $PIB,'privileges'=>$request->input('privileges')], [
            'ID'=>'required|numeric',
            'PIB' => 'required|string|size:9',
            'privileges' => [
                'required',
                'string',
                'max:255',
                Rule::in(['Read', 'Write']),
            ]
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $result=Member::Where('user_id',$ID)->Where('firm_pib',$PIB)->update(['privileges'=>$request->input('privileges')]);
        if($result==1)return response()->json(['message' => 'Member updated successfully', 'member' => Member::Where('user_id',$ID)->Where('firm_pib',$PIB)->get()], 201);
        else return response()->json(['message' => 'Error while updating member or there is nothing to update'],400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ID,$PIB)
    {
        $validator = Validator::make(['ID'=>$ID,'PIB' => $PIB], [
            'ID'=>'required|numeric',
            'PIB' => 'required|string|size:9',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $result=Member::Where('user_id',$ID)->Where('firm_pib',$PIB)->delete();
        if($result==1) return response()->json(['message' => 'Member deleted successfully'], 201);
        else return response()->json(['message' => 'Error while deleting member'],400);
    }
}
