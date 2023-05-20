<?php


namespace App\Services\Api;


use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesApiService
{
    private Request $request;
    private ?Builder $filteredQuery;

    public function __construct(Request $request, QueryFilter $queryFilter)
    {
        $this->request = $request;
        $this->filteredQuery = $queryFilter->filter(new Category, $request);
    }

    public function getAll()
    {
        if (!is_null($this->filteredQuery)) {
            $categories = $this->filteredQuery->get()->toArray();
            return response()->json($categories);
        } else {
            $categories = Category::all()->toArray();
            return response()->json($categories);
        }
    }

    public function getById(int $id)
    {
        $category =Category::find($id)->toArray();
        return response()->json($category);
    }

    public function store(Request $request)
    {
        Category::create(
            [
                'name' => $request->name
            ]
        );
        return response()->noContent();
    }

    public function update(int $id, Request $request)
    {
        Category::whereId($id)
            ->update($request->toArray());
        return response()->noContent();
    }

    public function destroyById(int $id)
    {
        Category::whereId($id)
            ->delete();
        return response()->noContent();
    }

    public function destroy()
    {
        DB::table('categories')->delete();
        return response()->noContent();
    }
}

