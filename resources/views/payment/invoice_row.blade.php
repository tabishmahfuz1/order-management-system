<tr class="invoice_fulfilment_row" data-fulfilment_id="{{ $fulfilment['id'] }}">
	<td class="text-center">
		{{ $fulfilment['fulfilment_no'] }}
	</td>
	<!-- {{-- <td class="text-center fulfilment_date">@date($fulfilment['fulfilment_date'])</td> --}} -->
	<td class="text-right fulfilment_amt_td">
		{{ $fulfilment['fulfilment_amt'] }}
	</td>
	<td class="text-right fulfilment_tax_td">
		{{ $fulfilment['fulfilment_tax'] }}
	</td>
	<td class="text-right fulfilment_total_td">
		{{ $fulfilment['fulfilment_total'] }}
	</td>
	<td class="text-center">
		<input type="checkbox" 
				class="checkbox fulfilment_checkbox"
				name="invoice[fulfilments][]"
				onchange="calculateInvoiceTotals()" 
				value="{{ $fulfilment['id'] }}" />
	</td>
	<td class="text-center">
		<span class="btn-success btn-sm"
				onclick="getItems(this, '{{ $fulfilment['id'] }}')"> <i class="fa fa-sm fa-plus"></i>
		</span>
	</td>
</tr>