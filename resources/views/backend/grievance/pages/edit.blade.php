<!doctype html>
<html lang="en">

<head>
  @include('components.backend.head')
</head>

@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-6"><h4>Grievance &mdash; Edit Page</h4></div>
        <div class="col-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('grievance-pages.index') }}">Pages</a></li>
            <li class="breadcrumb-item active">Edit Page</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <form class="needs-validation custom-input banner-form" novalidate method="POST" enctype="multipart/form-data"
          action="{{ route('grievance-pages.update', $page->id) }}">
      @include('backend.grievance.pages._form')
    </form>
  </div>
</div>

@include('components.backend.footer')
@include('components.backend.main-js')
@include('backend.grievance.pages._manage-js')
</html>
