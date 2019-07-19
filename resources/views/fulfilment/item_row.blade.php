<tr data-row_id="{{ $item['id'] ?? 0 }}">
  <td>
    <input class="form-control-sm form-control" 
            value="{{ $item['item_name'] }}" 
            disabled />
  </td>
  <td>
    <input class="form-control-sm form-control text-right" 
            value="{{ $item['item_rate'] }}" 
            disabled />
  </td>
  <td>
    <input class="form-control-sm form-control text-center item_qty_input" 
            value="{{ $item['item_qty'] }}" 
            disabled />
  </td>
  <td>
    <input class="form-control-sm form-control text-center balance_qty_input" 
            value="{{ $item['balance_qty'] }}" 
            disabled />
  </td>
  <td>
    <input class="form-control-sm form-control text-center qty_on_hand_input" 
            value="{{ ($item['qty_on_hand'] ?? 0) }}" 
            disabled />
  </td>
  <td>
    <input type="hidden" 
            name="fulfilment[items][{{ $item['so_item_id'] }}][fulfilment_item_id]" 
            value="{{ $item['id'] ?? ''}}" />
    <input type="hidden" 
            name="fulfilment[items][{{ $item['so_item_id'] }}][so_item_id]" 
            value="{{ $item['so_item_id'] }}" />
    <input type="number" 
            class="form-control form-control-sm fulfil_qty_input text-center" 
            data-so_item_id="{{ $item['so_item_id'] }}" 
            name="fulfilment[items][{{ $item['so_item_id'] }}][fulfilment_qty]" 
            value="{{ $item['fulfilment_qty'] ?? 0 }}"
            onchange="validateFulfilQty(this)" />
  </td>
</tr>