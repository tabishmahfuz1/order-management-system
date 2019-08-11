<tr class="payment_row" 
	data-payment_line_id="{{ $paymentLine->id }}">
	<td class="text-center">
		{{ $paymentLine->line_num }}
		<input type="hidden" 
				name="payment_line_id" 
				value="{{ $paymentLine->id }}" />
	</td>
	<td class="text-center">
		@date($paymentLine->date_received)
	</td>
	<td class="text-right">
		{{ $paymentLine->received_amt }}
	</td>
	<td class="text-right">
		{{ $paymentLine->balance_amt }}
	</td>
</tr>