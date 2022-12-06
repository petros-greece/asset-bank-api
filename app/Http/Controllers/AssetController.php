<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
//use Intervention\Image\Image;
use Intervention\Image\Facades\Image;

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
            $time = 'U-' . time();
            $imageFileName = $time . '.' . $file_ext;
            $imageFileNameSm = $time. '-sm.' . $file_ext;

            $imageRow = [
                'size'=> $request->file('image')->getSize(),
                'ext' => $file_ext,
                'src' => $imageFileName,
                'accountId' => $request->accountId
            ];

            $img = Image::make($request->file('image')->path());
            $img->resize(200, null, function ($const) {
                $const->aspectRatio();
            })->save($destination_path.$imageFileNameSm);

            if ($request->file('image')->move($destination_path, $imageFileName)) {

                $asset = $this->addAssetRow($imageRow);
                if($asset){
                    return $this->responseRequestSuccess($imageFileName);
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

    public function getAssetRow($accountId, $src){
        try {
            return Assets::where([
                    ['src', '=', $src],
                    ['accountId', '=', $accountId]]
            )->first();
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }

    public function getAssetsTotalSize($accountId){
        return Assets::where('accountId', $accountId)->
            sum(['size']);
    }

    public function addTagsToAsset(Request $request)
    {
        try {
            return Assets::where([
                ['src', '=', $request->src],
                ['accountId', '=', $request->accountId]]
            )->update(['tags' => $request->tags]);
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }

    public function getTagsForAccount($accountId)
    {
        try {
            $tags = Assets::where([
                    ['accountId', '=', $accountId]
                ])->get(['tags']);
            $allTags = [];
            foreach ($tags as $tag){
                $t = json_decode($tag->tags);
               // $tt = json_decode( $t->tags );
                $allTags = array_merge($allTags, $t);

            }
            return array_unique($allTags);
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }

    public function getAssetsForTags($accountId, $tags)
    {
        try {
            $query = [
                ['accountId', '=', $accountId],
            ];

            $tags = explode(',', $tags);

            foreach($tags as $tag){
                array_push($query, ['tags', 'like', '%"'.$tag.'"%']);
            }


            return Assets::where($query)->get();

        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
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
