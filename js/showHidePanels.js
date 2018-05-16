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
    var entryId = $(this).attr("value");
    console.log("id=" + entryId);
    $.ajax({
      type: 'POST',
      url: 'php/update.php',
      data: {id: entryId},
      success: function(response){
        var entryData = JSON.parse(response);
        console.log(entryData);

        $('#titleUpdate').attr('value', entryData[0].title);
        $('#authorUpdate').attr('value', entryData[0].author);
        $('#yearReadUpdate').attr('value', entryData[0].yearRead);
        $('#yearPubUpdate').attr('value', entryData[0].yearPub);
        $('#numPgsUpdate').attr('value', entryData[0].numPgs);

        if(entryData[0].forClass == 1){
          //console.log("forClass= yes");
          $('#forClassNo').removeAttr("checked");
          $('#forClassYes').attr('checked', "true");
        }else if (entryData[0].forClass == 0){
          //console.log("forClass= no");
          $('#forClassYes').removeAttr("checked");
          $('#forClassNo').attr('checked', "true");
        }else{
          //console.log("forClass= not selected");
          $('#forClassYes').removeAttr("checked");
          $('#forClassNo').removeAttr("checked");

        }

        if(entryData[0].reread == 1){
          //console.log("reread= yes");
          $('#rereadNo').removeAttr("checked");
          $('#rereadYes').attr('checked', "true");
        }else if (entryData[0].reread == 0){
          //console.log("reread = no");
          $('#rereadYes').removeAttr("checked");
          $('#rereadNo').attr('checked', "true");
        }else{
          //console.log("reread= not selected");
          $('#rereadYes').removeAttr("checked");
          $('#rereadNo').removeAttr("checked");
        }

      }

    })

    $('#updateOverlay').slideDown(300);
  });

});

/*
// Update entry using user input
$(function updateEntry(){
  var updateForm = $('#updateForm');
  var updateResponse = $('#updateResponse');

  $(updateForm).submit(function(event){

    // Stop browser from submitting form
    event.preventDefault();

    // Converts user input into AJAX request compatible format
    var formData = $(updateForm).serialize();

    $.ajax({
      type: 'POST',
      url: $(form).attr('action'),
      data: formData;

    })

  });

});*/

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
