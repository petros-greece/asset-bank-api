<?php

namespace App\Http\Controllers;

use App\Models\Tree;
use Illuminate\Http\Request;

class TreeController extends Controller
{

    public function getTree($accountId){
        return Tree::where('accountId', $accountId)->orderByDesc('created_at')->first();
    }

    public function getTreeById($id, $accountId){
        return Tree::where('id', $id)->
                     where('accountId', $accountId)->
                     first();
    }

    public function getAccountTreeVersions($accountId){
        return Tree::where('accountId', $accountId)->get(['id', 'accountId','updated_at']);
    }


    public function addTree(Request $request){
        try{
            $category = new Tree();
            $category->categories = json_encode($request->categories);
            $category->accountId = $request->accountId;
            if ($category->save()) {
                return response()->json(['status' => 'success', 'message' => $category]);
            }
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

    }

    public function getTreeVersions($accountId){
        return Tree::where('accountId', $accountId)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get(['id','created_at']);
    }


}
