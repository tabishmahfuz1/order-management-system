<table class="table table-sm">
	<thead>
		<tr>
			<th>S.No.</th>
			<th>Date</th>
			<th>Received Amount</th>
			<th>Balance Amount</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<input type="hidden" 
						class="payment_line_id_input" />
			</td>
			<td>
				<input class="text-center datepicker received_date_input form-control form-control-sm" 
						type="text" />
			</td>
			<td>
				<input type="number" 
						step=".01" 
						class="text-right received_amt_input form-control form-control-sm" />
			</td>
			<td>
				<input type="number" 
						step=".01" 
						class="text-right balance_amt_input form-control form-control-sm" />
			</td>
			<td class="text-center">
				<button class="btn btn-primary btn-sm" 
						type="button"
						onclick="AddPayment(this)">
							<i class="fa fa-plus fa-sm"></i>
						</button>
			</td>
		</tr>
	</tbody>
	<tbody id="invoice_payment_list">
		@each('invoice.payment_row', $invoice->Payments, 'paymentLine')
	</tbody>
</table>

<script type="text/javascript">

	async function AddPayment(thisBtn) {
		let $thisTr 			= $(thisBtn).closest('tr'),
			payment_line_id 	= $thisTr.find('.payment_line_id_input').val(),
			date_received 		= $thisTr.find('.received_date_input').datepicker('getDate').toDatabaseFormat(),
			received_amt 		= parseFloat($thisTr.find('.received_amt_input').val()),
			balance_amt 		= (parseFloat(Invoice.Invoice.balance_amt) - received_amt),
			invoice_id 			= Invoice.Invoice.id;

		Invoice({obj: "Invoice", data: {balance_amt: balance_amt}});
		let data 		= {
			payment_line_id,
			date_received,
			received_amt,
			invoice_id
		};
		data._token 	= '{{ Session::token() }}';
		try{
			let response 	= await $.post('{{ route("add_payment_to_invoice") }}', data);
			if(!payment_line_id) {
				$('#invoice_payment_list').append(response);
			} else {
				$('#invoice_payment_list tr[data-payment_line_id=' + payment_line_id + ']').replaceWith(response);
			}
			ClearPaymentRow($thisTr);
		} catch(err) {
			console.error('Error while adding Item', err);
		}
		
	}

	function ClearPaymentRow($row) {
		$row.find('input').val('');
	}
</script>