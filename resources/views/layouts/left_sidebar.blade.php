<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/home') }}">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Laravel') }}</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item active">
    <a class="nav-link" href="{{ url('/home') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Interface
  </div>
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventory" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-table"></i>
      <span>Inventory</span>
    </a>
    <div id="collapseInventory" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Item Master:</h6>
        <a id="menu_new_item" class="collapse-item" href="{{ route('new_item') }}">New Item</a>
        <a id="menu_view_items" class="collapse-item" href="{{ route('view_items') }}">View Items</a>
      </div>
    </div>
  </li>

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCustomer" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-table"></i>
      <span>{{ $customer_alias ?? "Distributor" }}</span>
    </a>
    <div id="collapseCustomer" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">{{ $customer_alias ?? "Distributor" }}:</h6>
        <a id="menu_new_customer" class="collapse-item" href="{{ route('new_customer') }}">New {{ $customer_alias ?? "Distributor" }}</a>
        <a id="menu_view_customers" class="collapse-item" href="{{ route('view_customers') }}">View {{ $customer_alias ?? "Distributor" }}</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrders" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-table"></i>
      <span>Order</span>
    </a>
    <div id="collapseOrders" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Sales Order:</h6>
        <a id="menu_new_sales_order" class="collapse-item" href="{{ route('new_sales_order') }}">New Order</a>
        <a id="menu_view_sales_orders" class="collapse-item" href="{{ route('view_sales_orders') }}">View Orders</a>
        <a class="collapse-item" href="{{ route('new_fulfilment') }}">New Fulfillment</a>
        <a class="collapse-item" href="#">View Fulfillments</a>
      </div>
    </div>
  </li>

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-table"></i>
      <span>Reports</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Inventory Reports</h6>
        <a class="collapse-item" href="#">Report 1</a>
        <a class="collapse-item" href="#">Report 2</a>
      </div>
      <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Order Reports</h6>
        <a class="collapse-item" href="#">Report 1</a>
        <a class="collapse-item" href="#">Report 2</a>
      </div>
    </div>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<script type="text/javascript">
  $(function(){
    if(menu_id){
      $('#menu_' + menu_id).addClass('active');
      $('#menu_' + menu_id).closest('div.collapse').addClass('show');
      $('#menu_' + menu_id).closest('div.collapse').prev('a.nav-link').removeClass('collapsed');
    }
  })
</script>