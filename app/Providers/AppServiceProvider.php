<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ServiceCategory;
use App\Models\ProductCategory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Feed the dynamic "Services" menu to the frontend header.
        View::composer('components.frontend.header', function ($view) {
            $serviceMenu = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('service_categories')) {
                $serviceMenu = ServiceCategory::whereNull('deleted_at')
                    ->where('status', 1)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('id', 'asc')
                    ->get()
                    ->map(function ($cat) {
                        $items = ProductCategory::where('service_category_id', $cat->id)
                            ->whereNull('deleted_at')
                            ->where('status', 1)
                            ->orderBy('sort_order', 'asc')->orderBy('id', 'asc')
                            ->get()
                            ->map(function ($p) {
                                if (in_array($p->layout, ['debenture', 'services2', 'services3', 'fif'], true) && $p->slug) {
                                    $link = route('frontend.product_page', $p->slug);
                                } else {
                                    $link = '#';
                                }
                                return ['title' => $p->name, 'link' => $link];
                            })
                            ->all();

                        return [
                            'name'  => $cat->name,
                            'icon'  => $cat->icon,
                            'items' => $items,
                        ];
                    })
                    ->values();
            }

            $view->with('serviceMenu', $serviceMenu);
        });
    }
}
