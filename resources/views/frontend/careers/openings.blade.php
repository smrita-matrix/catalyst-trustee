{{-- Current Openings, with the form for sending in a resume.
     Applying belongs with the roles, so the two sit on one page. --}}
<!DOCTYPE html>
<html lang="en">

<head>
  @include('components.frontend.head')
  <style>
    /* Inline error text for the client-side checks. */
    .field-error {
      display: block;
      margin-top: 6px;
      color: #d9534f;
      font-size: 13px;
    }
    .career-form .form-control.is-invalid {
      border-color: #d9534f;
    }
  </style>
</head>

<body>
  <div class="body-overlay"></div>
  <header>
    @include('components.frontend.header')
  </header>

  <div id="smooth-wrapper">
    <div id="smooth-content">

      @php $pageTitle = 'Current Openings'; @endphp
      @include('frontend.careers.partials._banner')

      @include('frontend.careers.partials._openings')
      @include('frontend.careers.partials._resume-form')

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')

  @include('frontend.careers.partials._resume-form-js')
</body>

</html>
