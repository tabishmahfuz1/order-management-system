<template>
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
					<option :value="RECEIVING">Receiving</option>
					<option :value="ADJUSTMENT">Adjustment</option>
				</select>
			</td>
			<td>
				<datepicker v-model="newStockDetail.date" 
							name="entry_date"
							class="form-control form-control-sm datepicker"
							>
				</datepicker>
			</td>
			<td>
				<input type="number"
				name="entry_quantity"
				id="entry_quantity"
				v-model="newStockDetail.type"
				class="form-control form-control-sm" />
			</td>
			<td>
				<input type="text"
				name="entry_remarks"
				id="entry_remarks"
				v-model="newStockDetail.remarks"
				class="form-control form-control-sm" />
			</td>
			<td class="text-center">
				<button type="button"
				class="btn btn-sm btn-success"
				v-on:click="AddStockDetail()">
				<i class="fa fa-plus fa-sm"></i>
				</button>
			</td>
		</tr>
	</tbody>
	<tbody id="stock_details_body"><!-- 
		{{-- @each('inventory.item_stock_line_row', $item->StockDetails, 'stockDetail') --}} -->
		<tr v-for="(stockDetail, i) in stockDetails" v-bind:row_id="stockDetail.id">
		  <td class="text-center">{{ stockDetail.type }}</td>
		  <td class="text-center">{{ stockDetail.date }}</td>
		  <td class="text-center">{{ stockDetail.quantity }}</td>
		  <td>{{ stockDetail.remarks }}</td>
		  <td class="text-center">
		    <input type="hidden" name="item_stock_detail_id" :value="stockDetail.id" />
		    <button class="btn btn-sm btn-primary" 
		            type="button"
		            v-on:click="EditStockDetail(stockDetail)">
		      <i class="fa fa-edit fa-sm"></i>
		    </button>
		  </td>
		</tr>
	</tbody>
</table>
</template>
<script>
	import Datepicker from 'vuejs-datepicker';
	export default {
		name: 'item-stock-detail',
		components: {
		    Datepicker
		},
		data: function(){
			return {
				newStockDetail: {
					type,
			        date,
			        quantity,
			        remarks,
			        item_id: this.itemId,
				}
				stockDetails: null,
				RECEIVING: 'RECEIVING',
				ADJUSTMENT: 'ADJUSTMENT',
				baseUrl: '/api/item/stock'
			}	
		},
		props: ['itemId'],

		async mounted(){

		},
		methods: {
			AddStockDetail: async function (stockDetail) {

				stockDetail = stockDetail || this.newStockDetail;
			        if(!stockDetail.type || !stockDetail.date || !stockDetail.quantity) {
			          showDangerMsg("One or more of the required fields are mmissing");
			          return false;
			        } 

			        stockDetail.date = stockDetail.date.toISOString();

			        try{
			          stockDetail = await axios.post(`${this.baseUrl}`, data);
			          Item.qty_on_hand += stockDetail.quantity;
			          $('input[name=qty_on_hand]').val(Item.qty_on_hand);
			          $('#stock_details_body').append(createItemStockDetailRow(stockDetail));
			        } catch(err) {
			          console.error("Error while adding new Stock detail", err);
			        }
			  }
		}
	}
</script>