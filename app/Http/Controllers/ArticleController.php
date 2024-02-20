<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
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
     
        $categories = Category::select('id', 'name')->get();



        return inertia('Article/Create', compact('categories'));
        



    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //validation
        $request->validate([
            'name' => 'required|unique:articles|max:255|min:3',
            'description' => 'required',
            'category' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/articles'), $imageName);
        } 

        $article = new Article();
        $article->name = $request->name;
        $article->description = $request->description;
        $article->category_id = $request->category;
        $article->user_id = auth()->user()->id;
        $article->image = 'images/articles/'.$imageName;
        $article->save();
        return redirect()->route('articles.index')->with('status', ['type' => 'success', 'action' => 'Success', 'text' => 'Article created successfully!']);
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
    public function edit(Request $request)
    {


        $article = Article::findOrFail($request->id);
       
        $categories = Category::select('id', 'name')->get();



        return inertia('Article/Edit', compact('categories', 'article'));
        



     
    }



    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request)

    {
        $company = Article::findOrFail($request->id);
        $request->validate([
            'name' => 'required|string|max:255|unique:articles,name,' . $company->id,
            'email' => 'required|email|max:255|unique:articles,email,' . $company->id,
            'website' => 'required|max:255|unique:companies,website,' . $company->id . '|url|min:10',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // if there is logo image condition
        if ($request->hasFile('logo')) {
            $logoName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('images'), $logoName);
            if ($company->logo && $company->logo !== 'profile_default.jpg') {
                $oldImagePath = public_path('images/' . $company->logo);


                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Delete the old image file
                }
            }
        } else {



            $logoName = $company->logo;
        }

        

        $company->name = $request->name;
        $company->website = $request->website;
        $company->email = $request->email;
        $company->logo = $logoName;
        $company->save();

        return redirect()->route('companies.index')->with('status', ['type' => 'success', 'action' => 'Success', 'text' => 'Company updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $id)
    {
        $company = Article::find($id->id);
        $company->delete();


        return redirect()->route('articles.index')->with('status', ['type' => 'success', 'action' => 'Success', 'text' => 'Article Delete Successfully!']);


        //  with('message', 'Blog Delete Successfully');
    }
}
