@extends('layouts.backend.index')

@section('title', __('trans.category.manager'))

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@endsection

@section('js')
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script>
        let dataTable = $('.data_table'),
            click_mode = 0,
            aLengthMenuGeneral = [
                [20, 50, 100, 500, 1000],
                [20, 50, 100, 500, 1000]
            ];

        if (dataTable.length) {
            var result = dataTable.DataTable({
                "bServerSide": true,
                "bProcessing": true,
                "sPaginationType": "full_numbers",
                "sAjaxSource": "{{ route('admin.category.getList') }}",
                "bDeferRender": true,
                "bFilter": false,
                "bDestroy": true,
                "aLengthMenu": aLengthMenuGeneral,
                "iDisplayLength": 20,
                "bSort": true,
                "aaSorting": [
                    [5, "desc"]
                ],
                columns: [
                    {
                        data: 'responsive_id',
                        "bSortable": false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'parent_id'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'popular'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'updated_at'
                    },
                    {
                        data: 'action',
                        "bSortable": false
                    },
                ],
                "fnServerParams": function (aoData) {
                    if (click_mode === 0) {
                        aoData.push({
                            "name": "search[name]",
                            "value": $('#frmSearch input[name="search[name]"]').val()
                        });
                        aoData.push({
                            "name": "search[parent_id]",
                            "value": $('#frmSearch select[name="search[parent_id]"]').val()
                        });
                        aoData.push({
                            "name": "search[status]",
                            "value": $('#frmSearch select[name="search[status]"]').val()
                        });
                        aoData.push({
                            "name": "search[popular]",
                            "value": $('#frmSearch select[name="search[popular]"]').val()
                        });
                    }
                },
                columnDefs: [
                    {
                        // For Responsive
                        className: 'control',
                        searchable: false,
                        orderable: false,
                        responsivePriority: 2,
                        targets: 0,
                        render: function (data, type, full, meta) {
                            return '';
                        }
                    },
                    {
                        targets: 1,
                        responsivePriority: 4,
                        render: function (data, type, full, meta) {
                            let $name = full['name'],
                                $editPages = full['edit_pages'],
                                $image = full['image_uri'];
                            return '<div class="d-flex justify-content-start align-items-center user-name">' +
                                '<div class="avatar-wrapper">' +
                                '<div class="avatar avatar-sm me-3">' +
                                '<img src="' + $image + '" class="rounded-circle" alt="' + $name + '"/>' +
                                '</div>' +
                                '</div>' +
                                '<div class="d-flex flex-column">' +
                                '<a href="' +
                                $editPages +
                                '" class="text-body text-truncate text-capitalize"><span class="fw-semibold">' +
                                $name +
                                '</span></a>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    {
                        targets: 2,
                        responsivePriority: 4,
                        render: function (data, type, full, meta) {
                            let $name = full['parentName'],
                                $editPages = full['edit_pages_parent'],
                                $image = full['imageUriParent'];
                            return '<div class="d-flex justify-content-start align-items-center user-name">' +
                                '<div class="avatar-wrapper">' +
                                '<div class="avatar avatar-sm me-3">' +
                                '<img src="' + $image + '" class="rounded-circle" alt="' + $name + '"/>' +
                                '</div>' +
                                '</div>' +
                                '<div class="d-flex flex-column">' +
                                '<a href="' +
                                $editPages +
                                '" class="text-body text-truncate text-capitalize"><span class="fw-semibold">' +
                                $name +
                                '</span></a>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    {
                        targets: 3,
                        render: function (data, type, full) {
                            const $status_number = full['status'];
                            const $status = {
                                {{ config('constant.status.active') }}: {
                                    icon: '<i class="ti ti-check ti-xs"></i>',
                                    class: 'bg-label-primary'
                                },
                                {{ config('constant.status.inactive') }}: {
                                    icon: '<i class="ti ti-x ti-xs"></i>',
                                    class: ' bg-label-danger'
                                },
                            };
                            if (typeof $status[$status_number] === 'undefined') {
                                return data;
                            }
                            return (
                                '<span class="badge badge-center rounded-pill ' + $status[$status_number].class + ' w-px-30 h-px-30">' +
                                $status[$status_number].icon +
                                '</span>'
                            );
                        }
                    },
                    {
                        targets: 4,
                        render: function (data, type, full) {
                            const $featured_number = full['popular'];
                            const $featured = {
                                {{ config('constant.popular.active') }}: {
                                    icon: '<i class="ti ti-check ti-xs"></i>',
                                    class: 'bg-label-primary'
                                },
                                {{ config('constant.popular.inactive') }}: {
                                    icon: '<i class="ti ti-x ti-xs"></i>',
                                    class: ' bg-label-danger'
                                },
                            };
                            if (typeof $featured[$featured_number] === 'undefined') {
                                return data;
                            }
                            return (
                                '<span class="badge badge-center rounded-pill ' + $featured[$featured_number].class + ' w-px-30 h-px-30">' +
                                $featured[$featured_number].icon +
                                '</span>'
                            );
                        }
                    },
                    {
                        targets: -1,
                        title: 'Thao Tác',
                        render: function (data, type, full) {
                            const $editPages = full['edit_pages'],
                                $delete = full['delete'],
                                $id = full['id'];
                            return (
                                '<a href=' + $editPages + ' class="btn btn-sm btn-icon item-edit me-2"><i class="bx bxs-edit"></i></a>' +
                                '<a href="javascript:void(0)" class="btn btn-sm btn-icon item-edit" data-bs-toggle="modal" data-bs-target="#action-dialog" data-id=' + $id + ' data-action=' + $delete + '>' +
                                '<i class="bx bxs-trash"></i>' +
                                '</a>'
                            );
                        }
                    }
                ],
                dom:
                    'r <"row me-2"' +
                    '<"col-md-2"<"me-3"l>>' +
                    '>t' +
                    '<"row mx-2"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>' +
                    '>',
                language: {
                    sLengthMenu: '_MENU_',
                    search: '',
                    searchPlaceholder: 'Search..'
                },
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function (row) {
                                const data = row.data();
                                return 'Chi Tiết Thông Tin: ' + data['name'];
                            }
                        }),
                        type: 'column',
                        renderer: function (api, rowIdx, columns) {
                            const data = $.map(columns, function (col, i) {
                                return col.title !== ''
                                    ? '<tr data-dt-row="' +
                                    col.rowIndex +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>'
                                    : '';
                            }).join('');

                            return data ? $('<table class="table"/><tbody />').append(data) : false;
                        }
                    }
                }
            });
        }

        $(document).ready(function () {
            $('#btnFrmSearch').on('click', function () {
                click_mode = 0;
                result.draw();
            });

            $('#btnFrmReset').on('click', function () {
                click_mode = 1;
                result.draw();
                $('.bootstrap-select').selectpicker('val', '');
            });
        })
    </script>
@endsection

@section('content')
    <h4 class="fw-semibold mb-4 text-uppercase">{{ __('trans.category.manager')  }}</h4>

    <div class="row g-4">
        <div class="col-12">
            <a href="{{ route('admin.category.create') }}" class="btn btn-primary text-capitalize">
                <span class="ti-xs ti ti-plus me-1"></span>
                {{ __('trans.btn.create') }}
            </a>
        </div>

        <div class="col-12">
            <div class="card">
                <h5 class="card-header text-uppercase">{{ __('trans.search') }}</h5>

                <div class="card-body">
                    {{ html()->form('GET', route('admin.category.getList'))->class('row g-3')->id('frmSearch')->attribute('onsubmit', 'return false')->open() }}
                    <div class="col-md-6">
                        {{ html()->label(__('trans.category.title'), 'search[name]')->class('text-capitalize') }}
                        {{ html()->text('search[name]')->class('form-control')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.category.name'), 'search[parent_id]')->class('text-capitalize') }}
                        {{ html()->select('search[parent_id]', $getCategoryList)->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.status.name'), 'search[status]')->class('text-capitalize') }}
                        {{ html()->select('search[status]', optionStatus())->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')  }}
                    </div>

                    <div class="col-md-6">
                        {{ html()->label(__('trans.popular.name'), 'search[popular]')->class('text-capitalize') }}
                        {{ html()->select('search[popular]', optionPopular())->class('selectpicker text-capitalize w-100')->attribute('data-style', 'btn-default text-capitalize')  }}
                    </div>

                    <div class="col-12 text-center">
                        {{ html()->button(__('trans.btn.search'))->class('btn btn-sm btn-primary text-capitalize')->id('btnFrmSearch') }}
                        {{ html()->reset(__('trans.btn.reset'))->class('btn btn-sm btn-warning text-capitalize')->id('btnFrmReset') }}
                    </div>
                    {{ html()->form()->close()  }}
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0 text-uppercase">{{ __('trans.category.list') }}</h5>
                </div>

                <div class="card-datatable table-responsive">
                    <table class="data_table table border-top">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('trans.info') }}</th>
                                <th>{{ __('trans.category.parent') }}</th>
                                <th>{{ __('trans.status.name') }}</th>
                                <th>{{ __('trans.popular.name') }}</th>
                                <th>{{ __('trans.created_at') }}</th>
                                <th>{{ __('trans.updated_at') }}</th>
                                <th>{{ __('trans.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection