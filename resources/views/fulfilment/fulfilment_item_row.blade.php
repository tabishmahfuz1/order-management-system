<tr data-item_id="{{ $item['so_item_id'] }}">
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
    <input class="form-control-sm form-control text-center" 
            value="{{ $item['item_qty'] }}" 
            disabled />
  </td>
   <td>
    <input class="form-control-sm form-control text-center" 
            value="{{ $item['balance_qty'] }}" 
            disabled />
  </td>
  <td>
    <input type="number" 
            class="form-control form-control-sm fulfil_qty_input text-center" 
            data-so_item_id="{{ $item['so_item_id'] }}" 
            name="item[{{ $item['so_item_id'] }}]" 
            value="{{ $item['fulfil_qty'] ?? 0 }}" />
  </td>
</tr>