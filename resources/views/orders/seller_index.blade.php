@extends('layouts.app')

@section('content')
@php
    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
@endphp
<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading bord-btm clearfix pad-all h-100">
        <h3 class="panel-title pull-left pad-no">{{translate('Orders')}}</h3>
        <div class="pull-right clearfix">
            <form class="" id="sort_orders" action="" method="GET">
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 300px;">
                        <select class="form-control demo-select2" name="payment_type" id="payment_type" onchange="sort_orders()">
                            <option value="">{{translate('Filter by Payment Status')}}</option>
                            <option value="paid"  @isset($payment_status) @if($payment_status == 'paid') selected @endif @endisset>{{translate('Paid')}}</option>
                            <option value="cancel"  @isset($payment_status) @if($payment_status == 'cancel') selected @endif @endisset>{{translate('Canceled')}}</option>
                            <option value="return"  @isset($payment_status) @if($payment_status == 'return') selected @endif @endisset>{{translate('Returned')}}</option>
                            <option value="curier_unpaid"  @isset($payment_status) @if($payment_status == 'curier_unpaid') selected @endif @endisset>{{translate('Curier Unpaid')}}</option>
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="select" style="min-width: 300px;">
                        <select class="form-control demo-select2" name="delivery_status" id="delivery_status" onchange="sort_orders()">
                            <option value="">{{translate('Filter by Deliver Status')}}</option>
                            <option value="pending"   @isset($delivery_status) @if($delivery_status == 'pending') selected @endif @endisset>{{translate('Pending')}}</option>
                            <option value="on_review"   @isset($delivery_status) @if($delivery_status == 'on_review') selected @endif @endisset>{{translate('On review')}}</option>
                            <option value="on_delivery"   @isset($delivery_status) @if($delivery_status == 'on_delivery') selected @endif @endisset>{{translate('On delivery')}}</option>
                            <option value="delivered"   @isset($delivery_status) @if($delivery_status == 'delivered') selected @endif @endisset>{{translate('Delivered')}}</option>
                            <option value="canceled"   @isset($delivery_status) @if($delivery_status == 'canceled') selected @endif @endisset>{{translate('Canceled')}}</option>
                            <option value="return"   @isset($delivery_status) @if($delivery_status == 'return') selected @endif @endisset>{{translate('Returned')}}</option>
                            <option value="rejected"   @isset($delivery_status) @if($delivery_status == 'rejected') selected @endif @endisset>{{translate('Rejected')}}</option>
                            <option value="missing"   @isset($delivery_status) @if($delivery_status == 'missing') selected @endif @endisset>{{translate('Product Missing')}}</option>
                        </select>
                    </div>
                </div>
                <div class="box-inline pad-rgt pull-left">
                    <div class="" style="min-width: 200px;">
                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
            </form>
            
        </div>
        
    </div>
    <div class="panel-body">
        <form action="" method="GET" class="form-inline">
            <div class="form-group">
                <label for="email">Select Date :</label>
                <input type="date" class="form-control" id="date" name="date" value="{{$_GET['date'] ?? ''}}">
            </div>
            <button type="submit" class="btn btn-primary">Date Filter</button>
            @if(isset($_GET['date']))
            <a href='{{route('orders.index.seller')}}' class="btn btn-primary">Resset Date</a>
            @endif
        </form>
        <hr>
        <table class="table table-striped res-table mar-no" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Order Code')}}</th>
                    <th>{{translate('Num. of Products')}}</th>
                    <th>{{translate('Customer')}}</th>
                    <th>{{translate('Amount')}}</th>
                    <th>{{translate('Delivery Status')}}</th>
                    <th>{{translate('Payment Method')}}</th>
                    <th>{{translate('Payment Status')}}</th>
                    @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                        <th>{{translate('Refund')}}</th>
                    @endif
                    <th width="10%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order_id)
                    @php
                        $order = \App\Order::find($order_id->id);
                    @endphp
                    @if($order != null)
                        <tr>
                            <td>
                                {{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}
                            </td>
                            <td>
                                {{ $order->code }} @if($order->viewed == 0) <span class="pull-right badge badge-info">{{ translate('New') }}</span> @endif
                            </td>
                            <td>
                                {{ count($order->orderDetails->where('order_id', $order->id)) }}
                            </td>
                            <td>
                                @if ($order->user != null)
                                    {{ $order->user->name }}
                                @else
                                    Guest ({{ $order->guest_id }})
                                @endif
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->where('seller_id', '!=', $admin_user_id)->sum('price') + $order->orderDetails->where('seller_id', $admin_user_id)->sum('tax')) }}
                            </td>
                            <td>
                                @php
                                    $status = $order->orderDetails->first()->delivery_status;
                                @endphp
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </td>
                            <td>
                                {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                            </td>
                            <td>
                                <span class="badge badge--2 mr-4">
                                    @if ($order->orderDetails->where('seller_id', '!=', $admin_user_id)->first()->payment_status == 'paid')
                                        <i class="bg-green"></i> {{ translate('Paid') }}
                                    @elseif($order->orderDetails->where('seller_id', '!=', $admin_user_id)->first()->payment_status == 'unpaid')
                                        <i class="bg-red"></i> {{ translate('Unpaid') }}
                                    @elseif($order->orderDetails->where('seller_id', '!=', $admin_user_id)->first()->payment_status == 'cancel')
                                        <i class="bg-red"></i> {{ translate('Canceled') }}
                                    @elseif($order->orderDetails->where('seller_id', '!=', $admin_user_id)->first()->payment_status == 'return')
                                        <i class="bg-red"></i> {{ translate('Returned') }}
                                    @elseif($order->orderDetails->where('seller_id', '!=', $admin_user_id)->first()->payment_status == 'curier_unpaid')
                                        <i class="bg-red"></i> {{ translate('Couier Unpaid') }}
                                    @endif
                                </span>
                            </td>
                            @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                <td>
                                    @if (count($order->refund_requests) > 0)
                                        {{ count($order->refund_requests) }} {{ translate('Refund') }}
                                    @else
                                        {{ translate('No Refund') }}
                                    @endif
                                </td>
                            @endif
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{translate('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{ route('seller_orders.show', encrypt($order->id)) }}">{{translate('View')}}</a></li>
                                        <li><a href="{{ route('seller.invoice.download_1', $order->id) }}">{{translate('Download Invoice')}}</a></li>
                                        <li><a onclick="confirm_modal('{{route('orders.destroy', $order->id)}}');">{{translate('Delete')}}</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $orders->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endsection
