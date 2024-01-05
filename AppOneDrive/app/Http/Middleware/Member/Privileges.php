<?php

namespace App\Http\Middleware\Member;

use App\Models\Firm;
use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Privileges
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,string $role): Response
    {
        $firmName=$request->route("firmName");
        if(!isset($firmName) && $request->route("PIB")!=null){
            $firmName=Firm::find($request->route("PIB"))->Name;
        }
        else if(!isset($firmName) && $request->input("PIB")!=null){
            $firmName=Firm::find($request->input("PIB"))->Name;
        }
        else if(!isset($firmName) && $request->input("firm_pib")!=null){
            $firmName=Firm::find($request->input("firm_pib"))->Name;
        }
        else if(!isset($firmName))return response()->json(["message"=>"Bad request"],400);

        if($role=='admin'){
            $admin=Firm::Where('user_id',auth()->id())->Where('Name',$firmName)->get();
            if($admin->count()==0)return response()->json(["message"=>"you don't have the right privilege"],401);
        }
        else if($role=='adminORwrite'){
            $admin=Firm::Where('user_id',auth()->id())->Where('Name',$firmName)->get();
            if($admin->count()==0){
                $pib=Firm::Where("Name",$firmName)->first()->PIB;
                $member=Member::Where('user_id',auth()->id())->Where('firm_pib',$pib)->get();
                if($member->count()==0)return response()->json(["message"=>"Can't find specific member in a team"],400);
                if($member[0]->privileges!="Write")return response()->json(["message"=>"you don't have the right privilege"],401);
            } 
        }

        return $next($request);
    }
}
