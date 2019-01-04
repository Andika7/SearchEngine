<script type="text/javascript">
<?php
     include APPPATH ."modules/Dashboard/ajax/Dashboard.js";
?>
</script>

<body style="background-color: #ffffff;">

    <div class="docs-header-custom">
      <!--nav-->
      <nav class="navbar navbar-default navbar-custom" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href=""><img src="<?php echo base_url() ?>asset/vendor/bootflat/img/a-logo-1.png" height="40"></a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
              <li><a class="nav-link current" href="">Dashboard</a></li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="topic">
        <div class="container">
			<div class="col-md-8">
			  <h3>Find News</h3>
			  <h4> search various news here</h4>
			</div>
        </div>
      <form action="#" method="post" onsubmit="return false">
        <div style="margin:50px auto; width:600px;">
        <div class="input-group form-search">
          <input name="cari" id="cari" type="text" class="form-control search-query">
          <span class="input-group-btn">
            <button type="button" class="btn btn-danger" data-type="last">Search</button>
          </span>
        </div>
      </div>
      <h5 style="text-align: center; color:#ffffff">Range Search</h5>
      <div style="margin:0 auto; width:100px;">
      <select name="top-ranking" id="rank" class="selecter_3">
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      </div>
    </form>
    </div>
    </div>

    <div class="row">
    <div class="col-md-6" style="margin:70px 60px auto;" id="content">

    </div>
    <div class="col-md-4" style="margin:70px 0px 0px 30px;">
      <div ><h5 id="time" style=""></h5></div>
      <div id="found"></div>
    </div>
  </div>

</body>
