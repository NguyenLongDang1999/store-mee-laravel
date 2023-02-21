<?php

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
