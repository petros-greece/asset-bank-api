<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

  public function index(){

      $categories = Categories::all()->sort(function ($a, $b) {
          return strcmp($a['path'], $b['path']);
      });

      return $categories;


  }

  public function category($id){
    return Categories::findOrFail($id);
  }

  public function storeNewCategory(Request $request){
    try{
      $category = new Categories();
      $category->title = $request->title;
      $category->icon = $request->icon;
      $category->accId = $request->accId;
      $category->path = $request->path ? $request->path : '';
      if ($category->save()) {
        return response()->json(['status' => 'success', 'message' => $category]);
      }
    }
    catch(\Exception $e){
      return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }

  }

  public function update(Request $request, $id)
  {
      try {
          $category = Categories::findOrFail($id);
          $category->title = $request->title;
          $category->icon = $request->icon;
          $category->description = $request->description;
          $category->childrenNum = $request->childrenNum;
          $category->path = $request->path;
          $category->tags = $request->tags;
          $category->assets = $request->assets;

          if ($category->save()) {
              return response()->json(['status' => 'success', 'message' => 'Category updated successfully']);
          }
      } catch (\Exception $e) {
          return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
      }
  }

  public function destroy($id)
  {
      try {
          $category = Categories::findOrFail($id);

          if ($category->delete()) {
              return response()->json(['status' => 'success', 'message' => 'Category deleted successfully']);
          }
      } catch (\Exception $e) {
          return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
      }
  }

  public function storeCategoriesFromTree(Request $request, $accId){
      foreach($request->categories as $cat){
          if($cat->id){
              $category = Categories::findOrFail($cat->id);
              $category->title = $request->title;
              $category->icon = $request->icon;
              $category->childrenNum = $request->childrenNum;
              $category->path = $request->path;
              $category->save();
          }
          else{
              $category = new Categories();
              $category->title = $request->title;
              $category->icon = $request->icon;
              $category->accId = $request->accId;
              $category->path = $request->path;
              $category->save();
          }
      }
  }




}
