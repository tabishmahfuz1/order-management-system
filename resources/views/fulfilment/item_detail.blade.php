<table class="table table-striped table-bordered table-hover table-sm">
  <thead>
    <tr>
      <th style="width: 40%;">Item Name</th>
      <th>Item Rate</th>
      <th>Order Qty.</th>
      <th>Balance Qty.</th>
      <th>Fulfilment Qty.</th>
    </tr>
  </thead>
  <tbody id="item-list">
    @isset($items)
        @each('fulfilment.item_row', $items, 'item')
    @endisset
  </tbody>
</table>