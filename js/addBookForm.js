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
