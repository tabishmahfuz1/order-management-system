<div id="common_danger_alert" class="alert alert-danger fade show ml-4" role="alert" style="display: none;position: fixed;bottom: 0;">
	<span class="msg_text"><strong>Holy guacamole!</strong> You should check in on some of those fields below.</span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
</div>
<!-- Footer .sticky-footer  -->
<footer class="bg-white w-100" style="bottom: 0; position: fixed;">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Copyright &copy; Your Website 2019</span>
    </div>
  </div>
</footer>
<style type="text/css">
    /* width */
    ::-webkit-scrollbar {
      width: 7px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1; 
    }
     
    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #888; 
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #555; 
    }
</style>
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
    Date.prototype.toDatabaseFormat = function() {
      var mm = this.getMonth() + 1; // getMonth() is zero-based
      var dd = this.getDate();

      return [this.getFullYear(),
              (mm>9 ? '' : '0') + mm,
              (dd>9 ? '' : '0') + dd
             ].join('-');
    };
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token()
    ]) !!};
</script>