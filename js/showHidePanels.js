// Smooth scroll to top when back to top button is clicked
$(document).ready(function(){
  $("#backToTop").click(function(){
    $('body,html').animate({
        scrollTop:0 }, 200);
        return false;
  });
});

// Fetch the data from the database
function getData(){
  var sortOption = "none"; //UPDATE THIS LATER
  $("#dataTable").html("");

  $.ajax({
    url: 'php/display.php',
    data: {sortMenu: sortOption},
    type: 'post',
    success: function(response){
      if (response == "FAILED"){
        console.log(response);
      }else{
        $("#dataTable").fadeOut(10);
        $("#dataTable").fadeIn(500);
        $("#dataTable").html(response);
      }
    }
  });
}

// Display data fetched from database
$(document).ready(function(){
  getData();
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
        $("#addResponse").text("Added " + $("input[name=title]").val() + " by " + $("input[name=author]").val() + "!");
        $("#addResponse").delay(3000).fadeOut('5000');
        $("#addForm")[0].reset();
        getData();
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
$(document).on("click",".fa-edit", function(){

  //Get ID of selected entry to query DB
  entryId = $(this).attr("value");

  // Reset update panel
  $("#updatePanel").show();
  $("#updateFailed").text("");
  $("#updatePanel").css({"max-height": "520px", "margin": "100px auto"});
  $("#updateSuccessPanel").css("display", "none");
  $('#updateForm')[0].reset(); //reset form

  //console.log("id=" + entryId);
  $.ajax({
    type: 'POST',
    url: 'php/displayUpdate.php',
    data: {id: entryId},
    success: function(response){
      //console.log("response: " + response);
      var entryData = JSON.parse(response);

      $('#titleUpdate').attr('value', entryData[0].title);
      $('#authorUpdate').attr('value', entryData[0].author);
      $('#yearReadUpdate').attr('value', entryData[0].yearRead);
      $('#yearPubUpdate').attr('value', entryData[0].yearPub);
      $('#numPgsUpdate').attr('value', entryData[0].numPgs);

      if(entryData[0].forClass == 1){
        console.log("forClass= yes");
        $('#forClassNo').removeAttr("checked");
        $('#forClassYes').attr('checked', "true");
      }else if (entryData[0].forClass == 0){
        console.log("forClass= no");
        $('#forClassYes').removeAttr("checked");
        $('#forClassNo').attr('checked', "true");
      }else{
        console.log("forClass= not selected");
        $('#forClassYes').removeAttr("checked");
        $('#forClassNo').removeAttr("checked");

      }

      if(entryData[0].reread == 1){
        console.log("reread= yes");
        $('#rereadNo').removeAttr("checked");
        $('#rereadYes').attr('checked', "true");
      }else if (entryData[0].reread == 0){
        console.log("reread = no");
        $('#rereadYes').removeAttr("checked");
        $('#rereadNo').attr('checked', "true");
      }else{
        console.log("reread= not selected");
        $('#rereadYes').removeAttr("checked");
        $('#rereadNo').removeAttr("checked");
      }

    }// end success func

  }) //end ajax

  $('#updateOverlay').slideDown(300);

});

// Update selected entry when update form is submitted
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

        $('#updateOverlay').delay(1000).fadeOut(500);
        setTimeout(getData, 1500);
      }else{
        $("#updatePanel").css({"max-height": "550px", "margin": "80px auto"});
        $("#updateFailed").text(response);
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
/*$(document).ready(function(){
  $("#closeUpdateBtn").click(function(){
    $('#updateOverlay').fadeOut(300);
    $("#updateSuccessPanel").css("display", "none");
    getData();
  });

});*/

// Show delete window when delete button is clicked
$(document).on("click", ".fa-trash-alt", function(){
  entryId = $(this).attr("value");

  $("#deletePanel").show();
  $("#deleteResponsePanel").hide();
  $('#deleteOverlay').slideDown(300);
});

// Deletes selected entry from the database when delete form is submitted
$(document).ready(function(){
  $("#deleteForm").submit(function(event){

    // Prevent form from being submitted normally
    event.preventDefault();

    $.ajax({
      url: 'php/delete.php',
      data: {id: entryId},
      type: 'post',
      success: function(response){
        if(response == "Failed"){
          console.log(response);
        }else{
          console.log(response);
          $("#deletePanel").hide();
          $("#deleteResponsePanel").fadeIn(300);

          $("#deleteOverlay").delay(1000).fadeOut(500);
          setTimeout(getData, 1500);
        }
      }

    }); //end ajax

  });
});

// Close delete window after successful deletion and "x" is clicked
/*$(document).ready(function(){
  $("#closeDeleteBtn").click(function(){
    $("#deleteOverlay").fadeOut(300);
    $("#deleteResponsePanel").hide();
    getData();
  });
});*/

//Close delete window when cancel button is clicked
$(document).ready(function(){
  $("#cancelDeleteBtn").click(function(){
    $('#deleteOverlay').slideUp(300);
  });

});
