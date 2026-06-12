<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BlogDetailResource;
use App\Http\Resources\Api\V1\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()->orderByDesc('published_at');

        if ($category = trim((string) $request->query('category', ''))) {
            $query->where('category', $category);
        }

        return BlogResource::collection(
            $query->paginate($request->integer('per_page', 15))->appends($request->query())
        );
    }

    public function show(Blog $blog)
    {
        abort_unless($blog->is_published, 404);

        $blog->increment('views_count');

        return new BlogDetailResource($blog);
    }
}
