@extends('layouts.app')

@section('content')

    <div class="tab-base">

        

        <!--Tabs Content-->
        <div class="tab-content">
                <div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{translate('Home Categories')}}</h3>
    </div>

    <!--Horizontal Form-->
    <!--===================================================-->
    <form class="form-horizontal" action="{{ route('home_categories.update_category', $homeCategory->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="id" value="{{$homeCategory->id}}">
                <div class="col-lg-7 mb-2"  id="category">
                    <label class="col-lg-3 control-label">{{translate('Title')}}</label>
                    <div class="col-lg-6">
                        
                        <input class="form-control" type="text" name="title" value="{{$homeCategory->title}}" required="">
                      
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
                            
                                <option @if($homeCategory->category_id == $category->id) selected  @endif value="{{$category->id}}">{{__($category->name)}}</option>
                            
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
                            <select class="form-control demo-select2-multiple-selects"" name="subcategory_id[]" id="subcategory_id" required multiple="multiple">
                                @foreach($subcategory as $subcat)
                            
                                <option @if(in_array($subcat->id,explode(',',$homeCategory->subcategory_id))) selected  @endif value="{{$subcat->id}}">{{__($subcat->name)}}</option>
                            
                        @endforeach
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
                            <select class="form-control demo-select2-placeholder" name="subsubcategory_id" id="subsubcategory_id" required="">
                                @foreach($subsubcategory as $subsubcat)
                            
                                <option @if($homeCategory->subsubcategory_id == $subsubcat->id) selected  @endif value="{{$subsubcat->id}}">{{__($subsubcat->name)}}</option>
                                @endforeach
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
                            <input class="form-control" type="number" name="serial" value="{{$homeCategory->serial}}" required="">
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
    </div>

@endsection

@section('script')

<script type="text/javascript">

    $(document).ready(function(){
});


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
