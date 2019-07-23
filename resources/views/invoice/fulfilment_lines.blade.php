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
</table>