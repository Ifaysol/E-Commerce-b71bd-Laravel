@extends('frontend.layouts.app')

@section('content')

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <!-- Page title -->
                    
                    <!-- dashboard content -->
                    <div class="bg-white p-3">
                        <div class="row">
                            @if(Auth::user()->user_type == 'seller')
                            <div class="col-sm-12">
    <div class="panel">
        

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('flash_deals.update_seller', $flash_deal->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="panel-body">
                <div class="form-group">
                    
                    <h5>Campaign Title: {{ $flash_deal->title }}</h5>
                </div>
                <div class="form-group">
                    
                    <div class="col-sm-9">
                        <p>Date: {{ date('m/d/Y', $flash_deal->start_date) }}
                                 to 
                                {{ date('m/d/Y', $flash_deal->end_date) }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="products"><h5>{{translate('Products')}}</h5></label>
                    <div class="col-sm-9">
                        <select name="products[]" id="products" class="form-control demo-select2 select2" multiple required data-placeholder="{{ translate('Choose Products') }}">
                            @foreach(\App\Product::where('published',1)->where('user_id',\Auth::user()->id)->select('id', 'name')->get() as $product)
                                @php
                                    $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('user_id', \Auth::user()->id)->where('product_id', $product->id)->first();
                                @endphp
                                <option value="{{$product->id}}" <?php if($flash_deal_product != null) echo "selected";?> >{{__($product->name)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="form-group" id="discount_table">

                </div>
            </div>
            <div class="panel-footer">
                <button class="btn btn-purple" type="submit">{{translate('Save')}}</button>
                <br>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
<link href="{{ my_asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet">

    <link href="{{ my_asset('css/bootstrap-select.min.css')}}" rel="stylesheet">
<script src="{{ my_asset('plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{ my_asset('js/bootstrap-select.min.js')}}"></script>

    <script type="text/javascript">
        $(".demo-select2").select2();
        $(document).ready(function(){

            get_flash_deal_discount();

            $('#products').on('change', function(){
                get_flash_deal_discount();
            });

            function get_flash_deal_discount(){
                var product_ids = $('#products').val();
                if (product_ids) {
                    if(product_ids.length > 0){
                    $.post('{{ route('flash_deals.supplier_product_discount_edit') }}', {_token:'{{ csrf_token() }}', product_ids:product_ids, flash_deal_id:{{ $flash_deal->id }}}, function(data){
                        $('#discount_table').html(data);
                        $('.demo-select2').select2();
                    });
                }
                else{
                    $('#discount_table').html('');
                }
                } else{
                    $('#discount_table').html('');
                }
                
            }
        });
    </script>
@endsection
