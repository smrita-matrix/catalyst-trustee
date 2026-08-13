<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactPageDetails;
use App\Models\ContactOffice;
use App\Models\FooterDetails;

class ContactController extends Controller
{
    public function contact()
    {
        $content = ContactPageDetails::whereNull('deleted_at')->latest('id')->first();

        $offices = ContactOffice::whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        $mainOffices   = $offices->where('type', 'main')->values();
        $branchOffices = $offices->where('type', 'branch')->values();

        $footer = FooterDetails::whereNull('deleted_at')->latest('id')->first();

        return view('frontend.contact.index', compact('content', 'mainOffices', 'branchOffices', 'footer'));
    }
}
