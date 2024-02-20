<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $perPage = $request->input('perPage') === 'ALL' ? PHP_INT_MAX : ($request->input('perPage') ?? 20);
        $date_min = $request->input('date_min');
        $date_max = $request->input('date_max');
        if ($date_min!==null && $date_min!=='' &&$date_min!=="Invalid date" ) {
           
            $date_min = \Carbon\Carbon::parse($date_min)->format('Y-m-d');
        }
        if ($date_max!==null &&$date_max!==''&&$date_max!=="Invalid date") {
            $date_max = \Carbon\Carbon::parse($date_max)->format('Y-m-d');
        }



    


        $articles = Article::query()
        ->select('articles.id', 'articles.name', 'articles.description', 'articles.image', 'articles.created_at', 'categories.name as category_name','users.name as user_name')
        ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')
        ->leftJoin('users', 'articles.user_id', '=', 'users.id')
        ->when($request->input('search'), function ($query, $search) {
            $query->where('articles.name', 'like', '%' . $search . '%')
            ->orWhere('categories.name', 'like', '%' . $search . '%')
          
            ->orWhere('users.name', 'like', '%' . $search . '%')
                ->orWhere('articles.description', 'like', '%' . $search . '%');
        })
        ->paginate($perPage)
        ->withQueryString();
    

        $search = $request->input('search');
        $perPage = $request->input('perPage');

        return inertia('Article/Index', compact('articles', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //select all categories and take just id and name 
        $categories = \App\Models\Category::select('id', 'name')->get();



        return inertia('Article/Create', compact('categories'));
        



    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
