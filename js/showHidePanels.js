// Smooth scroll to top when back to top button is clicked
$(document).ready(function(){
  $("#backToTop").click(function(){
    $('body,html').animate({
        scrollTop:0 }, 200);
        return false;
  });
});

// Adds new entry when Add New Book form is submitted
$(document).ready(function(){
  $("#addForm").submit(function(event){

    // Prevent form from being submitted normally
    event.preventDefault();

    var $form = $(this);
    var url = $form.attr('action');
    var forClassVal, rereadVal;

    if($('#forClassYesAdd').is(':checked')){
      forClassVal = "1";
    }

    if($('#forClassNoAdd').is(':checked')){
      forClassVal = "0";
    }

    if($('#rereadYesAdd').is(':checked')){
      rereadVal = "1";
    }

    if($('#rereadNoAdd').is(':checked')){
      rereadVal  = "0";
    }

    var data = $.post(url, {title: $("input[name=title]").val(),
    author: $("input[name=author]").val(), yearRead: $("input[name=yearRead]").val(),
    yearPub: $("input[name=yearPub").val(), numPgs: $("input[name=numPgs]").val(),
    forClass: forClassVal, reread: rereadVal});

    data.done(function(response){
      console.log(response);
      $("#addResponse").show();

      if(response == "Success"){
        $("#addPanel").hide();
        $("#addResponse").text("Sucessfully added " + $("input[name=title]").val() + "!");
        $("#addResponse").delay(1000).fadeOut('slow');
        $("#addForm")[0].reset();
      }else{
        $("#addResponse").text("Add failed! " + response);
      }


    });


  });

});

// Show add panel when add book button is clicked
$(document).ready(function(){
  $("#addBtn").click(function(){
    $("#addPanel").slideToggle();
    $("#addResponse").text("");
  });
});

//Hide add panel when cancel button is clicked
$(document).ready(function(){
  $("#cancelAddBtn").click(function(){
    $("#addPanel").slideUp();
    $("#addResponse").text("");
  });
});

// Stores ID of selected entry
var entryId;

// Show update window and data of selected entry when edit button is clicked
$(document).ready(function(){
  $(".fa-edit").click(function(){
    entryId = $(this).attr("value");

    // Reset update panel
    $("#updatePanel").css("display", "block");
    $("#updateFailed").text("");
    $("#updatePanel").css({"max-height": "520px", "margin": "100px auto"});

    //console.log("id=" + entryId);
    $.ajax({
      type: 'POST',
      url: 'php/displayUpdate.php',
      data: {id: entryId},
      success: function(response){
        console.log("response: " + response);
        var entryData = JSON.parse(response);

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

      }// end success func

    })

    $('#updateOverlay').slideDown(300);
  });

});

// Update selected entry
$(document).ready(function(){
  $("#updateForm").submit(function(event){
    console.log("val: " + entryId);

    // Prevent form from being submitted normally
    event.preventDefault();

    var $form = $(this);
    var url = $form.attr('action');
    var forClassVal, rereadVal;

    if($('#forClassYes').is(':checked')){
      forClassVal = "1";
    }

    if($('#forClassNo').is(':checked')){
      forClassVal = "0";
    }

    if($('#rereadYes').is(':checked')){
      rereadVal = "1";
    }

    if($('#rereadNo').is(':checked')){
      rereadVal  = "0";
    }

    var data = $.post(url, { id: entryId, title: $('#titleUpdate').val() ,
    author: $('#authorUpdate').val(), yearRead: $('#yearReadUpdate').val(),
    yearPub: $('#yearPubUpdate').val(), numPgs: $('#numPgsUpdate').val(),
    forClass: forClassVal, reread: rereadVal});

    data.done(function(response){
      console.log(response);

      if(response == "Success"){
        $("#updatePanel").css("display", "none");
        //$("#updateResponse").html("<div class = 'updateIcons'><i class='far fa-window-close'></i></div> Successfully updated!");
        $("#updateSuccessPanel").fadeIn(300);
      }else{
        $("#updatePanel").css({"max-height": "550px", "margin": "80px auto"});
        $("#updateFailed").text("Update failed! " + response);
      }
    });


  });

});

//Close update window when cancel button is clicked
$(document).ready(function(){
  $("#cancelUpdateBtn").click(function(){
    $('#updateOverlay').slideUp(300);
  });

});

//Close update window when updated successfully and "x" is clicked
$(document).ready(function(){
  $("#closeUpdateBtn").click(function(){
    $('#updateOverlay').fadeOut(300);
    $("#updateSuccessPanel").css("display", "none");
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
