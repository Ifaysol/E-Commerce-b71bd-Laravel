
@extends('frontend.layouts.app')
@section('meta_title') Campaign @stop


@section('meta_title')'Campaign'@stop
@section('meta_description')@stop



@section('content')

    <div class="breadcrumb-area">
        <div class="container container-lg">
            <div class="row">
                <div class="col">
                    <ul class="breadcrumb">
                        <li><a href="{{ route('home') }}">{{ translate('Home')}}</a></li>
                        <li><a href="#">{{ translate('Campaign')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<style>
 .custom_class::after {

    background: 

    #1715158c;
    content: '';
    width: 100%;
    height: 200%;
    position: absolute;
    top: -25%;
    left: -39%;
    transform: rotate(127deg);
    overflow: hidden;

}   
</style>

    <section class="gry-bg py-4">
        <div class="container container-lg sm-px-0">
            <form class="" id="search-form" action="{{ route('search') }}" method="GET">
                <div class="row">
                
                <div class="col-xl-12">
                    <!-- <div class="bg-white"> -->
                        
                        <!-- <hr class=""> -->
                            <div class="row sm-no-gutters gutters-5">
                                
                                @foreach ($products as $key => $product)
                                
                                    <div class="col-12 products-box-bar p-3 bg-white custom_class" style="background-image: url('{{asset('/')}}/public/{{$product->banner}}');background-position: center; background-size: cover; margin-bottom: 0;background-repeat: no-repeat;overflow: hidden;">
                                        <a href="{{route('flash-deal-details',$product->slug )}}">
                                        <div class="jumbotron p-3 p-md-5 text-white rounded" style="background: transparent;">
        <div class="col-md-6 px-0" style="z-index: 9999999999999999999999;">
          <h1 class="display-4 font-italic test-white">{{$product->title}}</h1>
          <p class="lead my-3 test-white" style="font-weight: 500;">Start: {{date('d-m-Y', $product->start_date)}}</p>
          <p class="lead my-3 test-white" style="font-weight: 500;">End: {{date('d-M-Y', $product->end_date)}}</p>
        </div>
       
      </div>
       </a>
                                    </div>
                                    <br>
                                    <br>
                                @endforeach
                            </div>
                        

                    <!-- </div> -->
                </div>
            </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }
        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }
    </script>
@endsection
