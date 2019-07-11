@php $row_num = $item->id; @endphp
<tr class="item_row" data-row-num="{{ $row_num }}" data-so-item-id="{{ $item->id }}}">
  <td>
    <input type="text" readonly name="order[items][{{ $row_num }}][item_name]" value="{{ $item->item_name }}" readonly class="form-control editable_input item_name_input ">
    <input type="hidden" name="order[items][{{$row_num}}][item_id]" class="item_id_input" value="{{$item->item_id}}">
    <input type="hidden" name="order[items][{{$row_num}}][so_item_id]" class="so_item_id_input" value="{{$item->id}}">
  </td>
  <td>
    <input type="number" name="order[items][{{$row_num}}][item_cost]" value="{{$item->item_cost}}" step=".01" readonly class="form-control item_cost_input"/>
  </td>
  <td>
    <input type="number" name="order[items][{{$row_num}}][item_price]" value="{{$item->item_price}}" step=".01" readonly class="form-control editable_input item_price_input"/>
  </td>
  <td>
    <input type="number" name="order[items][{{$row_num}}][item_disc_per]" value="{{$item->item_disc_per}}" step=".01" readonly class="form-control editable_input item_disc_per_input"/>
  </td>
  <td>
    <input type="number" name="order[items][{{$row_num}}][item_disc_amt]" value="{{$item->item_disc_amt}}"  step=".01" readonly class="form-control item_disc_amt_input"/>
  </td>
  <td>
    <input type="number" name="order[items][{{$row_num}}][item_rate]" value="{{$item->item_rate}}" step=".01" readonly class="form-control editable_input item_rate_input"/>
  </td>
  <td>
    <input type="number" name="order[items][{{$row_num}}][qty_on_hand]" value="{{$item->qty_on_hand}}" readonly class="form-control qty_on_hand_input"/>
  </td>
  <td>
    <input type="number" name="order[items][{{$row_num}}][item_qty]" value="{{$item->item_qty}}" readonly class="form-control editable_input item_qty_input"/>
  </td>
  <td>
    <input type="number" name="order[items][{{$row_num}}][item_total]" value="{{$item->item_total}}" step=".01" readonly class="form-control item_total_input"/>
  </td>
  <td>
    <button type="button" class="btn btn-sm btn-primary" onclick="EditItem(this)"><i class="fa fa-edit"></i></button>
  </td>
</tr>