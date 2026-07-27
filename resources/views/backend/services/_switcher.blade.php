@php
    $swCategories = \App\Models\ServiceCategory::whereNull('deleted_at')
        ->orderBy('sort_order','asc')->orderBy('id','asc')->get();
    $swProducts = \App\Models\ProductCategory::whereNull('deleted_at')
        ->orderBy('sort_order','asc')->orderBy('id','asc')->get();
@endphp
<div class="card">
    <div class="card-header"><h4>Service &amp; Product</h4>
        <p class="f-m-light mt-1 mb-0">Switch to any service to edit its page.</p></div>
    <div class="card-body row g-4">
        <div class="col-lg-6">
            <label class="form-label" for="switch_category">Service Category</label>
            <select class="form-select" id="switch_category">
                @foreach($swCategories as $c)
                    <option value="{{ $c->id }}" {{ optional($product->serviceCategory)->id === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <small class="d-block text-secondary mt-1"><a href="{{ route('service-category.index') }}" target="_blank">Manage categories</a></small>
        </div>
        <div class="col-lg-6">
            <label class="form-label" for="switch_product">Product</label>
            <select class="form-select" id="switch_product">
                @foreach($swProducts as $p)
                    <option value="{{ $p->id }}" data-service="{{ $p->service_category_id }}" data-url="{{ route('product-page.open', $p->id) }}" {{ $product->id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            <small class="d-block text-secondary mt-1">Products of the selected category. <a href="{{ route('product-category.index') }}" target="_blank">Manage products</a></small>
        </div>
    </div>
</div>
<script>
    (function () {
        var catSel  = document.getElementById('switch_category');
        var prodSel = document.getElementById('switch_product');
        if (!catSel || !prodSel) return;

        // snapshot every product option (value/label/category/url) up front
        var all = Array.prototype.slice.call(prodSel.querySelectorAll('option')).map(function (o) {
            return {
                value:   o.value,
                text:    o.textContent,
                service: o.getAttribute('data-service'),
                url:     o.getAttribute('data-url')
            };
        });
        var currentValue = prodSel.value; // the product this page belongs to

        function rebuild(selectValue) {
            var catId = catSel.value;
            prodSel.innerHTML = '';
            all.filter(function (o) { return o.service === catId; }).forEach(function (o) {
                var opt = document.createElement('option');
                opt.value = o.value;
                opt.textContent = o.text;
                opt.setAttribute('data-url', o.url);
                if (o.value === selectValue) opt.selected = true;
                prodSel.appendChild(opt);
            });
        }

        rebuild(currentValue);                                   // keep the current product selected on load
        catSel.addEventListener('change', function () { rebuild(''); });
        prodSel.addEventListener('change', function () {
            var opt = prodSel.options[prodSel.selectedIndex];
            var url = opt && opt.getAttribute('data-url');
            if (url) window.location.href = url;
        });
    })();
</script>
