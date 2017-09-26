<?php
require_once('../../../../include/start.php');
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  font-family: Verdana, sans-serif;
  margin: 0;
}

* {
  box-sizing: border-box;
}

.row > .column {
  padding: 0 8px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.column {
  float: left;
  width: 25%;
}

/* The Modal (background) */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: black;
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  width: 90%;
  max-width: 1200px;
}

/* The Close Button */
.close {
  color: white;
  position: absolute;
  top: 10px;
  right: 25px;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #999;
  text-decoration: none;
  cursor: pointer;
}

.mySlides {
  display: none;
}

.cursor {
  cursor: pointer
}


img {
  margin-bottom: -4px;
}



.demo {
  opacity: 0.6;
}

.active,
.demo:hover {
  opacity: 1;
}

img.hover-shadow {
  transition: 0.3s
}

.hover-shadow:hover {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
}

</style>
<body>

<h2 style="text-align:center">Lightbox</h2>

<div class="row">
<?php 

$product = Product::find_by_ID(11);
$pictures = Picture::getPicturesByProduct($product->ID);



if (!empty($pictures)) : foreach ($pictures as $key => $pic) : ?> 
    <?php echo $key % 3 == 0 ? '<div class="clearfix"></div><br>' : '' ?> 
    <div class="col-xs-4"> 
        <img src="<?php echo makePictureLink($pic) . DS . PICTURE_THUMB . DS . $pic->name; ?>" style="width:100%" class="img-fluid img-thumbnail fixedSize hover-shadow cursor" onclick="openModal()">
    </div> 
<?php endforeach; endif;



  if (!empty($pictures)) : foreach ($pictures as $key => $pic) :
              if($key==0){
                  echo '<div id="lightbox" class="column">';
                    echo '<img src="'.makePictureLink($pic) . $pic->name.'" style="width:100%" class="img-thumbnail" onclick="openModal()" ';
                  echo '</div>';
              }else{
                  echo '<div class="col-lg-2 col-md-4 col-xs-6 column">';
                      echo '<img src="'.makePictureLink($pic) . DS . PICTURE_THUMB . DS . $pic->name.'" style="width:100%" class="img-fluid img-thumbnail fixedSize hover-shadow cursor" onclick="openModal()">';
                  echo '</div>';
              }
  endforeach; endif;
?>
</div>



<div id="myModal" class="modal">
  <span class="close cursor" onclick="closeModal()">&times;</span>
  <div class="modal-content">

  </div>
</div>

<script>
function openModal() {
  document.getElementById('myModal').style.display = "block";
}

function closeModal() {
  document.getElementById('myModal').style.display = "none";
}

</script>
    
</body>
</html>
