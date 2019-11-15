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
					v-model="newStockDetail.type"
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
				v-model="newStockDetail.quantity"
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
		            v-on:click="stockDetail.editing = true">
		      	<i class="fa fa-edit fa-sm"></i>
		    </button>
		    <button class="btn btn-sm btn-success" 
		            type="button"
		            v-if="stockDetail.editing"
		            v-on:click="AddStockDetail(stockDetail, i)">
		      	Save
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
					type: "",
			        date: new Date(),
			        quantity: null,
			        remarks: null,
			        item_id: this.itemId,
				},
				stockDetails: [],
				RECEIVING: 'RECEIVING',
				ADJUSTMENT: 'ADJUSTMENT',
				baseUrl: `/api/item/${this.itemId}/stock`
			}	
		},
		props: ['itemId'],

		async mounted(){
			this.fetchStockDetails();
		},
		methods: { 
        	fetchStockDetails: async function(){
        		let res = await axios.get(`${this.baseUrl}`);
            	this.stockDetails = res.data;
        	},
        	initializeNewItemInputs: function(){
        		this.newStockDetail = {
					type: "",
			        date: new Date(),
			        quantity: null,
			        remarks: null,
			        item_id: this.itemId,
				};
        	},
			AddStockDetail: async function (stockDetail, i) {
				let exists = stockDetail;
				stockDetail = stockDetail || this.newStockDetail;
		        if(!stockDetail.type || !stockDetail.date || !stockDetail.quantity) {
		          showDangerMsg("One or more of the required fields are mmissing");
		          return false;
		        } 

		        stockDetail.date = stockDetail.date.toDatabaseFormat();

		        try{
		          	let res = await axios.post(`${this.baseUrl}`, stockDetail);
		          	stockDetail = res.data;
		          	if(exists) {
		          		stockDetail.editing = false;
		          		Vue.set(
		                    this.stockDetails, 
		                    i, 
		                    stockDetail
		                );
		          	} else {
		          		this.stockDetails.push(stockDetail);
		          		this.initializeNewItemInputs();
		          	}

		          	this.$emit('item-update', stockDetail.item);
		        } catch(err) {
		          console.error("Error while adding new Stock detail", err);
		        }
			  }
		}
	}
</script>