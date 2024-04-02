<div class="row">
    <div class="col-12">
        <table class="footable-details table table-striped table-hover toggle-circle">
            <tbody>
            <tr>
                <td class="text-dark">{{__('Name')}}</td>
                <td> {{ $item->name}}</td>
            </tr>
            <tr>
                <td class="text-dark">{{__('Category')}}</td>
                <td>{{ !empty($item->categories)?$item->categories->name:'' }}</td>
            </tr>
            <tr>
                <td class="text-dark">{{__('Sku')}}</td>
                <td>{{ $item->sku }}</td>
            </tr>
            <tr>
                <td class="text-dark">{{__('Sale Price')}}</td>
                <td>{{ \Auth::user()->priceFormat($item->sale_price) }}</td>
            </tr>
            <tr>
                <td class="text-dark">{{__('Purchase Price')}}</td>
                <td>{{  \Auth::user()->priceFormat($item->purchase_price )}}</td>
            </tr>
            <tr>
                <td class="text-dark">{{__('Tax')}}</td>
                <td>
                    @php
                        $taxes=\Utility::tax($item->tax);
                    @endphp
                    @foreach($taxes as $tax)
                        {{ !empty($tax)?$tax->name:''  }}<br>

                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="text-dark">{{__('Unit')}}</td>
                <td>{{ !empty($item->units)?$item->units->name:'' }}</td>
            </tr>
            <tr>
                <td class="text-dark">{{__('Description')}}</td>
                <td>{{ $item->description }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
