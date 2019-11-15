<template>
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>Item Name</th>
					<th>Item Type</th>
					<th>Purchasing Cost</th>
					<th>Selling Price</th>
					<th>Stock Balance</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td>
						<input type="text" 
										name="item_name" 
										class="form-control form-control-sm"
										placeholder="Name" />
					</td>
					<td>
						
					</td>
					<td>
						<div class="input-group"> 
							<div class="input-group-append">
								<select class="rounded-left form-control-sm form-control"
												name="item_cost_comparison"> 
									<option value="lt">&lt;</option>
									<option value="le">&lt;=</option>
									<option value="ge">&gt;=</option>
									<option value="gt">&gt;</option>
									<option value="eq">=</option>
								</select>
							</div>
							<input type="number" 
											step=".01" 
											name="item_cost" 
											placeholder="Item Cost" 
											class="form-control form-control-sm">
						</div>
					</td>
					<td>
						<div class="input-group"> 
							<div class="input-group-append">
								<select class="rounded-left form-control-sm form-control"
												name="item_price_comparison"> 
									<option value="lt">&lt;</option>
									<option value="le">&lt;=</option>
									<option value="ge">&gt;=</option>
									<option value="gt">&gt;</option>
									<option value="eq">=</option>
								</select>
							</div>
							<input type="number" 
											step=".01" 
											name="item_price" 
											placeholder="Item Price" 
											class="form-control form-control-sm">
						</div>
					</td>
					<td>
						<div class="input-group"> 
							<div class="input-group-append">
								<select class="rounded-left form-control-sm form-control"
												name="qty_on_hand_comparison"> 
									<option value="lt">&lt;</option>
									<option value="le">&lt;=</option>
									<option value="ge">&gt;=</option>
									<option value="gt">&gt;</option>
									<option value="eq">=</option>
								</select>
							</div>
							<input type="number" 
											name="qty_on_hand" 
											placeholder="Stock" 
											class="form-control form-control-sm">
						</div>
					</td>
					<td>
						<select name="status"
				                  class="form-control-sm form-control">
				            <option value="">Select Status</option>
				            <option value="1">Active</option>
				            <option value="0">Disabled</option>
				        </select>
					</td>
					<td>
          				<button class="btn btn-sm btn-primary"><i class="fa fa-search fa-sm"></i></button>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<tr v-if="items.length" v-for"(item, i) in items">
					
				</tr>
				<tr v-else>
					<td colspan="5">
						No Data in Table
					</td>
				</tr>
			</tbody>
		</table>
</template>
<script type="text/javascript">
	
</script>