<?php

function adminMenu(): array
{
    return [
        'manage-dashboard' => [
            'title' => '',
            'content' => [
                [
                    'key' => config('constant.route.dashboard'),
                    'title' => __('trans.dashboard.name'),
                    'icon' => 'ti ti-home',
                    'href' => route('admin.dashboard.index'),
                ],
            ],
        ],
        'manage-product' => [
            'title' => __('trans.product.manager'),
            'content' => [
                [
                    'key' => config('constant.route.slider'),
                    'title' => __('trans.slider.name'),
                    'icon' => 'ti ti-speakerphone',
                    'href' => route('admin.slider.index'),
                ],
                [
                    'key' => config('constant.route.category'),
                    'title' => __('trans.category.name'),
                    'icon' => 'ti ti-category',
                    'href' => route('admin.category.index'),
                ],
                [
                    'key' => config('constant.route.brand'),
                    'title' => __('trans.brand.name'),
                    'icon' => 'ti ti-trademark',
                    'href' => route('admin.brand.index'),
                ],
                [
                    'key' => config('constant.route.attribute'),
                    'title' => __('trans.attribute.name'),
                    'icon' => 'ti ti-color-picker',
                    'href' => route('admin.attribute.index'),
                ],
                [
                    'key' => config('constant.route.variation'),
                    'title' => __('trans.variation.name'),
                    'icon' => 'ti ti-color-picker',
                    'href' => route('admin.variation.index'),
                ],
            ],
        ],
    ];
}

function activeMenu(string $key): string
{
    if (request()->routeIs('admin.'.$key.'.*')) {
        return 'active';
    }

    return '';
}

function optionStatus(): array
{
    return [
        '' => __('trans.empty'),
        config('constant.status.active') => __('trans.status.active'),
        config('constant.status.inactive') => __('trans.status.inactive'),
    ];
}

function optionPopular(): array
{
    return [
        '' => __('trans.empty'),
        config('constant.popular.active') => __('trans.popular.active'),
        config('constant.popular.inactive') => __('trans.popular.inactive'),
    ];
}

function uploadFile($path, $request): bool|string
{
    return Storage::disk('bunnycdn')->put($path, $request);
}

function getFile($path): ?string
{
    if (! is_null($path)) {
        return Storage::disk('bunnycdn')->url($path);
    }

    return asset('assets/img/default.webp');
}
