<div id="common_danger_alert" class="alert alert-danger fade show ml-4" role="alert" style="display: none;position: fixed;bottom: 0;">
	<span class="msg_text"><strong>Holy guacamole!</strong> You should check in on some of those fields below.</span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>
<!-- Footer .sticky-footer  -->
<footer class="bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Copyright &copy; Your Website 2019</span>
    </div>
  </div>
</footer>
<!-- End of Footer -->
<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    // $('.select2').select2();
	    let $alert = $('#common_danger_alert');
	    $alert.on("close.bs.alert", function () {
		      $alert.hide({animation:'slide', direction: 'left'});
		      return false;
		});
    });
    function showDangerMsg(msg = 'Something went wrong!') {
    	let $alert = $('#common_danger_alert');
    	$alert.find('span.msg_text').html(msg);
    	$alert.show({animation:'slide', direction: 'left'});
    }
</script>