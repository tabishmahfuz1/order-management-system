<table class="table table-striped table-bordered table-hover table-sm">
  <thead>
    <tr>
      <th style="width: 40%;">Fulfilment No</th>
      <!-- <th>Fulfilment Date</th> -->
      <th>Fulfilment Amount</th>
      <th>Tax</th>
      <th>Fulfilment Total</th>
      <th>Action</th>
      <th>View Items</th>
    </tr>
  </thead>
  <tbody id="fulfilments-list">
    @isset($fulfilments)
        @each('invoice.fulfilment_row', $fulfilments, 'fulfilment')
    @endisset
  </tbody>
  <tfoot>
    <tr>
      <td class="text-right">Total:</td>
      <td><input class="form-control form-control-sm text-right" 
      				id="inv_sub_total_input" 
      				type="number" 
      				step=".01" 
      				name="invoice[sub_total]" 
      				readonly /></td>
      <td><input class="form-control form-control-sm text-right" 
      				id="inv_tax_amt_input" 
      				type="number" 
      				step=".01" 
      				name="invoice[tax_amt]" 
      				readonly /></td>
      <td><input class="form-control form-control-sm text-right" 
      				id="inv_grandtotal_input" 
      				type="number" 
      				step=".01" 
      				name="invoice[grandtotal]" 
      				readonly /></td>
      <td></td>
      <td></td>
    </tr>
  </tfoot>
</table>