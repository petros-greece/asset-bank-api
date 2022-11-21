<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{

    public function uploadImage(Request $request)
    {
        $response = null;

        //return $this->responseRequestSuccess($request->file('image')->getSize());
        if ($request->hasFile('image')) {
            $original_filename = $request->file('image')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = "../storage/assets/$request->accountId/";
            $image = 'U-' . time() . '.' . $file_ext;

            $imageRow = [
                'size'=> $request->file('image')->getSize(),
                'ext' => $file_ext,
                'src' => $image,
                'accountId' => $request->accountId
            ];


            if ($request->file('image')->move($destination_path, $image)) {

                $asset = $this->addAssetRow($imageRow);
                if($asset){
                    return $this->responseRequestSuccess($image);
                }
            } else {
                return $this->responseRequestError('Cannot upload file', 404);
            }
        } else {
            return $this->responseRequestError('File not found', 404);
        }
    }

    private function addAssetRow($assetAssoc){
        try{
            $asset = new Assets();
            $asset->accountId = $assetAssoc['accountId'];
            $asset->src = $assetAssoc['src'];
            $asset->size = $assetAssoc['size'];
            $asset->ext = $assetAssoc['ext'];
            //$asset-> = $assetAssoc[''];
            if ($asset->save()) {
                return $asset;
            }
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }

    public function getAssets($accountId, $limit, $offset){
        $total = Assets::where('accountId', $accountId)->count();
        return [
            'total' => $total,
            'assets' => Assets::where('accountId', $accountId)->
            orderByDesc('created_at')->
            limit($limit)->
            offset($offset)->
            get()
        ];
    }

    public function getAsset(Request $request){
        //return response()->json(['status' => 'error', 'message' => $request->accountId], 404);

        try {
            return response()->file("../storage/assets/$request->accountId/$request->path.$request->ext", [
                    'Access-Control-Allow-Origin'      => '*',
                    'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
                    'Access-Control-Allow-Credentials' => 'true',
                    'Access-Control-Max-Age'           => '86400',
                    'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With',
                    'Content-Type'=> "image/$request->ext"
            ])->send();
        }


        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }





    protected function responseRequestSuccess($ret)
    {
        return response()->json(['status' => 'success', 'data' => $ret], 200)

            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    protected function responseRequestError($message = 'Bad request', $statusCode = 200)
    {
        return response()->json(['status' => 'error', 'error' => $message], $statusCode)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }


}
