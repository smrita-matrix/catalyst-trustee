{{-- Shared "Attention Investors!" callout. Both texts are editable per page in the admin. --}}
@if($category->alert_heading || $category->alert_text)
<div class="row">
  <div class="col-sm-12">
    <div class="bomsc-alert">
      <div class="bomsc-alert-icon"><i class="fa fa-bullhorn"></i></div>
      <div class="bomsc-alert-body">
        @if($category->alert_heading)<p class="bomsc-alert-heading">{{ $category->alert_heading }}</p>@endif
        @if($category->alert_text)<p class="bomsc-alert-text">{!! $category->alert_text !!}</p>@endif
      </div>
    </div>
  </div>
</div>
@endif
