<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

    @php $imgBase = 'services/layout3/'; @endphp

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>{{ $product->name }} — Page (Non-SEBI layout)</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Services</li>
                    <li class="breadcrumb-item">{{ optional($product->serviceCategory)->name }}</li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <form class="needs-validation custom-input banner-form" novalidate method="POST" enctype="multipart/form-data"
                  action="{{ route('service-layout3.update', $product->id) }}">
                @csrf
                @method('PUT')

                @include('backend.services._switcher')

                <!-- ===== SECTION 1 : BANNER ===== -->
                <div class="card">
                    <div class="card-header"><h4>1. Banner</h4>
                        <p class="f-m-light mt-1 mb-0">Breadcrumb labels + background image. Title uses the product name (<b>{{ $product->name }}</b>).</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-4">
                            <label class="form-label" for="banner_breadcrumb_parent">Breadcrumb Parent</label>
                            <input class="form-control" id="banner_breadcrumb_parent" type="text" name="banner_breadcrumb_parent" value="{{ old('banner_breadcrumb_parent', $page->banner_breadcrumb_parent ?? 'Services') }}" placeholder="e.g. Services">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="banner_breadcrumb_child">Breadcrumb Sub-parent</label>
                            <input class="form-control" id="banner_breadcrumb_child" type="text" name="banner_breadcrumb_child" value="{{ old('banner_breadcrumb_child', $page->banner_breadcrumb_child ?? optional($product->serviceCategory)->name) }}" placeholder="e.g. Non-SEBI Regulated Services">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="banner_background_image">Background Image</label>
                            <input class="form-control single-image-input" id="banner_background_image" type="file" name="banner_background_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->banner_background_image)<img src="{{ asset($imgBase.'banner/'.$page->banner_background_image) }}" alt="bg">@endif</div>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 2 : INTRO ===== -->
                <div class="card">
                    <div class="card-header"><h4>2. Intro</h4>
                        <p class="f-m-light mt-1 mb-0">Left image, heading and description.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="intro_image">Image</label>
                            <input class="form-control single-image-input" id="intro_image" type="file" name="intro_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->intro_image)<img src="{{ asset($imgBase.'intro/'.$page->intro_image) }}" alt="intro">@endif</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="intro_heading">Heading</label>
                            <input class="form-control" id="intro_heading" type="text" name="intro_heading" value="{{ old('intro_heading', $page->intro_heading ?? '') }}" placeholder="e.g. Alternative Investment Funds (AIF)">
                            <label class="form-label mt-3" for="intro_description">Description</label>
                            <textarea class="form-control rich-editor" id="intro_description" name="intro_description" rows="4" placeholder="Intro paragraph">{{ old('intro_description', $page->intro_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 3 : SERVICES TABS ===== -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div><h4>3. Services (Tabs)</h4>
                            <p class="f-m-light mt-1 mb-0">Left nav tabs (e.g. Fund Registration, Documentation…). Each: icon, title, optional intro text, and points.</p></div>
                        <button type="button" id="btn-add-tab" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="services_divider_label">Divider Label</label>
                            <input class="form-control" id="services_divider_label" type="text" name="services_divider_label" value="{{ old('services_divider_label', $page->services_divider_label ?? '') }}" placeholder="e.g. We cater the below-mentioned services for IFSC & Domestic AIFs:">
                        </div>
                        <div id="tabs-wrap">
                            @php $tabs = old('tab_title') ? collect(old('tab_title'))->map(fn($t,$i)=>['title'=>$t,'description'=>old('tab_description')[$i]??'','points'=>old('tab_points')[$i]??'','icon'=>old('tab_existing_icon')[$i]??null])->all() : ($page->services_tabs ?? [['title'=>'','description'=>'','points'=>'','icon'=>null]]); @endphp
                            @foreach($tabs as $i => $t)
                            <div class="tab-item border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <b class="tab-index">Tab {{ $i + 1 }}</b>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-tab"><i class="fa fa-trash"></i> Remove</button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-lg-3">
                                        <label class="form-label">Icon</label>
                                        <input class="form-control repeater-image-input" type="file" name="tab_icon[]" accept=".png,.jpg,.jpeg,.webp,.svg">
                                        <input type="hidden" name="tab_existing_icon[]" value="{{ $t['icon'] ?? '' }}">
                                        <div class="img-preview repeater-preview mt-2">@if(!empty($t['icon']))<img src="{{ asset($imgBase.'services/'.$t['icon']) }}" alt="icon">@endif</div>
                                    </div>
                                    <div class="col-lg-9">
                                        <label class="form-label">Tab Title</label>
                                        <input class="form-control" type="text" name="tab_title[]" value="{{ $t['title'] ?? '' }}" placeholder="e.g. Fund Registration">
                                        <label class="form-label mt-2">Intro Text <span class="text-secondary">(optional)</span></label>
                                        <textarea class="form-control" name="tab_description[]" rows="2" placeholder="Optional paragraph above the points">{{ $t['description'] ?? '' }}</textarea>
                                        <label class="form-label mt-2">Points <span class="text-secondary">(use the bullet-list button)</span></label>
                                        <textarea class="form-control rich-editor" name="tab_points[]" rows="4" placeholder="Add bullet points">{{ $t['points'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 4 : KEY BENEFITS ===== -->
                <div class="card">
                    <div class="card-header"><h4>4. Key Benefits</h4>
                        <p class="f-m-light mt-1 mb-0">Image, heading, points and the note strip.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="benefits_image">Image</label>
                            <input class="form-control single-image-input" id="benefits_image" type="file" name="benefits_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->benefits_image)<img src="{{ asset($imgBase.'benefits/'.$page->benefits_image) }}" alt="benefits">@endif</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="benefits_heading">Heading</label>
                            <input class="form-control" id="benefits_heading" type="text" name="benefits_heading" value="{{ old('benefits_heading', $page->benefits_heading ?? '') }}" placeholder="e.g. Key benefits for AIFs in GIFT City">
                            <label class="form-label mt-3" for="benefits_points">Points <span class="text-secondary">(use the bullet-list button)</span></label>
                            <textarea class="form-control rich-editor" id="benefits_points" name="benefits_points" rows="5" placeholder="Add bullet points">{{ old('benefits_points', $page->benefits_points ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="benefits_note">Note Strip</label>
                            <textarea class="form-control rich-editor" id="benefits_note" name="benefits_note" rows="2" placeholder="e.g. Catalyst Trusteeship Setup of AIF facility unit at IFSC, GIFT City...">{{ old('benefits_note', $page->benefits_note ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body d-flex justify-content-end">
                        <button class="btn btn-primary px-5" type="submit">{{ $page ? 'Update Page' : 'Save Page' }}</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
        <!-- footer start-->
        @include('components.backend.footer')
        </div>
        </div>

       @include('components.backend.main-js')

@include('backend.services.layout3._manage-js')
</body>

</html>
