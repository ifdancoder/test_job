<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ObjectApp;
use Illuminate\Http\Request;
use App\Models\CategoriesGroup;
use Illuminate\Support\Facades\Log;

class ObjectController extends Controller
{
    public function index(Request $request) {
        $groups = CategoriesGroup::all();

        $categories = Category::whereIn('alias', explode(',', $request->input('categories', '')))->get();
        $categoryIds = $categories->pluck('id')->toArray();

        $objects = ObjectApp::all();

        Log::debug($categories);

        if (!(count($categories) === 0)) {
            $objects = ObjectApp::whereIn('id', function ($query) use ($categoryIds) {
                $query->select('object_id')
                    ->from('category_object')
                    ->whereIn('category_id', $categoryIds)
                    ->groupBy('object_id')
                    ->havingRaw('COUNT(DISTINCT category_id) = ?', [count($categoryIds)]);
            })->get();
        }
        
        Log::debug($objects);

        $url = route('objects.index', ['categories' => $categories]);

        return view('objects', compact('groups', 'categoryIds', 'objects', 'categories', 'url'));
    }
    public function indexRaw(Request $request) {
        $objects = ObjectApp::all();

        $categories = Category::whereIn('alias', explode(',', $request->input('categories', '')))->get();
        $categoryIds = $categories->pluck('id')->toArray();

        if (!(count($categories) === 0)) {
            $objects = ObjectApp::whereIn('id', function ($query) use ($categoryIds) {
                $query->select('object_id')
                    ->from('category_object')
                    ->whereIn('category_id', $categoryIds)
                    ->groupBy('object_id')
                    ->havingRaw('COUNT(DISTINCT category_id) = ?', [count($categoryIds)]);
            })->get();
        }
        
        return response()->json(['theHTML' => view('objects_only', ["objects" => $objects])->render()]);
    }
}
