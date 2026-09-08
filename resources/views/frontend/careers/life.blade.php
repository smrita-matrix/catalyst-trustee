{{-- Life at Catalyst: the introduction and the stories.
     Its own page, so it can be linked to and shared on its own. --}}
<!DOCTYPE html>
<html lang="en">

<head>
  @include('components.frontend.head')
</head>

<body>
  <div class="body-overlay"></div>
  <header>
    @include('components.frontend.header')
  </header>

  <div id="smooth-wrapper">
    <div id="smooth-content">

      {{-- Named for what the page holds. The dashboard's banner title covers
           both Careers pages, so it would read "Careers" on each of them. --}}
      @php $pageTitle = 'Life at Catalyst'; @endphp
      @include('frontend.careers.partials._banner')

      @include('frontend.careers.partials._intro')
      @include('frontend.careers.partials._stories')

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')
</body>

</html>
