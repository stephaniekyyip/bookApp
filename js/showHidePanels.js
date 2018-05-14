// Smooth scroll to top when back to top button is clicked
$(document).ready(function(){
  $("#backToTop").click(function(){
    $('body,html').animate({
        scrollTop:0 }, 200);
        return false;
  });
});

// Show add panel when add book button is clicked
$(document).ready(function(){
  $("#addBtn").click(function(){
    $("#addPanel").slideToggle();
    $("#addResponse").hide();
  });
});

//Hide add panel when cancel button is clicked
$(document).ready(function(){
  $("#cancelBtn").click(function(){
    $("#addPanel").slideUp();
  });
});

// Show update window when edit button is clicked
$(document).ready(function(){
  $(".fa-edit").click(function(){
    $('#updateOverlay').slideDown(300);
  });

});

//Close update window when cancel button is clicked
$(document).ready(function(){
  $("#cancelUpdateBtn").click(function(){
    $('#updateOverlay').slideUp(300);
    //$('#updatePanel').css({"visibility":"hidden","display":"none"});
  });

});

// Show delete window when delete button is clicked
$(document).ready(function(){
  $(".fa-trash-alt").click(function(){
    $('#deleteOverlay').slideDown(300);
  });

});

//Close delete window when cancel button is clicked
$(document).ready(function(){
  $("#cancelDeleteBtn").click(function(){
    $('#deleteOverlay').slideUp(300);
  });

});


$(document).ready(function(){
  $("#sortMenu").change(function(){
    var select = document.getElementById("sortMenu").options.selectedIndex;

    /*
    $.ajax({

      url: 'php/sortDisplay.php',
      type: 'post',
      data: {'sortSelect': select},
      success: function(data, status){

      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }

    }); //end ajax
    */
  });
});
