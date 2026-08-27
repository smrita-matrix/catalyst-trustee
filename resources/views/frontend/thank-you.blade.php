<!DOCTYPE html>
<html lang="en">

<head>
  @include('components.frontend.head')
  <style>
    /* Confirmation page — sits between the shared header and footer. */
    .thankyou-sec {
      padding: 90px 0 110px;
      text-align: center;
    }

    .thankyou-icon {
      width: 110px;
      height: 110px;
      margin: 0 auto 30px;
      border-radius: 50%;
      background: rgba(212, 118, 82, 0.10);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .thankyou-icon i {
      font-size: 50px;
      color: #d47652;
      line-height: 1;
    }

    .thankyou-sec h2 {
      margin: 0 0 18px;
    }

    .thankyou-sec p {
      max-width: 640px;
      margin: 0 auto 34px;
    }

    .thankyou-actions .btn-default {
      margin: 0 6px 10px;
    }

    @media (max-width: 767px) {
      .thankyou-sec { padding: 60px 0 70px; }
      .thankyou-icon { width: 88px; height: 88px; }
      .thankyou-icon i { font-size: 40px; }
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

      <section class="breadcrumb-bg-sec">
        <div class="breadcrumb-header-bg"></div>
        <div class="container">
          <div class="breadcrumb-header-inner">
            <h1>{{ $heading }}</h1>
            <div class="thm-breadcrumb__inner">
              <ul class="thm-breadcrumb list-unstyled">
                <li><a href="{{ route('frontend.index') }}">Home</a></li>
                <li><i class="fa fa-angle-right"></i></li>
                <li>{{ $heading }}</li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="thankyou-sec">
        <div class="container">
          <div class="row">
            <div class="col-md-12">

              <div class="thankyou-icon" data-aos="fade-up" data-aos-duration="1000">
                <i class="fa fa-check" aria-hidden="true"></i>
              </div>

              <div class="heading heading-center" data-aos="fade-up" data-aos-duration="1000">
                <h2>{{ $heading }}</h2>
              </div>

              <p data-aos="fade-up" data-aos-duration="1000">{{ $message }}</p>

              <div class="thankyou-actions" data-aos="fade-up" data-aos-duration="1000">
                <a class="btn-default" href="{{ route('frontend.index') }}">Back to Home</a>
                <a class="btn-default btn-black" href="{{ route('frontend.contact') }}">Contact Us</a>
              </div>

            </div>
          </div>
        </div>
      </section>

      @include('components.frontend.footer')
    </div>
  </div>
  @include('components.frontend.main-js')
</body>

</html>
