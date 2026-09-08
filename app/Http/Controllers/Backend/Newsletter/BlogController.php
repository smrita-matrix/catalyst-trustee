<?php

namespace App\Http\Controllers\Backend\Newsletter;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    private const IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'webp'];

    public function index()
    {
        $blogs = Blog::whereNull('deleted_at')->newestFirst()->get();

        return view('backend.newsletter.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('backend.newsletter.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(), $this->messages());

        Blog::create($this->payload($request) + [
            'slug'       => Blog::uniqueSlug($request->title),
            'created_at' => Carbon::now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('blogs.index')->with('message', 'Blog post added successfully!');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);

        return view('backend.newsletter.blogs.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate($this->rules($id), $this->messages());

        $data = $this->payload($request, $blog) + [
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ];

        // The address only changes if the post is renamed, so a link that has
        // already been shared keeps working for as long as possible.
        if ($request->title !== $blog->title) {
            $data['slug'] = Blog::uniqueSlug($request->title, $blog->id);
        }

        $blog->update($data);

        return redirect()->route('blogs.index')->with('message', 'Blog post has been successfully updated!');
    }

    /** Show or hide a post on the website, straight from the list. */
    public function toggleStatus($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $blog->update([
                'status'      => $blog->status ? 0 : 1,
                'modified_at' => Carbon::now(),
                'modified_by' => Auth::id(),
            ]);

            return redirect()->back()->with('message', '"' . $blog->title . '" is now '
                . ($blog->status ? 'showing on' : 'hidden from') . ' the website.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Blog::findOrFail($id)->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('blogs.index')->with('message', 'Blog post deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                            */
    /* ------------------------------------------------------------------ */

    private function payload(Request $request, ?Blog $blog = null): array
    {
        $image = $blog?->image;

        if ($request->hasFile('image')) {
            if ($image) { $this->deleteImage($image); }
            $image = $this->uploadImage($request->file('image'));
        }

        return [
            'title'        => $request->title,
            'excerpt'      => $request->excerpt,
            'body'         => $request->body,
            'author'       => $request->author,
            'published_on' => $request->published_on ?: null,
            'image'        => $image,
            'sort_order'   => $request->sort_order ?? 0,
            'status'       => $request->has('status') ? 1 : 0,
        ];
    }

    private function rules($id = null): array
    {
        return [
            'title'        => 'required|string|max:255',
            'excerpt'      => 'nullable|string|max:600',
            'body'         => 'required|string',
            'author'       => 'nullable|string|max:120',
            'published_on' => 'nullable|date',
            'sort_order'   => 'nullable|integer',
            'image'        => 'nullable|image|mimes:' . implode(',', self::IMAGE_TYPES) . '|max:8192',
        ];
    }

    private function messages(): array
    {
        return [
            'title.required' => 'The Title is required.',
            'body.required'  => 'The post itself cannot be empty.',
            'image.max'      => 'The picture must not be larger than 8MB.',
            'image.mimes'    => 'The picture must be a ' . implode(', ', self::IMAGE_TYPES) . ' file.',
        ];
    }

    private function uploadImage($file): string
    {
        $destination = public_path('blog-uploads');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $name = 'blog_' . time() . '_' . Str::random(8) . '.' . strtolower($file->getClientOriginalExtension());
        $file->move($destination, $name);

        return $name;
    }

    private function deleteImage(string $name): void
    {
        $path = public_path('blog-uploads/' . $name);

        if (is_file($path)) {
            @unlink($path);
        }
    }
}
