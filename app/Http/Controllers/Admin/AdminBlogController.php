<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Cat;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{
   //ブログ一覧表示
    public function index()
    {
        $blogs = Blog::latest('updated_at')->paginate(10);
        return view('admin.blogs.index',['blogs' => $blogs]);
    }

    //ブログ投稿画面
    public function create()
    {
        $categories = Category::all();
        $cats = Cat::all();
        return view('admin.blogs.create', ['categories' => $categories, 'cats' => $cats]);
    }

    
    //ブログ投稿処理
    public function store(StoreBlogRequest $request)
    {
        $validated = $request->validated();
        $validated['image'] = $request->file('image')->store('blogs', 'public');
        $blog = new Blog($validated);
        $blog->category()->associate($validated['category_id']);
        $blog->save();
        $blog->cats()->attach($validated['cats']);
 
        return to_route('admin.blogs.index')->with('success', 'ブログを投稿しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

   //指定したIDのブログの編集画面
    public function edit(Blog $blog)
    {
        $categories = Category::all();
        $cats = Cat::all();
        return view('admin.blogs.edit', ['blog' => $blog, 'categories' => $categories, 'cats' => $cats]);

    }

   //指定したIDのブログの更新処理
    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $updateData = $request->validated();

        //画像を変更する場合
        if($request->has('image'))
        {
            //変更前の画像削除
            Storage::disk('public')->delete($blog->image);
            //変更後の画像アップロード
            $updateData['image'] = $request->file('image')->store('blogs', 'public');
         }
            $blog->category()->associate($updateData['category_id']);
            $blog->cats()->sync($updateData['cats']);
            $blog->update($updateData);
            return to_route('admin.blogs.index')->with('success', 'ブログを更新しました');
    }

    //指定したブログの削除処理
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->cats()->detach();
        $blog->delete();
        Storage::disk('public')->delete($blog->image);        

        return to_route('admin.blogs.index')->with('success', 'ブログを削除しました');        
    }
}
