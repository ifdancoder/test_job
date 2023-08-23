<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ObjectApp;
use Illuminate\Http\Request;
use App\Models\CategoriesGroup;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class ObjectController extends Controller
{
    private function getObjectsByCategoryIds($categoryIds) {
        return ObjectApp::whereIn('id', function ($query) use ($categoryIds) {
            $query->select('object_id')
            ->from('category_object')
            ->whereIn('category_id', $categoryIds)
            ->groupBy('object_id')
            ->havingRaw('COUNT(DISTINCT category_id) = ?', [count($categoryIds)]);
        })->get();
    }
    private function GetSharedData(Request $request) {
        $categories = Category::whereIn('alias', explode(',', $request->input('categories', '')))->get();
        $categoryIds = $categories->pluck('id')->toArray();

        $objects = ObjectApp::all();
        if (!(count($categories) === 0)) {
            $objects = $this->getObjectsByCategoryIds($categoryIds);
        }
        View::share("sharedData", ["categories" => $categories, "objects" => $objects, "url" => route('objects.index', ['categories' => $categories]), "categoryIds" => $categoryIds]);
    }
    public function index(Request $request) {
        $groups = CategoriesGroup::all();

        $this->GetSharedData($request);

        return view('objects', compact('groups'));
    }
    public function indexRaw(Request $request) {
        $this->GetSharedData($request);
        
        return response()->json(['theHTML' => view('objects_only')->render()]);
    }
}
