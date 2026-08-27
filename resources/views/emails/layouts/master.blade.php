{{--
  Shared shell for every email the website sends.

  Table-based and inline-styled on purpose — Outlook and most webmail clients
  strip <style> blocks and ignore flex/grid.

  The logo is embedded in the message itself (a cid: attachment) rather than
  linked. A linked image only loads if the site is publicly reachable, so it
  would break on localhost and on any staging server behind a login.

  Sections a child template fills in:
    @section('title')    browser/preview title
    @section('heading')  white text in the coloured header bar
    @section('subtitle') optional small line under the heading
    @section('content')  the body
    @section('note')     optional grey line in the footer strip
--}}
@php
    // PNG, not the site's WebP logo — several email clients cannot render WebP.
    $logoFile = public_path('email/catalyst-logo.png');
    $logoSrc  = null;

    if (is_file($logoFile)) {
        // $message exists when this is a real mailable; fall back to a URL if
        // the layout is ever rendered outside one.
        $logoSrc = isset($message) ? $message->embed($logoFile) : asset('email/catalyst-logo.png');
    }
    $siteUrl  = rtrim(config('app.url'), '/');
    $siteName = 'Catalyst Trusteeship Limited';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title', $siteName)</title>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f2f1ed; font-family:Arial, Helvetica, sans-serif; color:#444343; -webkit-font-smoothing:antialiased;">

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f2f1ed; padding:28px 12px;">
    <tr>
      <td align="center">

        <table role="presentation" width="640" cellpadding="0" cellspacing="0" border="0"
               style="max-width:640px; width:100%; background-color:#ffffff; border-radius:10px; overflow:hidden; border:1px solid #e8ded9;">

          {{-- ---------- Header: website logo ---------- --}}
          <tr>
            <td align="center" style="padding:26px 28px 20px; background-color:#ffffff; border-bottom:1px solid #efe7e3;">
              @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="{{ $siteName }}" width="150"
                     style="display:block; width:150px; max-width:150px; height:auto; border:0; outline:none; text-decoration:none;">
              @else
                <span style="font-size:20px; font-weight:bold; color:#c9624c;">{{ $siteName }}</span>
              @endif
            </td>
          </tr>

          {{-- ---------- Coloured title bar ---------- --}}
          <tr>
            <td style="background-color:#c9624c; border-top:3px solid #a94a37; padding:20px 28px;">
              <h1 style="margin:0; font-size:19px; line-height:1.35; font-weight:bold; color:#ffffff;">@yield('heading')</h1>
              @hasSection('subtitle')
                <p style="margin:6px 0 0; font-size:13px; line-height:1.5; color:#ffdfd9;">@yield('subtitle')</p>
              @endif
            </td>
          </tr>

          {{-- ---------- Body ---------- --}}
          <tr>
            <td style="padding:26px 28px; font-size:14px; line-height:1.7; color:#444343;">
              @yield('content')
            </td>
          </tr>

          {{-- ---------- Footer: year + company website ---------- --}}
          <tr>
            <td align="center" style="background-color:#fff3f0; padding:20px 28px; border-top:1px solid #efe7e3;">
              @hasSection('note')
                <p style="margin:0 0 10px; font-size:12px; line-height:1.6; color:#8d8683;">@yield('note')</p>
              @endif
              <p style="margin:0; font-size:13px; line-height:1.6; color:#5c5654;">
                &copy; {{ date('Y') }} <strong style="color:#c9624c;">Catalyst</strong> &mdash; {{ $siteName }}
              </p>
              <p style="margin:6px 0 0; font-size:12px; line-height:1.6;">
                <a href="{{ $siteUrl }}" style="color:#c9624c; text-decoration:none;">{{ preg_replace('#^https?://#', '', $siteUrl) }}</a>
              </p>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
