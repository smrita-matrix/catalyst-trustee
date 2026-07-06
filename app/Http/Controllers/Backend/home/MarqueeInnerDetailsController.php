<?php

namespace App\Http\Controllers\Backend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\MarqueeInnerDetails;


class MarqueeInnerDetailsController extends Controller
{
    public function index()
    {
        $marquee_inner = MarqueeInnerDetails::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.home-page.marquee-inner.index', compact('marquee_inner'));
    }

    public function create(Request $request)
    {
        return view('backend.home-page.marquee-inner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|array|min:1',
            'title.*' => 'required|string|max:255',
        ], [
            'title.required'   => 'Please add at least one marquee item.',
            'title.*.required' => 'The marquee item text is required.',
            'title.*.max'      => 'Each marquee item must not exceed 255 characters.',
        ]);

        $now    = Carbon::now();
        $userId = Auth::id();

        foreach ($request->title as $title) {
            $title = trim($title);

            if ($title === '') {
                continue;
            }

            MarqueeInnerDetails::create([
                'title'      => $title,
                'created_at' => $now,
                'created_by' => $userId,
            ]);
        }

        return redirect()->route('marquee-inner.index')->with('message', 'Marquee items added successfully!');
    }

    public function edit($id)
    {
        $marquee_inner = MarqueeInnerDetails::findOrFail($id);

        return view('backend.home-page.marquee-inner.edit', compact('marquee_inner'));
    }

    public function update(Request $request, $id)
    {
        $marquee_inner = MarqueeInnerDetails::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
        ], [
            'title.required' => 'The marquee item text is required.',
            'title.max'      => 'The marquee item must not exceed 255 characters.',
        ]);

        $marquee_inner->update([
            'title'       => trim($request->title),
            'modified_at' => Carbon::now(),
            'modified_by' => Auth::id(),
        ]);

        return redirect()->route('marquee-inner.index')->with('message', 'Marquee item has been successfully updated!');
    }

    public function destroy($id)
    {
        try {
            $marquee_inner = MarqueeInnerDetails::findOrFail($id);
            $marquee_inner->update([
                'deleted_at' => Carbon::now(),
                'deleted_by' => Auth::id(),
            ]);

            return redirect()->route('marquee-inner.index')->with('message', 'Marquee item deleted successfully!');
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Something Went Wrong - ' . $ex->getMessage());
        }
    }
}
