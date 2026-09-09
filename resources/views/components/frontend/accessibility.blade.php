{{-- ================= Accessibility tools =================

     The panel of reading aids - contrast, bigger text, readable font, and so
     on - in the corner of every page. It is a hosted service (EqualWeb), the
     same one the client's previous site used, so the panel behaves exactly as
     it did there.

     The site key comes from the site's own settings, so it can be changed, or
     the panel switched off altogether, without editing this file. Leave
     ACCESSIBILITY_KEY empty in .env and nothing is loaded.

     The button is set in the site's own colour rather than the blue used
     before, so it sits with the rest of the page.
--}}
@php $accessKey = trim((string) config('services.accessibility.key')); @endphp

@if ($accessKey !== '')
<script>
  window.interdeal = {
    sitekey: @json($accessKey),
    domains: {
      js:  'https://cdn.equalweb.com/',
      acc: 'https://access.equalweb.com/'
    },
    Position:  @json(config('services.accessibility.position', 'right')),
    Menulang:  'EN',
    draggable: true,
    btnStyle: {
      vPosition: ['50%', '80%'],
      margin:    ['0', '0'],
      scale:     ['0.5', '0.5'],
      color: {
        main:   @json(config('services.accessibility.colour', '#c9624c')),
        second: '#ffffff'
      },
      icon: {
        outline:      false,
        outlineColor: '#ffffff',
        type:         1,
        shape:        'circle'
      }
    }
  };

  (function (doc, head, body) {
    var call = doc.createElement('script');
    call.src = window.interdeal.domains.js + 'core/5.2.0/accessibility.js';
    call.defer = true;

    // Ties the file to a known copy, so a changed file on their end cannot
    // quietly run something else on the site.
    call.integrity = 'sha512-fHF4rKIzByr1XeM6stpnVdiHrJUOZsKN2/Pm0jikdTQ9uZddgq15F92kUptMnyYmjIVNKeMIa67HRFnBNTOXsQ==';
    call.crossOrigin = 'anonymous';
    call.setAttribute('data-cfasync', true);

    (body || head).appendChild(call);
  })(document, document.head, document.body);
</script>
@endif
