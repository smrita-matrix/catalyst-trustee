<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

    @php
        $imgBase = 'service-uploads/securitisation/';
        $bands = [
            'intro'    => ['1. Opening band',  'The picture and words that open the page.'],
            'business' => ['4. Middle band',   'Sits between the figures and the capabilities panel.'],
            'closing'  => ['7. Closing band',  'The last band before the footer.'],
        ];
    @endphp

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>{{ $product->name }} &mdash; Page (Securitisation layout)</h4></div>
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

          <div class="container-fluid">
            <div class="alert alert-light border d-flex align-items-start gap-2" role="alert">
                <i class="fa fa-info-circle mt-1"></i>
                <div>Every band below is optional. Leave one empty and it will not appear on the page,
                    which is how the Listed page shows seven bands and the Unlisted one four.</div>
            </div>

            <form class="needs-validation custom-input banner-form" novalidate method="POST" enctype="multipart/form-data"
                  action="{{ route('service-securitisation.update', $product->id) }}">
                @csrf
                @method('PUT')

                @include('backend.services._switcher')

                <!-- ===== BANNER ===== -->
                <div class="card">
                    <div class="card-header"><h4>Banner</h4>
                        <p class="f-m-light mt-1 mb-0">The strip at the top of the page.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_title">Title</label>
                            <input class="form-control" id="banner_title" type="text" name="banner_title" value="{{ old('banner_title', $page->banner_title ?? $product->name) }}" placeholder="{{ $product->name }}">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_breadcrumb_parent">Breadcrumb Parent</label>
                            <input class="form-control" id="banner_breadcrumb_parent" type="text" name="banner_breadcrumb_parent" value="{{ old('banner_breadcrumb_parent', $page->banner_breadcrumb_parent ?? 'Services') }}" placeholder="e.g. Services">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_breadcrumb_child">Breadcrumb Sub-parent</label>
                            <input class="form-control" id="banner_breadcrumb_child" type="text" name="banner_breadcrumb_child" value="{{ old('banner_breadcrumb_child', $page->banner_breadcrumb_child ?? optional($product->serviceCategory)->name) }}" placeholder="e.g. SEBI Regulated Services">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_background_image">Background Image</label>
                            <input class="form-control single-image-input" id="banner_background_image" type="file" name="banner_background_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->banner_background_image)<img src="{{ asset($imgBase.'banner/'.$page->banner_background_image) }}" alt="bg">@endif</div>
                        </div>
                    </div>
                </div>

                <!-- ===== THE THREE PICTURE-AND-WORDS BANDS ===== -->
                @foreach($bands as $band => $meta)
                <div class="card">
                    <div class="card-header"><h4>{{ $meta[0] }}</h4>
                        <p class="f-m-light mt-1 mb-0">{{ $meta[1] }}</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-8">
                            <label class="form-label" for="{{ $band }}_heading">Heading</label>
                            <input class="form-control" id="{{ $band }}_heading" type="text" name="{{ $band }}_heading" value="{{ old($band.'_heading', $page->{$band.'_heading'} ?? '') }}" placeholder="e.g. Listed Securitisation Business">
                            <label class="form-label mt-3" for="{{ $band }}_subheading">Sub-heading <span class="text-secondary">(optional)</span></label>
                            <input class="form-control" id="{{ $band }}_subheading" type="text" name="{{ $band }}_subheading" value="{{ old($band.'_subheading', $page->{$band.'_subheading'} ?? '') }}" placeholder="e.g. Listed PTC Transactions">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="{{ $band }}_image">Picture</label>
                            <input class="form-control single-image-input" id="{{ $band }}_image" type="file" name="{{ $band }}_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->{$band.'_image'})<img src="{{ asset($imgBase.$band.'/'.$page->{$band.'_image'}) }}" alt="">@endif</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="{{ $band }}_description">Paragraphs</label>
                            <textarea class="form-control rich-editor" id="{{ $band }}_description" name="{{ $band }}_description" rows="5">{{ old($band.'_description', $page->{$band.'_description'} ?? '') }}</textarea>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="{{ $band }}_image_side">Picture sits on the</label>
                            @php $side = old($band.'_image_side', $page->{$band.'_image_side'} ?? 'left'); @endphp
                            <select class="form-select" id="{{ $band }}_image_side" name="{{ $band }}_image_side">
                                <option value="left" {{ $side === 'right' ? '' : 'selected' }}>Left</option>
                                <option value="right" {{ $side === 'right' ? 'selected' : '' }}>Right</option>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="{{ $band }}_background">Background</label>
                            @php $bg = old($band.'_background', $page->{$band.'_background'} ?? 'tint'); @endphp
                            <select class="form-select" id="{{ $band }}_background" name="{{ $band }}_background">
                                <option value="tint" {{ $bg === 'white' ? '' : 'selected' }}>Tinted</option>
                                <option value="white" {{ $bg === 'white' ? 'selected' : '' }}>White</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- ===== THE FULL-WIDTH PANEL ===== -->
                <div class="card">
                    <div class="card-header"><h4>2. Full-width panel</h4>
                        <p class="f-m-light mt-1 mb-0">A picture on one side and headed paragraphs on the terracotta beside it.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-4">
                            <label class="form-label" for="panel_image">Picture</label>
                            <input class="form-control single-image-input" id="panel_image" type="file" name="panel_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->panel_image)<img src="{{ asset($imgBase.'panel/'.$page->panel_image) }}" alt="">@endif</div>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="panel_image_side">Picture sits on the</label>
                            @php $pside = old('panel_image_side', $page->panel_image_side ?? 'left'); @endphp
                            <select class="form-select" id="panel_image_side" name="panel_image_side">
                                <option value="left" {{ $pside === 'right' ? '' : 'selected' }}>Left</option>
                                <option value="right" {{ $pside === 'right' ? 'selected' : '' }}>Right</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <b>Headed paragraphs</b>
                                <button type="button" id="btn-add-block" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                            </div>
                            <div id="blocks-wrap">
                                @php $blocks = old('panel_block_heading') ? array_map(fn($h, $d) => ['heading' => $h, 'description' => $d], old('panel_block_heading'), old('panel_block_description', [])) : ($page->panel_blocks ?? [['heading' => '', 'description' => '']]); @endphp
                                @foreach($blocks as $block)
                                <div class="block-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2"><b class="block-index"></b>
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-block"><i class="fa fa-trash"></i> Remove</button></div>
                                    <label class="form-label">Heading</label>
                                    <input class="form-control" type="text" name="panel_block_heading[]" value="{{ $block['heading'] ?? '' }}" placeholder="e.g. A Team Built Around Specialised Expertise">
                                    <label class="form-label mt-2">Paragraphs</label>
                                    <textarea class="form-control rich-editor" name="panel_block_description[]" rows="4">{{ $block['description'] ?? '' }}</textarea>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="panel_points">List underneath <span class="text-secondary">(optional, use the bullet-list button)</span></label>
                            <textarea class="form-control rich-editor" id="panel_points" name="panel_points" rows="5">{{ old('panel_points', $page->panel_points ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ===== THE FIGURES ===== -->
                <div class="card">
                    <div class="card-header"><h4>3. Our Experience at a Glance</h4>
                        <p class="f-m-light mt-1 mb-0">A row of cards, each an icon with a figure and what it counts.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="glance_heading">Heading</label>
                            <input class="form-control" id="glance_heading" type="text" name="glance_heading" value="{{ old('glance_heading', $page->glance_heading ?? '') }}" placeholder="e.g. Our Experience at a Glance">
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <b>Cards</b>
                                <button type="button" id="btn-add-glance" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                            </div>
                            <div class="table-responsive custom-scrollbar">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light"><tr>
                                        <th style="width:55px;">#</th><th style="width:200px;">Icon</th>
                                        <th style="width:220px;">Figure</th><th>What it counts</th><th style="width:60px;"></th>
                                    </tr></thead>
                                    <tbody id="glance-wrap">
                                        @php $cards = old('glance_value') ? array_map(fn($v, $l, $i) => ['value'=>$v,'label'=>$l,'icon'=>$i], old('glance_value'), old('glance_label', []), old('glance_existing_icon', [])) : ($page->glance_cards ?? [['icon'=>null,'value'=>'','label'=>'']]); @endphp
                                        @foreach($cards as $card)
                                        <tr class="glance-item">
                                            <td class="glance-index"></td>
                                            <td>
                                                <input class="form-control mb-2 single-image-input" type="file" name="glance_icon[]" accept=".jpg,.jpeg,.png,.webp,.svg">
                                                <input type="hidden" name="glance_existing_icon[]" value="{{ $card['icon'] ?? '' }}">
                                                <div class="img-preview">@if(!empty($card['icon']))<img src="{{ asset($imgBase.'glance/'.$card['icon']) }}" alt="">@endif</div>
                                            </td>
                                            <td><input class="form-control" type="text" name="glance_value[]" value="{{ $card['value'] ?? '' }}" placeholder="e.g. 5,500+"></td>
                                            <td><textarea class="form-control" name="glance_label[]" rows="2" placeholder="e.g. Direct Assignment & PTC Transactions Handled">{{ $card['label'] ?? '' }}</textarea></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-glance"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== THE CAPABILITIES PANEL ===== -->
                <div class="card">
                    <div class="card-header"><h4>5. Capabilities panel</h4>
                        <p class="f-m-light mt-1 mb-0">The tabs down the left, and what each one shows.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="capabilities_heading">Heading</label>
                            <input class="form-control" id="capabilities_heading" type="text" name="capabilities_heading" value="{{ old('capabilities_heading', $page->capabilities_heading ?? '') }}" placeholder="e.g. Our Listed PTC Capabilities">
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <b>Tabs</b>
                                <button type="button" id="btn-add-tab" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                            </div>
                            <div id="tabs-wrap">
                                @php $tabs = $page->capability_tabs ?? [['icon'=>null,'title'=>'','description'=>'','points'=>'','flow'=>'']]; @endphp
                                @foreach($tabs as $tab)
                                <div class="tab-item border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2"><b class="tab-index"></b>
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove-tab"><i class="fa fa-trash"></i> Remove</button></div>
                                    <div class="row g-3">
                                        <div class="col-lg-8">
                                            <label class="form-label">Tab title</label>
                                            <input class="form-control" type="text" name="tab_title[]" value="{{ $tab['title'] ?? '' }}" placeholder="e.g. Trust &amp; Transaction Setup">
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">Icon <span class="text-secondary">(optional)</span></label>
                                            <input class="form-control single-image-input" type="file" name="tab_icon[]" accept=".jpg,.jpeg,.png,.webp,.svg">
                                            <input type="hidden" name="tab_existing_icon[]" value="{{ $tab['icon'] ?? '' }}">
                                            <div class="img-preview mt-2">@if(!empty($tab['icon']))<img src="{{ asset($imgBase.'capabilities/'.$tab['icon']) }}" alt="">@endif</div>
                                        </div>
                                    </div>
                                    <label class="form-label mt-2">Opening line <span class="text-secondary">(optional)</span></label>
                                    <input class="form-control" type="text" name="tab_description[]" value="{{ $tab['description'] ?? '' }}" placeholder="e.g. We manage the complete PTC issuance lifecycle, including:">
                                    <label class="form-label mt-2">Steps in a row <span class="text-secondary">(optional, one per line)</span></label>
                                    <textarea class="form-control" name="tab_flow[]" rows="3" placeholder="Investor Confirmation&#10;Inward Funding&#10;Allotment">{{ $tab['flow'] ?? '' }}</textarea>
                                    <label class="form-label mt-2">Line after the steps <span class="text-secondary">(optional)</span></label>
                                    <input class="form-control" type="text" name="tab_note[]" value="{{ $tab['note'] ?? '' }}" placeholder="e.g. This includes:">
                                    <label class="form-label mt-2">Points <span class="text-secondary">(use the bullet-list button)</span></label>
                                    <textarea class="form-control rich-editor" name="tab_points[]" rows="4">{{ $tab['points'] ?? '' }}</textarea>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== THE LIFECYCLE STRIP ===== -->
                <div class="card">
                    <div class="card-header"><h4>6. Lifecycle strip</h4>
                        <p class="f-m-light mt-1 mb-0">The sliding row of steps. Each icon is a Font Awesome name, such as <code>fa-cogs</code>.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="lifecycle_heading">Heading</label>
                            <input class="form-control" id="lifecycle_heading" type="text" name="lifecycle_heading" value="{{ old('lifecycle_heading', $page->lifecycle_heading ?? '') }}" placeholder="e.g. Listed PTC Lifecycle">
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <b>Steps</b>
                                <button type="button" id="btn-add-step" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                            </div>
                            <div class="table-responsive custom-scrollbar">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light"><tr>
                                        <th style="width:55px;">#</th><th style="width:240px;">Icon name</th><th>Step</th><th style="width:60px;"></th>
                                    </tr></thead>
                                    <tbody id="steps-wrap">
                                        @php $steps = $page->lifecycle_steps ?? [['icon'=>'','title'=>'']]; @endphp
                                        @foreach($steps as $step)
                                        <tr class="step-item">
                                            <td class="step-index"></td>
                                            <td><input class="form-control" type="text" name="lifecycle_icon[]" value="{{ $step['icon'] ?? '' }}" placeholder="fa-cogs"></td>
                                            <td><input class="form-control" type="text" name="lifecycle_title[]" value="{{ $step['title'] ?? '' }}" placeholder="e.g. Transaction Setup"></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-step"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pb-5">
                    <a href="{{ route('product-category.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button class="btn btn-primary px-4" type="submit">Save</button>
                </div>
            </form>
          </div>
        </div>

        @include('components.backend.footer')
        </div>
        </div>

       @include('components.backend.main-js')
       @include('backend.services.securitisation._manage-js')
</body>

</html>
