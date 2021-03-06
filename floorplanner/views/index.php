<link href="<?=base_url('assets')?>/floorplanner/css/example.css" rel="stylesheet">
<script>
  var base_url="<?=base_url()?>";
</script>
<?php if(isset($floorScripts)){
  foreach ($floorScripts as $script) {
    echo "<script src='".base_url('assets')."/floorplanner/js/".$script."'></script>";
  }
}
?>
</head>

<body>

  <div class="container-fluid">
    <div class="row main-row">
      <!-- Left Column -->
      <div class="col-xs-3 sidebar">
        <!-- Main Navigation -->
        <ul class="nav nav-sidebar">
          <li id="floorplan_tab"><a href="#">
            Edit Floorplan
            <span class="glyphicon glyphicon-chevron-right pull-right"></span>
          </a></li>
          <li id="design_tab"><a href="#">
            Design
            <span class="glyphicon glyphicon-chevron-right pull-right"></span>
          </a></li>
          <li id="items_tab"><a href="#">
            Add Items
            <span class="glyphicon glyphicon-chevron-right pull-right"></span>
          </a></li>
		  <li id="items_tab"><a href="<?=  base_url("auth/logout")?>">
            Logout
            <span class="glyphicon glyphicon-chevron-right pull-right"></span>
          </a></li>
        </ul>
        <hr />

        <!-- Context Menu -->
        <div id="context-menu">
          <div style="margin: 0 20px">
            <span id="context-menu-name" class="lead"></span>
            <br /><br />
            <button class="btn btn-block btn-danger" id="context-menu-delete">
              <span class="glyphicon glyphicon-trash"></span>
              Delete Item
            </button>
            <br />
            <div class="panel panel-default">
              <div class="panel-heading">Adjust Size</div>
              <div class="panel-body" style="color: #333333">

                <div class="form form-horizontal" class="lead">
                  <div class="form-group">
                    <label class="col-sm-5 control-label">
                     Width
                   </label>
                   <div class="col-sm-6">
                    <input type="number" class="form-control" id="item-width">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-5 control-label">
                    Depth
                  </label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="item-depth">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-5 control-label">
                    Height
                  </label>
                  <div class="col-sm-6">
                    <input type="number" class="form-control" id="item-height">
                  </div>
                </div>
              </div>
              <small><span class="text-muted">Measurements in inches.</span></small>
            </div>
          </div>

          <label><input type="checkbox" id="fixed" /> Lock in place</label>
          <br /><br />
        </div>
      </div>

      <!-- Floor textures -->
      <div id="floorTexturesDiv" style="display:none; padding: 0 20px">
        <div class="panel panel-default">
          <div class="panel-heading">Adjust Floor</div>
          <div class="panel-body" style="color: #333333">

            <div class="col-sm-6" style="padding: 3px">
              <a href="#" class="thumbnail texture-select-thumbnail" texture-url="rooms/textures/light_fine_wood.jpg" texture-stretch="false" texture-scale="300">
                <img alt="Thumbnail light fine wood" src="<?=base_url()?>/assets/floorplanner/rooms/thumbnails/thumbnail_light_fine_wood.jpg" />
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Wall Textures -->
      <div id="wallTextures" style="display:none; padding: 0 20px">
        <div class="panel panel-primary">
          <div class="panel-heading">Adjust Wall</div>
          <!-- <?=var_dump($colors)?> -->
          <div class="panel-body">
            <select class="form-control" name="selectColor" id="changeColor">
              <option value="-1">Select color</option>
              <!-- <option value="red">Red</option>
              <option value="green">Green</option>
              <option value="yellow">Yellow</option>
              <option value="blue green">Blue Green</option> -->
              <?php foreach ($colors as $row):?>
                <option value="<?=strtolower($row['color'])?>"><?=$row['color']?></option>
              <?php endforeach;?>
            </select>
            <div class="img-list row">
            <?php foreach ($adjustWall as $row) :?>
                  <a href="#" class="col-sm-3 thumbnail texture-select-thumbnail" texture-url="<?=base_url('assets')?>/floorplanner/dulux/<?=$row['code']?>.png" texture-stretch="false" texture-scale="300">
                    <img alt="<?=$row['name']?>" src="<?=base_url('assets')?>/floorplanner/dulux/<?=$row['code']?>.png" />
                  </a>
            <?php endforeach;?>
            </div>
<center>
<a href="#" class="btn-md btn-info prev">Prev</a>
<a href="#" class="btn-md btn-primary next">Next</a>
</center>

            <script type="text/javascript">
       
              function init_paginate(){

              var start = 0;
              var nb = 8;
              var end = start + nb;
              var length = $('.img-list a').length;
              var list = $('.img-list a');
              console.log(length);
              list.hide().filter(':lt('+(end)+')').show();


              $('.prev, .next').click(function(e){
               e.preventDefault();
               console.log(start);
               if( $(this).hasClass('prev') ){
                 start -= nb;
               } else {
                 start += nb;
               }

               if( start < 0 || start >= length ) start = 0;
               end = start + nb;        
               console.log(start);
               console.log(end);

               if( start == 0 ) list.hide().filter(':lt('+(end)+')').show();
               else list.hide().filter(':lt('+(end)+'):gt('+(start-1)+')').show();
             });
              }
              $(document).ready(function(){init_paginate();});
              $('#changeColor').on('click',function(){
                var imgList=$('.img-list');
                imgList.empty();
                console.log($('#changeColor').val());
                var request = $.ajax({
                url: "<?=base_url()?>index.php/floorplanner/getImageByColor",
                method: "POST",
                data: {
                  color:$('#changeColor').val(),
                  <?php echo $this->security->get_csrf_token_name(); ?> : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: "text"
              });
              request.done(function( msg ) {
                var m=JSON.parse(msg);
                for(var i=0;i<m.data.length;i++){
                  var texture_url="<?=base_url()?>/assets/floorplanner/asianpaints/"+m.data[i]['code']+".png";
                  var a = document.createElement('a');
                  $(a).addClass("col-sm-3 thumbnail texture-select-thumbnail")
                      .attr("texture-url",texture_url)
                      .attr("texture-stretch","false")
                      .attr("texture-scale","300")
                      .html("<img alt='"+m.data[i]['product']+"' src='"+texture_url+"'/><p class='text-muted'>"+m.data[i]['code']+"</p>")
                      .appendTo($(".img-list"));
                }
                init_paginate();
              });
              });
            </script>

          </div>
        </div>
      </div>
    </div>

    <!-- Right Column -->
    <div class="col-xs-9 main">

      <!-- 3D Viewer -->
      <div id="viewer">

        <div id="main-controls">
          <a href="#" class="btn btn-default btn-sm" id="new">
            New Plan
          </a>
          <a href="#" class="btn btn-default btn-sm" id="saveFile">
            Save Plan
          </a>
          <a class="btn btn-sm btn-default btn-file">
           <input type="file" class="hidden-input" id="loadFile">
           Load Plan
         </a>
       </div>

       <div id="camera-controls">
        <a href="#" class="btn btn-default bottom" id="zoom-out">
          <span class="glyphicon glyphicon-zoom-out"></span>
        </a>
        <a href="#" class="btn btn-default bottom" id="reset-view">
          <span class="glyphicon glyphicon glyphicon-home"></span>
        </a>
        <a href="#" class="btn btn-default bottom" id="zoom-in">
          <span class="glyphicon glyphicon-zoom-in"></span>
        </a>

        <span>&nbsp;</span>

        <a class="btn btn-default bottom" href="#" id="move-left" >
          <span class="glyphicon glyphicon-arrow-left"></span>
        </a>
        <span class="btn-group-vertical">
          <a class="btn btn-default" href="#" id="move-up">
            <span class="glyphicon glyphicon-arrow-up"></span>
          </a>
          <a class="btn btn-default" href="#" id="move-down">
            <span class="glyphicon glyphicon-arrow-down"></span>
          </a>
        </span>
        <a class="btn btn-default bottom" href="#" id="move-right" >
          <span class="glyphicon glyphicon-arrow-right"></span>
        </a>
      </div>

      <div id="loading-modal">
        <h1>Loading...</h1>
      </div>
    </div>

    <!-- 2D Floorplanner -->
    <div id="floorplanner">
      <canvas id="floorplanner-canvas"></canvas>
      <div id="floorplanner-controls">

        <button id="move" class="btn btn-sm btn-default">
          <span class="glyphicon glyphicon-move"></span>
          Move Walls
        </button>
        <button id="draw" class="btn btn-sm btn-default">
          <span class="glyphicon glyphicon-pencil"></span>
          Draw Walls
        </button>
        <button id="delete" class="btn btn-sm btn-default">
          <span class="glyphicon glyphicon-remove"></span>
          Delete Walls
        </button>
        <span class="pull-right">
          <button class="btn btn-primary btn-sm" id="update-floorplan">Done &raquo;</button>
        </span>

      </div>
      <div id="draw-walls-hint">
        Press the "Esc" key to stop drawing walls
      </div>
    </div>

    <!-- Add Items -->
    <div id="add-items">
      <div class="row" id="items-wrapper">

        <!-- Items added here by items.js -->
      </div>
    </div>

  </div>
  <!-- End Right Column -->
</div>
</div>
