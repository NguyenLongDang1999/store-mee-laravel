@extends('layouts.backend.index')

@section('title', isset($row) ? __('trans.attribute.update') . ': ' . $row->name : __('trans.attribute.create'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            (function () {
                const attributeForm = document.getElementById('attribute-form'),
                    meteCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    nameLabel = document.querySelector('label[for=name]')?.textContent,
                    descriptionLabel = document.querySelector('label[for=description]')?.textContent,
                    requiredValidate = ' không được bỏ trống.',
                    maxValidate = ' không được vượt quá 160 ký tự.'

                FormValidation.formValidation(attributeForm, {
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
                                            name: attributeForm.querySelector('[name="name"]').value,
                                            id: attributeForm.querySelector('[name="id"]').value
                                        };
                                    },
                                    url: "{{ route('admin.attribute.checkExistData') }}"
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
    <h4 class="fw-semibold mb-4 text-uppercase">{{ isset($row) ? __('trans.attribute.update') . ': ' . $row->name : __('trans.attribute.create')  }}</h4>

    {{ html()->form('POST', $router)->id('attribute-form')->acceptsFiles()->open() }}
    {{ html()->hidden('id', $row?->id ?? '') }}

    <div class="row g-4">
        <div class="col-12">
            <a href="{{ route('admin.attribute.index') }}" class="btn btn-secondary text-capitalize">
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
                        {{ html()->label(__('trans.attribute.title'), 'name')->class('text-capitalize') }}
                        {{ html()->text('name')->value($row?->name ?? '')->class('form-control')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.category.name'), 'category_id')->class('text-capitalize') }}
                        {{ html()->select('category_id', $getCategoryList)->value($row?->category_id ?? '')->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')  }}
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
                <h5 class="card-header mb-0 text-uppercase">{{ __('trans.variation.name') }}</h5>
                <hr class="my-0">

                <div class="card-body">
                    <div class="form-repeater">
                        <div data-repeater-list="variation">
                            @isset($row?->variations)
                                @foreach($row->variations as $key => $item)
                                    <div class="variation-item" data-repeater-item>
                                        <div class="row">
                                            <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
                                                {{ html()->label(__('trans.variation.value'), 'value-' . $key + 1)->class('text-capitalize form-label') }}
                                                {{ html()->text('value')->id('value-' . $key + 1)->value($item->name)->class('form-control')->disabled() }}
                                            </div>

                                            <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                                {{ html()->button(__('trans.variation.delete'))->type('button')->class('btn btn-label-danger mt-4')->attribute('data-repeater-delete') }}
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                @endforeach
                            @else
                                <div class="variation-item" data-repeater-item>
                                    <div class="row">
                                        <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
                                            {{ html()->label(__('trans.variation.value'), 'value-1')->class('text-capitalize form-label') }}
                                            {{ html()->text('value')->id('value-1')->class('form-control') }}
                                        </div>

                                        <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                            {{ html()->button(__('trans.variation.delete'))->type('button')->class('btn btn-label-danger mt-4')->attribute('data-repeater-delete') }}
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            @endisset
                        </div>
                        <div class="mb-0">
                            {{ html()->button(__('trans.variation.create'))->type('button')->class('btn btn-outline-primary text-capitalize')->attribute('data-repeater-create') }}
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
