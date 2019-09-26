<template>
	<div>
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				<a role="tab"
					class="nav-link active"
					aria-controls="fulfilments_tab"
					data-toggle="tab"
				href="#general_details">General Details</a>
			</li>
			<li class="nav-item">
				<a role="tab"
					class="nav-link"
					data-toggle="tab"
				href="#stock_details">Stock Details</a>
			</li>
			<li class="nav-item">
				<a role="tab"
					class="nav-link"
					data-toggle="tab"
				href="#movement_details">Movement</a>
			</li>
		</ul>
		<div class="container-fluid border">
			<div class="tab-content mt-1" style="min-height: 60vh;">
				<div id="general_details"
					class="tab-pane fade show active"
					role="tabpanel">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Item Name</label>
								<input class="form-control form-control-sm"
								name="item_name"
								placeholder="Item Name"
								v-model="item.item_name" />
							</div>
							<div class="form-group">
								<label class="control-label">Purchasing Cost</label>
								<input class="form-control form-control-sm"
								name="item_cost"
								placeholder="Purchasing Cost"
								v-model="item.item_cost" />
							</div>
							<div class="form-group">
								<label class="control-label">Item Type</label>
								<input class="form-control form-control-sm"
								name="item_type"
								placeholder="Item Type" />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label">Item Status</label>
								<select name="status" v-model="item.status" class="form-control form-control-sm">
									<option value="1">Active</option>
									<option value="0">Disabled</option>
								</select>
							</div>
							
							<div class="form-group">
								<label class="control-label">Selling Price</label>
								<input class="form-control form-control-sm"
								type="number"
								name="item_price"
								placeholder="Selling Price"
								v-model="item.item_price"
								step=".01" />
							</div>
							
							<div class="form-group">
								<label class="control-label">Quantity on Hand</label>
								<input type="number"
								class="form-control form-control-sm"
								name="qty_on_hand"
								disabled=""
								placeholder="0"
								:value="item.qty_on_hand" />
							</div>
						</div>
						<div class="col-md-4">
							<div class="text-center">
								<img id="item_img" src="http://images1-1.gamewise.co/pots-102166-full.jpeg" />
							</div>
							<div class="text-center">
								<button type="button" class="btn-success btn-sm">Upload image</button>
							</div>
						</div>
					</div>
					
					<div class="text-center">
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</div>
				<div id="stock_details"
					class="tab-pane"
					role="tabpanel">
					<item-stock-detail :itemId="this.item.id"></item-stock-detail>
				</div>
				<div id="movement_details" class="tab-pane" role="tabpanel">
					
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'item-detail',
		data: function(){
			return {
				id: this.itemId,
				item: {
					id: null,
					name: '',
					status: 0,
					qty_on_hand: 0,
					item_cost: 0,
					item_price: 0,

				},
				exists: false,
				baseUrl: '/api/item'
			}
		},
		props: ['itemId'],
		async mounted() {
            console.log('Component mounted.', this);
            console.log('axios', axios);
            if(this.itemId) {
            	let res = await axios.get(`${this.baseUrl}/${this.itemId}`);
            	this.item = res.data;
            	this.exists = true;
            }
            

            // console.log({item: this.item});
        },
        methods: {
        	saveItem: async function(){
        		if(this.exists) {
        			let res = await axois.post(`${this.baseUrl}/${this.item.id}`);
        			this.item = res.data;
        		}
        	}
        }
	}
</script>