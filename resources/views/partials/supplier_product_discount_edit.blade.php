@if(count($product_ids) > 0)
    <label class="col-sm-3 control-label">{{translate('Discounts')}}</label>
    <div class="col-sm-9">
        <table class="table table-bordered">
    		<thead>
    			<tr>
    				<td class="text-center" width="40%">
    					<label for="" class="control-label">{{translate('Product')}}</label>
    				</td>
                    <td class="text-center">
    					<label for="" class="control-label">{{translate('Base Price')}}</label>
    				</td>
    				<td class="text-center">
    					<label for="" class="control-label">{{translate('Discount')}}</label>
    				</td>
                    <td>
                        <label for="" class="control-label">{{translate('Discount Type')}}</label>
                    </td>
    			</tr>
    		</thead>
    		<tbody>
                @foreach ($product_ids as $key => $id)
                	@php
                		$product = \App\Product::findOrFail($id);
                        $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal_id)->where('product_id', $product->id)->where('user_id', \Auth::user()->id)->first();
                	@endphp 
                		<tr>
                			<td>
                                <div class="col-sm-3">
                                <img loading="lazy"  class="img-md" src="{{ my_asset($product->thumbnail_img)}}" alt="Image">
                                </div>
                                <div class="col-sm-9">
                				<label for="" class="control-label">{{ __($product->name) }}</label>
                                </div>
                			</td>
                            <td>
                				<label for="" class="control-label">{{ $product->unit_price }}</label>
                			</td>
                            @if ($flash_deal_product != null)
                                <td>
                    				<input type="number" name="discount_{{ $id }}" value="{{ $flash_deal_product->discount }}" min="0" step="1" class="form-control" required>
                    			</td>
                                <td>
                                    <select class="demo-select2" name="discount_type_{{ $id }}">
                                        <option value="amount" <?php if($flash_deal_product->discount_type == 'amount') echo "selected";?> >$</option>
                                        <option value="percent" <?php if($flash_deal_product->discount_type == 'percent') echo "selected";?> >%</option>
                                    </select>
                                </td>
                            @else
                                <td>
                    				<input type="number" name="discount_{{ $id }}" value="{{ $product->discount }}" min="0" step="1" class="form-control" required>
                    			</td>
                                <td>
                                    <select class="demo-select2" name="discount_type_{{ $id }}">
                                        <option value="amount" <?php if($product->discount_type == 'amount') echo "selected";?> >$</option>
                                        <option value="percent" <?php if($product->discount_type == 'percent') echo "selected";?> >%</option>
                                    </select>
                                </td>
                            @endif
                		</tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
