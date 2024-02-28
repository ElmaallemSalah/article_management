<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Inertia\Response
    {
        $perPage = $request->input('perPage') === 'ALL' ? PHP_INT_MAX : ($request->input('perPage') ?? 20);
        $search = $request->input('search');
    
        $articles = Article::with(['category', 'user'])
                            ->withSearch($search)
                            ->orderByDesc('id')
                            ->paginate($perPage)
                            ->withQueryString();
    
        return Inertia::render('Article/Index', [
            'articles' => $articles,
            'search' => $search,
            'perPage' => $perPage,
        ]);
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
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
    
        // Handle image upload
        $logoName = $this->uploadImageOnCreate($request);
        Article::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category'],
            'user_id' => Auth::user()->id,
            'image' => '/images/articles/' . $logoName,
        ]);
    
        return redirect()->route('articles.index')->with('status', [
            'type' => 'success',
            'action' => 'Success',
            'text' => 'Article created successfully!'
        ]);
    }
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        
        $validatedData = $request->validated();

        $logoName = $this->uploadImageOnUpdate($request, $article);

        $article->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category'],
            'image' => '/images/articles/' . $logoName,
        ]);

        return redirect()->route('articles.index')
            ->with(
                'status',
                [
                    'type' => 'success',
                    'action' => 'Success',
                    'text' => 'Article updated successfully!'
                ]
            );
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


        

        $categories = Category::select('id', 'name')->get();



        return inertia('Article/Edit', compact('categories', 'article'));
    }



    /**
     * Update the specified resource in storage.
     */
    private function uploadImageOnCreate(Request $request): string
    {
        $logoName = 'no_article.png';
        if ($request->hasFile('image')) {
            $logoName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/articles'), $logoName);

          

        
        }
        return $logoName;
      
    }
    private function uploadImageOnUpdate(Request $request, Article $article): string
    {
        if ($request->hasFile('image')) {
            $logoName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/articles'), $logoName);

            $this->deleteOldImage($article);

            return $logoName;
        }

        return $article->image;
    }
    private function deleteOldImage(Article $article): void
    {
        if ($article->image) {
            $oldImagePath = public_path( $article->image);
            dd($oldImagePath);

            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath); // Delete the old image file
            }
        }
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('articles.index')
            ->with('status', [
                'type' => 'success',
                'action' => 'Success',
                'text' => 'Article Deleted Successfully!'
            ]);
    }
}
