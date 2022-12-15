<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function getConfig($accountId){
        try {
            return Config::where([
                    ['accountId', '=', $accountId]
            ])->first();
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }

    public function addConfigEditorSettings(Request $request){
        try{
            $settings = [];
            $settings[$request->type] = $request->editorSettings;
            $config = new Config();
            $config->accountId = $request->accountId;
            $config->editorSettings = json_encode($settings);
            //$asset-> = $assetAssoc[''];
            if ($resp = $config->save()) {
                //$config->id = $resp;
               $config->editorSettings = json_decode($config->editorSettings);
                return $config->editorSettings;
            }
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }

    public function addOrUpdateConfigEditorSettings(Request $request){

        if(!$request->editorSettings){
            return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
        }

        $editorSettings = json_encode($request->editorSettings);
        $storedConfig = Config::where([['accountId', '=', $request->accountId]])->first();

        if(!$storedConfig) {
            $config = $this->addConfigEditorSettings($request);
            return response()->json(['status' => 'created', 'data' => $config]);
        }
        try {
            $storedConfigSettings = json_decode($storedConfig->editorSettings, true);
            $storedConfigSettings[$request->type] = $request->editorSettings;

            $config = Config::where([
                    ['accountId', '=', $request->accountId]
                ]
            )->update(['editorSettings' => json_encode($storedConfigSettings)]);
            return response()->json(['status' => 'updated', 'data' => $storedConfigSettings]);
        }
        catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }



}
