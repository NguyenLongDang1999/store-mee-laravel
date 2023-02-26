@extends('layouts.backend.index')

@section('title', isset($row) ? __('trans.brand.update') . ': ' . $row->name : __('trans.brand.create'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            (function () {
                const brandForm = document.getElementById('brand-form'),
                    meteCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    nameLabel = document.querySelector('label[for=name]')?.textContent,
                    descriptionLabel = document.querySelector('label[for=description]')?.textContent,
                    requiredValidate = ' không được bỏ trống.',
                    maxValidate = ' không được vượt quá 160 ký tự.'

                FormValidation.formValidation(brandForm, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: nameLabel + requiredValidate
                                },
                                stringLength: {
                                    max: 50,
                                    message: nameLabel + ' phải có độ dài tối đa 50 ký tự.'
                                },
                                remote: {
                                    headers: {
                                        "X-CSRF-TOKEN": meteCSRF,
                                    },
                                    message: nameLabel + ' đã tồn tại. Vui lòng kiểm tra lại!',
                                    method: 'POST',
                                    data: function () {
                                        return {
                                            name: brandForm.querySelector('[name="name"]').value,
                                            id: brandForm.querySelector('[name="id"]').value
                                        };
                                    },
                                    url: "{{ route('admin.brand.checkExistData') }}"
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

    {{ html()->form('POST', $router)->id('brand-form')->acceptsFiles()->open() }}
    {{ html()->hidden('id', $row?->id ?? '') }}
    
    <div class="row g-4">
        <div class="col-12">
            <a href="{{ route('admin.brand.index') }}" class="btn btn-secondary text-capitalize">
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
                        {{ html()->label(__('trans.brand.title'), 'name')->class('text-capitalize') }}
                        {{ html()->text('name')->value($row?->name ?? '')->class('form-control')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.category.name'), 'category_id')->class('text-capitalize') }}
                        {{ html()->select('category_id', $getCategoryList)->value($row?->category_id ?? '')->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.status.name'), 'name')->class('text-capitalize') }}
                        {{ html()->select('status', optionStatus())->value($row?->status ?? '')->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.popular.name'), 'popular')->class('text-capitalize') }}
                        {{ html()->select('popular', optionPopular())->value($row?->popular ?? '')->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')  }}
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
            {{ html()->submit(__('trans.btn.save'))->class('btn btn-primary text-capitalize me-1') }}
            <a href="{{ route('admin.brand.index') }}" class="btn btn-secondary text-capitalize">
                {{ __('trans.btn.back') }}
            </a>
        </div>
    </div>
    {{ html()->form()->close()  }}
@endsection
