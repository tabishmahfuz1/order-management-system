<table class="m-auto table table-bordered table-striped table-sm w-75">
	<thead>
		<tr>
			<th style="width: 20%;">Type</th>
			<th style="width: 20%;">Date</th>
			<th class="text-center">Quantity</th>
			<th style="width: 40%;">Remarks</th>
			<th style="width: 10%;">Action</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<select class="select form-control-sm form-control"
					name="stock_entry_type"
					id="stock_entry_type"
					required>
					<option value="">Select a type</option>
					<option value="{{ \App\ItemStockDetail::RECEIVING }}">Receiving</option>
					<option value="{{ \App\ItemStockDetail::ADJUSTMENT }}">Adjustment</option>
				</select>
			</td>
			<td>
				<input type="text"
				name="entry_date"
				id="entry_date"
				class="form-control form-control-sm datepicker" />
			</td>
			<td>
				<input type="number"
				name="entry_quantity"
				id="entry_quantity"
				class="form-control form-control-sm" />
			</td>
			<td>
				<input type="text"
				name="entry_remarks"
				id="entry_remarks"
				class="form-control form-control-sm" />
			</td>
			<td class="text-center">
				<button type="button"
				class="btn btn-sm btn-success"
				onclick="AddStockDetail(this)">
				<i class="fa fa-plus fa-sm"></i>
				</button>
			</td>
		</tr>
	</tbody>
	<tbody id="stock_details_body">
		@each('inventory.item_stock_line_row', $item->StockDetails, 'stockDetail')
	</tbody>
</table>