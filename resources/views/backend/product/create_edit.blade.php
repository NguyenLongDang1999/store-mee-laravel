@extends('layouts.backend.index')

@section('title', isset($row) ? __('trans.product.update') . ': ' . $row->name : __('trans.product.create'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

    <script>
        var url_change_data = "{{ route('admin.brand.getBrandWithCategory') }}"

        document.addEventListener('DOMContentLoaded', function () {
            (function () {
                const productForm = document.getElementById('product-form'),
                    meteCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    nameLabel = document.querySelector('label[for=name]')?.textContent,
                    skuLabel = document.querySelector('label[for=sku]')?.textContent,
                    categoryLabel = document.querySelector('label[for=category_id]')?.textContent,
                    descriptionLabel = document.querySelector('label[for=description]')?.textContent,
                    metaTitleLabel = document.querySelector('label[for=meta_title]')?.textContent,
                    metaKeywordLabel = document.querySelector('label[for=meta_keyword]')?.textContent,
                    metaDescriptionLabel = document.querySelector('label[for=meta_description]')?.textContent,
                    requiredValidate = ' không được bỏ trống.',
                    maxValidate = ' không được vượt quá 160 ký tự.'

                FormValidation.formValidation(productForm, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: nameLabel + requiredValidate
                                },
                                stringLength: {
                                    max: 100,
                                    message: nameLabel + ' phải có độ dài tối đa 100 ký tự.'
                                },
                                remote: {
                                    headers: {
                                        "X-CSRF-TOKEN": meteCSRF,
                                    },
                                    message: nameLabel + ' đã tồn tại. Vui lòng kiểm tra lại!',
                                    method: 'POST',
                                    data: function () {
                                        return {
                                            name: productForm.querySelector('[name="name"]').value,
                                            id: productForm.querySelector('[name="id"]').value
                                        };
                                    },
                                    url: "{{ route('admin.product.checkExistData') }}"
                                },
                            }
                        },
                        sku: {
                            validators: {
                                notEmpty: {
                                    message: skuLabel + requiredValidate
                                },
                            }
                        },
                        category_id: {
                            validators: {
                                notEmpty: {
                                    message: categoryLabel + requiredValidate
                                },
                            }
                        },
                        description: {
                            validators: {
                                stringLength: {
                                    max: 160,
                                    message: descriptionLabel + maxValidate
                                }
                            }
                        },
                        meta_title: {
                            validators: {
                                stringLength: {
                                    max: 60,
                                    message: metaTitleLabel + ' không được vượt quá 60 ký tự.'
                                }
                            }
                        },
                        meta_keyword: {
                            validators: {
                                stringLength: {
                                    max: 60,
                                    message: metaKeywordLabel + ' không được vượt quá 60 ký tự.'
                                }
                            }
                        },
                        meta_description: {
                            validators: {
                                stringLength: {
                                    max: 160,
                                    message: metaDescriptionLabel + maxValidate
                                }
                            }
                        }
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            eleValidClass: ''
                        }),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                        autoFocus: new FormValidation.plugins.AutoFocus()
                    }
                });
            })();
        });
    </script>
@endsection

@section('content')
    <h4 class="fw-semibold mb-4 text-uppercase">{{ isset($row) ? __('trans.brand.update') . ': ' . $row->name : __('trans.brand.create')  }}</h4>

    {{ html()->form('POST', $router)->id('product-form')->acceptsFiles()->open() }}
    {{ html()->hidden('id', $row?->id ?? '') }}

    <div class="row g-4">
        <div class="col-12">
            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary text-capitalize">
                <span class="ti-xs ti ti-arrow-bar-to-left me-1"></span>
                {{ __('trans.btn.back') }}
            </a>
        </div>

        <div class="col-12">
            <div class="card">
                <h5 class="card-header mb-0 text-uppercase">{{ __('trans.info') }}</h5>

                <hr class="my-0">

                <div class="card-body row g-3">
                    <div class="col-md-6">
                        {{ html()->label(__('trans.product.title'), 'name')->class('text-capitalize') }}
                        {{ html()->text('name')->value($row?->name ?? '')->class('form-control')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.product.sku'), 'sku')->class('text-capitalize') }}
                        {{ html()->text('sku')->value($row?->sku ?? '')->class('form-control')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.category.name'), 'category_id')->class('text-capitalize') }}
                        {{ html()->select('category_id', $getCategoryList)->value($row?->category_id ?? '')->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')->attributes(['data-brand' => $row->brand_id ?? ''])  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.brand.name'), 'brand_id')->class('text-capitalize') }}
                        {{ html()->select('brand_id', ['' => __('trans.empty')])->value($row?->brand_id ?? '')->class('selectpicker text-capitalize w-100 brand-data')->attribute('data-style', 'btn-default text-capitalize')  }}
                    </div>

                    <div class="col-12">
                        {{ html()->label(__('trans.description'), 'description')->class('text-capitalize') }}
                        {{ html()->textarea('description')->value($row?->description ?? '')->class('form-control')  }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <h5 class="card-header mb-0 text-uppercase">{{ __('trans.setting_price') }}</h5>

                <hr class="my-0">

                <div class="card-body row g-3">
                    <div class="col-md-6">
                        {{ html()->label(__('trans.product.type_discount'), 'type_discount')->class('text-capitalize') }}
                        {{ html()->select('type_discount', optionPrice())->value($row?->type_discount ?? '')->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.product.quantity'), 'quantity')->class('text-capitalize') }}
                        {{ html()->text('quantity')->value($row?->quantity ?? 0)->class('form-control')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.product.price'), 'price')->class('text-capitalize') }}
                        {{ html()->text('price')->value($row?->price ?? 0)->class('form-control numeral-mask')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.product.price_discount'), 'price_discount')->class('text-capitalize') }}
                        {{ html()->text('price_discount')->value($row?->price_discount ?? 0)->class('form-control numeral-mask')  }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <h5 class="card-header mb-0 text-uppercase">{{ __('trans.image.name') }}</h5>

                <hr class="my-0">

                <div class="card-body row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            {{ html()->img(getFile($row?->image_uri ?? NULL), 'Image')->class('d-block rounded')->id('uploaded-image')->attributes(['width' => 150, 'height' => 150]) }}

                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Chọn Hình</span>
                                    <i class="ti ti-upload d-block d-sm-none"></i>

                                    {{ html()->file('image_uri')->acceptImage()->class('image-file-input')->id('upload')->attribute('hidden', 'hidden') }}
                                </label>

                                <button type="button" class="btn btn-label-secondary image-file-reset mb-4">
                                    <i class="ti ti-refresh d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Làm Mới</span>
                                </button>

                                <p class="mb-0">Chấp nhận ảnh JPG, GIF or PNG.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <h5 class="card-header mb-0 text-uppercase">{{ __('trans.meta.seo') }}</h5>

                <hr class="my-0">

                <div class="card-body row g-3">
                    <div class="col-12">
                        {{ html()->label(__('trans.meta.title'), 'meta_title')->class('text-capitalize') }}
                        {{ html()->textarea('meta_title')->value($row?->meta_title ?? '')->class('form-control')  }}
                    </div>

                    <div class="col-12">
                        {{ html()->label(__('trans.meta.keyword'), 'meta_keyword')->class('text-capitalize') }}
                        {{ html()->textarea('meta_keyword')->value($row?->meta_keyword ?? '')->class('form-control')  }}
                    </div>

                    <div class="col-12">
                        {{ html()->label(__('trans.meta.description'), 'meta_description')->class('text-capitalize') }}
                        {{ html()->textarea('meta_description')->value($row?->meta_description ?? '')->class('form-control')  }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            {{ html()->submit(__('trans.btn.save'))->class('btn btn-primary text-capitalize me-1') }}
            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary text-capitalize">
                {{ __('trans.btn.back') }}
            </a>
        </div>
    </div>
    {{ html()->form()->close()  }}
@endsection
