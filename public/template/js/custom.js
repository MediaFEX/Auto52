/**
 * Created by andrus.jakobson on 14.03.2017.
 * This is Aut52
 */


var admin_url = "http://ubuntu.ametikool.ee/~TAK15_Jakobson/BackupAuto52/public/admin/";

$(".delete-confirm").click(function () {
    var title = "Are you sure?";
    var text = "You will not be able to recover this.";
    var confirmButtonText = "Yes, delete it!";

    var heading_1 = "Deleted";
    var confirm_text = "Your imaginary file has been deleted.";

    var ID = $(this).data("delete-id");
    var rights= $(this).data("rights");
    var url = $(this).data("url");

    var closestTr = $(this).closest("tr");

    swal({
        title: title,
        text: text,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: confirmButtonText,
        closeOnConfirm: false
    },
    function(){
        $.ajax({
            method: "POST",
            url: admin_url + url + ".php?ID=" + ID + "&?rights=" + rights
        }) 
        .done(function( data ) { 
            if(data == '') { 
                closestTr.remove(); 
                swal(heading_1, confirm_text, "success"); 
            } else if(rights!='admin') { 
                url: admin_url + "logout.php?delete=1";
            } else{
                swal("ERROR", data, "error"); 
            }

        });
    });
});



$(".delete-picture").click(function () { 
    var ID = $(this).data("picture-id"); 

    $.ajax({ 
        method: "POST", 
        data: {ID: ID}, 
        url: admin_url + "pages/pictures/delete.php" 
    }) 
        .done(function( data ) { 
            if(data == '') { 
                $("#picture_" + ID).remove(); 
                swal("Deleted", "Your picture has been deleted.", "success"); 
            } else { 
                swal("ERROR", data, "error"); 
            } 

        }); 
}); 

$(".set-main-picture").click(function () { 
    var name = $(this).data("picture-name"); 
    var ID = $(this).data("product-id"); 

    $.ajax({ 
        method: "POST", 
        data: {name: name, ID: ID}, 
        url: admin_url + "pages/pictures/set-to-main.php" 
    }) 
        .done(function( data ) { 
            if(data == '') { 
                swal("Default selected", "Your picture has been set as the thumbnail.", "success"); 
            } else { 
                swal("ERROR", data, "error"); 
            } 

        }); 
}); 

$(".make-default-lang").click(function () { 
    var lang = $(this).data("lang"); 
    console.log(lang); 

    $.ajax({ 
        method: "POST", 
        data: {lang: lang}, 
        url: admin_url + "pages/language/set-main-language.php" 
    }) 
        .done(function( data ) { 
            if(data == '') { 
                swal("Success", "Default language changed", "success"); 
                $(".confirm").click(function () { 
                    console.log("test"); 
                    location.reload(); 
                }); 
            } else { 
                swal("ERROR", data, "error"); 
            } 

        }); 
});

function openModal() {
  document.getElementById('myModal').style.display = "block";
}

function closeModal() {
  document.getElementById('myModal').style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}