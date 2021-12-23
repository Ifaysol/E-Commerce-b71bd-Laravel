@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading bord-btm">
                <h3 class="panel-title">{{translate('Select Shipping Method')}}</h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('shipping_configuration_custom.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    
                    <div class="radio mar-btm">
                        
                        <label for="seller-shipping">{{translate('Inside Dhaka Cost:')}} &nbsp;&nbsp;</label>
                        <input id="seller-shipping" class="magic-radio" type="number" name="inside_dhaka" value="{{(\App\BusinessSetting::where('type', 'inside_dhaka')->first()->value)}}">
                        <br>
                        <br>
                        <label for="seller-shipping">{{translate('Outside Dhaka Cost:')}}</label>
                        <input id="seller-shipping" class="magic-radio" type="number" name="outside_dhaka" value="{{(\App\BusinessSetting::where('type', 'outside_dhaka')->first()->value)}}">
                    </div>
                    <div class="">
                        <button class="btn btn-primary" type="submit">{{ translate('Update') }}</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
    
</div>


@endsection
