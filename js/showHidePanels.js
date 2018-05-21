// Smooth scroll to top when back to top button is clicked
$(document).ready(function(){
  $("#backToTop").click(function(){
    $('body,html').animate({
        scrollTop:0 }, 200);
        return false;
  });
});

var sortState; // stores currently selected sorting option
var orderState; // stores whether sorting is ascending or descending

// Fetch the data from the database
function getData(sortOption = "none", order = "none"){

  console.log("sortOption: " + sortOption);
  console.log("sortOrder: " + order);
  $("#dataTable").html("");

  $.ajax({
    url: 'php/display.php',
    data: {sortMenu: sortOption, order: order},
    type: 'get',
    success: function(response){
      if (response == "404"){
        $("#dataTable").html("Error displaying book entries.");
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

    $.ajax({
      url: 'php/create.php',
      type: 'POST',
      data: {title: $("input[name=title]").val(),
      authorFirst: $("input[name=authorFirst]").val(),
      authorLast: $("input[name=authorLast]").val(),
      yearRead: $("input[name=yearRead]").val(),
      yearPub: $("input[name=yearPub").val(),
      numPgs: $("input[name=numPgs]").val(), forClass: forClassVal,
      reread: rereadVal},
      success: function(response){
        $("#addResponse").show();

        if(response == "200"){
          $("#addPanel").hide();
          $("#addResponse").text("Added " + $("input[name=title]").val() + "!");
            /*+ " by "
            + $("input[name=authorFirst]").val() + " "
            + $("input[name=authorLast]").val() + "!");*/
          $("#addResponse").delay(3000).fadeOut('5000');
          $("#addForm")[0].reset();
          getData(sortState, orderState);
        }else{
          $("#addResponse").text("Add failed! " + response);
        }

      }
    }); //end ajax
  }); // end submit add form
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

  $("#updatePanel").show();

  // Reset update panel
  $("#updateFailed").text(""); // clears any previous error messages
  $("#updatePanel").css({"max-height": "600px"}); // reset height of update form
  $("#updateSuccessPanel").css("display", "none"); // hide sucess panel
  $('#updateForm')[0].reset(); //reset form
  $('#updateOverlay').scrollTop(0); // reset scroll bar to top

  $.ajax({
    type: 'GET',
    url: 'php/displayUpdate.php',
    data: {id: entryId},
    success: function(response){
      //console.log("response: " + response);

      if(response == "404"){
        console.log("Cannot find selected entry in database.")
      }else{
        var entryData = JSON.parse(response);

        $('#titleUpdate').attr('value', entryData[0].title);
        $('#authorFirstUpdate').attr('value', entryData[0].authorFirst);
        $('#authorLastUpdate').attr('value', entryData[0].authorLast);
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
      } //end if
    }// end success func
  }) //end ajax

  $('#updateOverlay').slideDown(300);

});

// Update selected entry when update form is submitted
$(document).ready(function(){
  $("#updateForm").submit(function(event){
    //console.log("val: " + entryId);

    // Prevent form from being submitted normally
    event.preventDefault();

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

    $.ajax({
      url: 'php/update.php',
      type: 'POST',
      data : { id: entryId, title: $('#titleUpdate').val() ,
        authorFirst: $('#authorFirstUpdate').val(),
        authorLast: $('#authorLastUpdate').val(),
        yearRead: $('#yearReadUpdate').val(), yearPub: $('#yearPubUpdate').val(),
        numPgs: $('#numPgsUpdate').val(),
        forClass: forClassVal, reread: rereadVal},
      success: function(response){
        if(response == "200"){
          $("#updatePanel").css("display", "none");
          //$("#updateResponse").html("<div class = 'updateIcons'>
          // <i class='far fa-window-close'></i></div> Successfully updated!");
          $("#updateSuccessPanel").fadeIn(300);

          $('#updateOverlay').delay(1000).fadeOut(500);
          setTimeout(function(){getData(sortState, orderState);}, 1500);
        }else if (response == "404"){
            $("#updatePanel").css({"max-height": "620px", "margin": "50px auto"});
            $("#updateFailed").text("Unable to update entry.");
        }else{
          $("#updatePanel").css({"max-height": "620px", "margin": "50px auto"});
          $("#updateFailed").text("No changes have been made!");
        }
      } // end success func

    }); //end ajax
  }); // end update form submit
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
    console.log("id: " + entryId);
    // Prevent form from being submitted normally
    event.preventDefault();

    $.ajax({
      url: 'php/delete.php',
      data: {id: entryId},
      type: 'POST',
      success: function(response){
        console.log(response);
        if(response == "200"){
          $("#deletePanel").hide();
          $("#deleteResponsePanel").fadeIn(300);

          $("#deleteOverlay").delay(1000).fadeOut(500);
          setTimeout(function(){getData(sortState, orderState);}, 1500);
        }else{
          $("#deletePanel").hide();
          $("#deleteResponsePanel").fadeIn(300);
          $("#deleteResponsePanel").text("Deleted failed.");

          $("#deleteOverlay").delay(1000).fadeOut(500);
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


/**************************** Sorting **************************************/
//  Toggle sort button for Order Added (change button style + add icons)
function toggleSortBtn(btnLabel, innerTxt, isBool){

  var upArrow = ' <i class="fas fa-sort-up"></i>';
  var downArrow = " <i class='fas fa-sort-down'></i>";

  var yes = ' <i class="fas fa-check"></i>';
  var no = ' <i class="fas fa-times"></i>';

  var sortOrder; // return value, either ascending or descending

  // Determine ascending + descending icons depending on sort type
  var ascendIcon, descendIcon;
  if (isBool){
    ascendIcon = yes;
    descendIcon = no;
  }else{
    ascendIcon = upArrow;
    descendIcon = downArrow;
  }

  // Deselect the other buttons
  $(".sortBtnClick").addClass('sortBtn');
  $(".sortBtnClick").removeClass('sortBtnClick');

  // Change current button to active style
  if (!$(btnLabel).hasClass("sortBtnClick")){
    $(btnLabel).addClass("sortBtnClick");
    $(btnLabel).removeClass("sortBtn");
  }

  // Remove sort icons from the other buttons (default text)
  var sortBtnList = document.getElementsByClassName("sortBtn");
  for(var i = 0; i < sortBtnList.length; i++){
    sortBtnList[i].innerText = sortBtnList[i].innerText.split('<')[0].trim();
  }

  //console.log("start");
  //console.log("html: " + $(btnLabel).html());
  //console.log("has active class: " + $(btnLabel).hasClass("sortBtnClick"));

  // Set button active color + ascending/ descending icon
  // Inactive to Ascending
  if ($(btnLabel).html() == innerTxt  ){ //|| $(btnLabel).html() == (innerTxt + " ")
    console.log("set to ascending");
    $(btnLabel).html(innerTxt + ascendIcon);

    if(ascendIcon == upArrow){
      sortOrder = "ascend";
    }else{
      sortOrder = "yes";
    }

  // Ascending to descending
  }else if ($(btnLabel).html() == innerTxt + ascendIcon){
    console.log("set to descending");
    $(btnLabel).html(innerTxt + descendIcon);

    if(descendIcon == downArrow){
      sortOrder = "descend";
    }else{
      sortOrder = "no";
    }

  // Descending back to inactive
  }else {
    console.log("back to default");
    $(btnLabel).html(innerTxt);
    $(btnLabel).removeClass("sortBtnClick");
    $(btnLabel).addClass("sortBtn");
    sortOrder = "none";
  }

  return sortOrder;
  //console.log("end");

}

// sort Order Added
$(document).ready(function(){
  $("#sortOrder").click(function(){
    sortState = "orderAdded";
    orderState = toggleSortBtn("#sortOrder", "Order Added", 0);

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }

  });
});

// sort Title
$(document).ready(function(){
  $("#sortTitle").click(function(){
    sortState = "title";
    orderState = toggleSortBtn("#sortTitle", "Title", 0);

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }
  });
});

// sort Author
$(document).ready(function(){
  $("#sortAuthor").click(function(){
    sortState = "author";
    orderState = toggleSortBtn("#sortAuthor", "Author", 0);

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }
  });
});

// sort Year Read
$(document).ready(function(){
  $("#sortYearRead").click(function(){
    sortState = "yearRead";
    orderState = toggleSortBtn("#sortYearRead", "Year Read", 0);

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }
  });
});

// sort Year Read
$(document).ready(function(){
  $("#sortYearPub").click(function(){
    sortState = "yearPub";
    orderState = toggleSortBtn("#sortYearPub", "Year Published", 0);

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }
  });
});

// orderState Num Pgs
$(document).ready(function(){
  $("#sortNumPgs").click(function(){
    sortState = "numPgs";
    orderState = toggleSortBtn("#sortNumPgs", "Number of Pages", 0);

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }
  });
});

// sort For Class
$(document).ready(function(){
  $("#sortForClass").click(function(){
    sortState = "forClass";
    orderState = toggleSortBtn("#sortForClass", "Read for Class", 1);

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }
  });
});

// sort Reread
$(document).ready(function(){
  $("#sortReread").click(function(){
    sortState = "reread";
    orderState = toggleSortBtn("#sortReread", "Reread", 1);

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }
  });
});
