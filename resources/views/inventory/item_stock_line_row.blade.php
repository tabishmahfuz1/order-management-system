<tr data-row_id="{{ $stockDetail['id'] ?? '' }}">
  <td class="text-center">{{ $stockDetail['type'] }}</td>
  <td class="text-center">{{ $stockDetail['date'] }}</td>
  <td class="text-center">{{ $stockDetail['quantity'] }}</td>
  <td>{{ $stockDetail['remarks'] }}</td>
  <td class="text-center">
    <input type="hidden" name="item_stock_detail_id" value="{{ $stockDetail['id'] ?? '' }}" />
    @if($stockDetail['type'] != \App\ItemStockDetail::OPENING_STOCK)
    <button class="btn btn-sm btn-primary" 
            type="button"
            onclick="EditStockDetail(this)">
      <i class="fa fa-edit fa-sm"></i>
    </button>
    @endif
  </td>
</tr>