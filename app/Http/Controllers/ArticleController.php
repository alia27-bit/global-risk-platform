<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index() { return view('articles.index', ['articles' => Article::with('user')->latest()->paginate(15)]); }
    public function create() { return view('articles.create'); }
    public function store(Request $request) { $data=$this->validated($request); $data['user_id']=$request->user()->id; $data['slug']=$this->slug($data['title']); Article::create($data); return redirect()->route('admin.articles.index')->with('success','Artikel dibuat.'); }
    public function show(Article $article) { return view('articles.show', compact('article')); }
    public function edit(Article $article) { return view('articles.edit', compact('article')); }
    public function update(Request $request, Article $article) { $data=$this->validated($request); if($data['title']!==$article->title)$data['slug']=$this->slug($data['title'],$article); $article->update($data); return redirect()->route('admin.articles.index')->with('success','Artikel diperbarui.'); }
    public function destroy(Article $article) { $article->delete(); return back()->with('success','Artikel dihapus.'); }
    private function validated(Request $request): array { return $request->validate(['title'=>['required','string','max:255'],'content'=>['required','string'],'status'=>['required','in:Draft,Published'],'published_at'=>['nullable','date']]); }
    private function slug(string $title, ?Article $ignore=null): string { $base=Str::slug($title) ?: 'article'; $slug=$base; $i=2; while(Article::where('slug',$slug)->when($ignore,fn($q)=>$q->whereKeyNot($ignore->id))->exists())$slug=$base.'-'.$i++; return $slug; }
}
