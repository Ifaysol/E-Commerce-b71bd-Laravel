@extends('layouts.app')

@section('content')

    <div class="tab-base">

        <!--Nav Tabs-->
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#demo-lft-tab-1" aria-expanded="true">{{ translate('Home slider') }}</a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#demo-lft-tab-2" aria-expanded="false">{{ translate('Home banner 1') }}</a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#demo-lft-tab-4" aria-expanded="false">{{ translate('Home categories') }}</a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#demo-lft-tab-3" aria-expanded="false">{{ translate('Home banner 2') }}</a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#demo-lft-tab-5" aria-expanded="false">{{ translate('Top 10') }}</a>
            </li>
            <li class="">
                <a data-toggle="tab" href="#demo-lft-tab-6" aria-expanded="false">{{ translate('Slider Beside Image') }}</a>
            </li>
        </ul>

        <!--Tabs Content-->
        <div class="tab-content">
            <div id="demo-lft-tab-1" class="tab-pane fade active in">

                <div class="row">
                    <div class="col-sm-12">
                        <a onclick="add_slider()" class="btn btn-rounded btn-info pull-right">{{translate('Add New Slider')}}</a>
                    </div>
                </div>

                <br>

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{translate('Home slider')}}</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{translate('Photo')}}</th>
                                    <th width="50%">{{translate('Link')}}</th>
                                    <th>{{translate('Published')}}</th>
                                    <th width="10%">{{translate('Options')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Slider::all() as $key => $slider)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td><img loading="lazy"  class="img-md" src="{{ my_asset($slider->photo)}}" alt="Slider Image"></td>
                                        <td>{{$slider->link}}</td>
                                        <td><label class="switch">
                                            <input onchange="update_slider_published(this)" value="{{ $slider->id }}" type="checkbox" <?php if($slider->published == 1) echo "checked";?> >
                                            <span class="slider round"></span></label></td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                    {{translate('Actions')}} <i class="dropdown-caret"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a onclick="confirm_modal('{{route('sliders.destroy', $slider->id)}}');">{{translate('Delete')}}</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div id="demo-lft-tab-2" class="tab-pane fade">

                <div class="row">
                    <div class="col-sm-12">
                        <a onclick="add_banner_1()" class="btn btn-rounded btn-info pull-right">{{translate('Add New Banner')}}</a>
                    </div>
                </div>

                <br>

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{translate('Home banner')}} ({{translate('Max 3 published')}})</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{translate('Photo')}}</th>
                                    <th>{{translate('Position')}}</th>
                                    <th>{{translate('Published')}}</th>
                                    <th width="10%">{{translate('Options')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Banner::where('position', 1)->get() as $key => $banner)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td><img loading="lazy"  class="img-md" src="{{ my_asset($banner->photo)}}" alt="banner Image"></td>
                                        <td>{{ translate('Banner Position ') }}{{ $banner->position }}</td>
                                        <td><label class="switch">
                                            <input onchange="update_banner_published(this)" value="{{ $banner->id }}" type="checkbox" <?php if($banner->published == 1) echo "checked";?> >
                                            <span class="slider round"></span></label></td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                    {{translate('Actions')}} <i class="dropdown-caret"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a onclick="edit_home_banner_1({{ $banner->id }})">{{translate('Edit')}}</a></li>
                                                    <li><a onclick="confirm_modal('{{route('home_banners.destroy', $banner->id)}}');">{{translate('Delete')}}</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div id="demo-lft-tab-3" class="tab-pane fade">

                <div class="row">
                    <div class="col-sm-12">
                        <a onclick="add_banner_2()" class="btn btn-rounded btn-info pull-right">{{translate('Add New Banner')}}</a>
                    </div>
                </div>

                <br>

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{translate('Home banner')}} ({{translate('Max 3 published')}})</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{translate('Photo')}}</th>
                                    <th>{{translate('Position')}}</th>
                                    <th>{{translate('Published')}}</th>
                                    <th width="10%">{{translate('Options')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Banner::where('position', 2)->get() as $key => $banner)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td><img loading="lazy"  class="img-md" src="{{ my_asset($banner->photo)}}" alt="banner Image"></td>
                                        <td>{{ translate('Banner Position ') }}{{ $banner->position }}</td>
                                        <td><label class="switch">
                                            <input onchange="update_banner_published(this)" value="{{ $banner->id }}" type="checkbox" <?php if($banner->published == 1) echo "checked";?> >
                                            <span class="slider round"></span></label></td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                    {{translate('Actions')}} <i class="dropdown-caret"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a onclick="edit_home_banner_2({{ $banner->id }})">{{translate('Edit')}}</a></li>
                                                    <li><a onclick="confirm_modal('{{route('home_banners.destroy', $banner->id)}}');">{{translate('Delete')}}</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div id="demo-lft-tab-4" class="tab-pane fade">
                <div class="row">
                    <div class="col-sm-12">
                        {{-- <a onclick="add_home_category()" class="btn btn-rounded btn-info pull-right">{{translate('Add New Category')}}</a> --}}
                        <a class="btn btn-rounded btn-info pull-right" data-toggle="tab" href="#demo-lft-tab-7" aria-expanded="true">{{ translate('Add New Category') }}</a>
                    </div>
                </div>

                <br>

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{translate('Home Categories')}}</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{translate('Title')}}</th>
                                    <th>{{translate('Category')}}</th>
                                    
                                    <th>{{ translate('Serial') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th width="10%">{{translate('Options')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\HomeCategory::orderBy('serial', 'ASC')->get() as $key => $home_category)
                                    @if ($home_category->category != null)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$home_category->title}}</td>
                                            <td>{{$home_category->category->name}}</td>
                                            <td>{{$home_category->serial}}</td>
                                            <td><label class="switch">
                                                <input onchange="update_home_category_status(this)" value="{{ $home_category->id }}" type="checkbox" <?php if($home_category->status == 1) echo "checked";?> >
                                                <span class="slider round"></span></label></td>
                                            <td>
                                                <div class="btn-group dropdown">
                                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                        {{translate('Actions')}} <i class="dropdown-caret"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li><a href="{{route('home_category.edit', $home_category->id )}}">{{translate('Edit')}}</a></li>
                                                        {{-- <li><a onclick="edit_home_category({{ $home_category->id }})">{{translate('Edit')}}</a></li> --}}
                                                        <li><a onclick="confirm_modal('{{route('home_categories.destroy', $home_category->id)}}');">{{translate('Delete')}}</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div id="demo-lft-tab-7" class="tab-pane fade">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{translate('Home Categories')}}</h3>
                    </div>
                    <!--Horizontal Form-->
                    <!--===================================================-->
                    <form class="form-horizontal" action="{{ route('home_categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-lg-7 mb-2"  id="category">
                                    <label class="col-lg-3 control-label">{{translate('Title')}}</label>
                                    <div class="col-lg-6">
                                        
                                        <input class="form-control" type="text" name="title" value="" required="">
                                        
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                                <div class="col-lg-7 mb-2"  id="category">
                                    <label class="col-lg-3 control-label">{{translate('Category')}}</label>
                                    <div class="col-lg-6">
                                        <select class="form-control demo-select2-placeholder" name="category_id" id="category_id" required>
                                            @foreach(\App\Category::all() as $category)
                                           {{-- @if (\App\HomeCategory::where('category_id', $category->id)->first() == null) --}}
                                            <option value="{{$category->id}}">{{__($category->name)}}</option>
                                           {{--  @endif --}}
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                                <div class="col-lg-7 mb-2" id="subcategory">
                                    <label class="col-lg-3 control-label">{{translate('Subcategory')}}</label>
                                    <div class="col-lg-6">
                                        <select class="form-control demo-select2-multiple-selects" name="subcategory_id[]" id="subcategory_id" required multiple="multiple">
                                        </select>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                                {{--
                                <div class="col-lg-7 mb-2" id="subsubcategory">
                                    <label class="col-lg-3 control-label">{{translate('Sub Subcategory')}}</label>
                                    <div class="col-lg-6">
                                        <select class="form-control demo-select2-placeholder" name="subsubcategory_id" id="subsubcategory_id">
                                        </select>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                                --}}
                                <div class="col-lg-7 mb-2" id="subsubcategory">
                                    <label class="col-lg-3 control-label">Serial</label>
                                    <div class="col-lg-6">
                                        <input class="form-control" type="number" name="serial" value="" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button class="btn btn-purple" type="submit">{{translate('Save')}}</button>
                        </div>
                    </form>
                    <!--===================================================-->
                    <!--End Horizontal Form-->
                </div>
            </div>
            <div id="demo-lft-tab-5" class="tab-pane fade">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{translate('Top 10 Information')}}</h3>
                    </div>

                    <!--Horizontal Form-->
                    <!--===================================================-->
                    <form class="form-horizontal" action="{{ route('top_10_settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-sm-3" for="url">{{translate('Top Categories (Max 10)')}}</label>
                                <div class="col-sm-9">
                                    <select class="form-control demo-select2-max-10" name="top_categories[]" multiple>
                                        @foreach (\App\Category::all() as $key => $category)
                                            <option value="{{ $category->id }}" @if($category->top == 1) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3" for="url">{{translate('Top Brands (Max 10)')}}</label>
                                <div class="col-sm-9">
                                    <select class="form-control demo-select2-max-10" name="top_brands[]" multiple>
                                        @foreach (\App\Brand::all() as $key => $brand)
                                            <option value="{{ $brand->id }}" @if($brand->top == 1) selected @endif>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button class="btn btn-purple" type="submit">{{translate('Save')}}</button>
                        </div>
                    </form>
                    <!--===================================================-->
                    <!--End Horizontal Form-->

                </div>
            </div>
            <div id="demo-lft-tab-6" class="tab-pane fade">

                <div class="row">
                    <div class="col-sm-12">
                        <a onclick="add_banner_3()" class="btn btn-rounded btn-info pull-right">{{translate('Slider Beside Image')}}</a>
                    </div>
                </div>

                <br>

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{translate('Slider Beside Image')}}</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{translate('Photo')}}</th>
                                    <th>{{translate('Position')}}</th>
                                    <th>{{translate('Published')}}</th>
                                    <th width="10%">{{translate('Options')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Banner::where('position', 3)->get() as $key => $banner)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td><img loading="lazy"  class="img-md" src="{{ my_asset($banner->photo)}}" alt="banner Image"></td>
                                        <td>{{ translate('Banner Position ') }}{{ $banner->position }}</td>
                                        <td><label class="switch">
                                            <input onchange="update_banner_published(this)" value="{{ $banner->id }}" type="checkbox" <?php if($banner->published == 1) echo "checked";?> >
                                            <span class="slider round"></span></label></td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                    {{translate('Actions')}} <i class="dropdown-caret"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a onclick="edit_home_banner_3({{ $banner->id }})">{{translate('Edit')}}</a></li>
                                                    <li><a onclick="confirm_modal('{{route('home_banners.destroy', $banner->id)}}');">{{translate('Delete')}}</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

<script type="text/javascript">

    function updateSettings(el, type){
        if($(el).is(':checked')){
            var value = 1;
        }
        else{
            var value = 0;
        }
        $.post('{{ route('business_settings.update.activation') }}', {_token:'{{ csrf_token() }}', type:type, value:value}, function(data){
            if(data == 1){
                showAlert('success', 'Settings updated successfully');
            }
            else{
                showAlert('danger', 'Something went wrong');
            }
        });
    }

    function add_slider(){
        $.get('{{ route('sliders.create')}}', {}, function(data){
            $('#demo-lft-tab-1').html(data);
        });
    }

    function add_banner_1(){
        $.get('{{ route('home_banners.create', 1)}}', {}, function(data){
            $('#demo-lft-tab-2').html(data);
        });
    }

    function add_banner_2(){
        $.get('{{ route('home_banners.create', 2)}}', {}, function(data){
            $('#demo-lft-tab-3').html(data);
        });
    }

    function add_banner_3(){
        $.get('{{ route('home_banners.create', 3)}}', {}, function(data){
            $('#demo-lft-tab-6').html(data);
        });
    }

    function edit_home_banner_1(id){
        var url = '{{ route("home_banners.edit", "home_banner_id") }}';
        url = url.replace('home_banner_id', id);
        $.get(url, {}, function(data){
            $('#demo-lft-tab-2').html(data);
            $('.demo-select2-placeholder').select2();
        });
    }

    function edit_home_banner_3(id){
        var url = '{{ route("home_banners.edit", "home_banner_id") }}';
        url = url.replace('home_banner_id', id);
        $.get(url, {}, function(data){
            $('#demo-lft-tab-6').html(data);
            $('.demo-select2-placeholder').select2();
        });
    }

    function edit_home_banner_2(id){
        var url = '{{ route("home_banners.edit", "home_banner_id") }}';
        url = url.replace('home_banner_id', id);
        $.get(url, {}, function(data){
            $('#demo-lft-tab-3').html(data);
            $('.demo-select2-placeholder').select2();
        });
    }

    function add_home_category(){
        $.get('{{ route('home_categories.create')}}', {}, function(data){
            $('#demo-lft-tab-4').html(data);
            $('.demo-select2-placeholder').select2();
        });
    }

    function edit_home_category(id){
        var url = '{{ route("home_categories.edit", "home_category_id") }}';
        url = url.replace('home_category_id', id);
        $.get(url, {}, function(data){
            $('#demo-lft-tab-4').html(data);
            $('.demo-select2-placeholder').select2();
        });
    }

    function update_home_category_status(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('home_categories.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                showAlert('success', 'Home Page Category status updated successfully');
            }
            else{
                showAlert('danger', 'Something went wrong');
            }
        });
    }

    function update_banner_published(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        $.post('{{ route('home_banners.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
            if(data == 1){
                showAlert('success', 'Banner status updated successfully');
            }
            else{
                showAlert('danger', 'Maximum 4 banners to be published');
            }
        });
    }

    function update_slider_published(el){
        if(el.checked){
            var status = 1;
        }
        else{
            var status = 0;
        }
        var url = '{{ route('sliders.update', 'slider_id') }}';
        url = url.replace('slider_id', el.value);

        $.post(url, {_token:'{{ csrf_token() }}', status:status, _method:'PATCH'}, function(data){
            if(data == 1){
                showAlert('success', 'Published sliders updated successfully');
            }
            else{
                showAlert('danger', 'Something went wrong');
            }
        });
    }
    $('#category_id').on('change', function() {
        get_subcategories_by_category();
    });
    function get_subcategories_by_category(){
        var category_id = $('#category_id').val();
        $.post('{{ route('subcategories.get_subcategories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
            $('#subcategory_id').html(null);
            $('#subcategory_id').append($('<option>', {
                value: null,
                text: 'Select'
            }));
            for (var i = 0; i < data.length; i++) {
                $('#subcategory_id').append($('<option>', {
                    value: data[i].id,
                    text: data[i].name
                }));
                $('.demo-select2').select2();
            }
            get_subsubcategories_by_subcategory();
        });
    }
    
    // $('#subcategory_id').on('change', function() {
    //     get_subsubcategories_by_subcategory();
    // });

    // function get_subsubcategories_by_subcategory(){
    //     var subcategory_id = $('#subcategory_id').val();
    //     $.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){
    //         $('#subsubcategory_id').html(null);
    //         $('#subsubcategory_id').append($('<option>', {
    //             value: null,
    //             text: 'Select'
    //         }));
    //         for (var i = 0; i < data.length; i++) {
    //             $('#subsubcategory_id').append($('<option>', {
    //                 value: data[i].id,
    //                 text: data[i].name
    //             }));
    //             $('.demo-select2').select2();
    //         }
    //         //get_brands_by_subsubcategory();
    //         //get_attributes_by_subsubcategory();
    //     });
    // }

</script>

@endsection
