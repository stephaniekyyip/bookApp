/******************************* Scrolling ************************************/
// Smooth scroll to top when back to top button is clicked
$(document).ready(function(){
  $("#scrollBtn").click(function(){
    $('body,html').animate({
        scrollTop:0 }, 200);
        return false;
  });
});

// Scroll to top button fades in when user scrolls down from the top of the
// page and fades out when users scrolls to the top of the page
function scrollBtnFade(){
  var ypos = document.body.scrollTop;

  // User scrolls to bottom of page
  if (document.body.scrollHeight == document.body.scrollTop + window.innerHeight){

  // User scrolls past top of page
  }else if(ypos > 100 ){
      $("#scrollBtn").fadeIn(300);

  // User at top of page
  }else{
    $("#scrollBtn").fadeOut(300);
  }
}
window.addEventListener("wheel", scrollBtnFade);
window.addEventListener("scroll", scrollBtnFade);

/**************************** CRUD Operations *********************************/
var entryId; // Stores ID of selected entry (used in Update and Delete)

var sortState; // stores currently selected sorting option
var orderState; // stores whether sorting is ascending or descending

// Formats errors
function printErr(dbErr){
  var errors = JSON.parse(dbErr);
  var errFormat = "";

  // Converts json object to array if necessary
  if(!$.isArray(errors)){
    errors = [errors];
  }

  // Prints errors in <ul>
  errFormat += "Error(s): <ul>";
  $.each(errors, function(i, item){
    errFormat += "<li>" + item + "</li>";
  });
  errFormat += "</ul>";

  return errFormat;
}

/****************************** Read ******************************************/
// Fetches the data from the database
function getData(sortOption = "none", order = "none"){
  $("#dataTable").html("");

  $.ajax({
    url: 'php/Books/readAll.php',
    data: {sortMenu: sortOption, order: order},
    type: 'get',
    success: function(response){
      console.log(response);
      if (response == "404"){
        $("#dataTable").html("Error displaying book entries.");
      }else if(response == "none"){
        $("#dataTable").html("No books here <i class=\"far fa-frown\"></i>");
      }else{
        $("#dataTable").css('visibility', 'hidden');
        $("#dataTable").delay(10).css('visibility', 'visible').hide().fadeIn(500);

        var table = printData(response);

        $("#dataTable").html(table);
      }// end else (response != error)
    }//end success
  });
}

// Gets entries from DB and formats them according to the selected sorting option
function printData(dbData){
  var jsonData = JSON.parse(dbData);
  var table = ""; // Holds the html to display all data entries

  // Converts json object to array if necessary
  if(!$.isArray(jsonData)){
    jsonData = [jsonData];
  }

  // Create HTML for each entry
  $.each(jsonData, function(i, item){
    // First line of entry: Year Read, edit icon, delete icon
    table += "<div class = \"year\"> Read in " + item.yearRead
      + "<span class = \"updateIcons\"><i class=\"fas fa-edit\" value = \""
      + item.id + "\" title = \"Edit\"></i> <i class=\"fas fa-trash-alt\" value = \""
      + item.id + "\" title = \"Delete\"></i></span></div>";

    // Second line: Book title, author first and last name
    table += "<div class = \"titleAuthor\">"
      + item.title + " by " + item.authorFirst + " "
      +  item.authorLast + "</div>";

    // Rest of entry (optional fields):
    table += "<div class = \"bookInfo\">";

    // Year published
    if(item.yearPub !== null){
      table += "Published in " + item.yearPub + "<br>";
    }
    // Number of pages
    if(item.numPgs !== null){
      table += item.numPgs + " pages <br>";
    }
    // For Class
    if(item.forClass !== null){
      if(item.forClass == "1"){
        table += "Read for class <i class=\"fas fa-check\"></i><br>";
      }else{
        table += "Read for class <i class=\"fas fa-times\"></i><br>";
      }
    }
    // Reread
    if(item.reread !== null){
      if(item.reread == "1"){
        table += "Reread <i class=\"fas fa-check\"></i>";
      }else{
        table += "Reread <i class=\"fas fa-times\"></i>";
      }
    }
    // Divider line at end of entry
    table += "</div><div class = \"line\"></div>";
  });

  return table;
}

// Display data fetched from database on page load
$(document).ready(function(){
  getData();
});

/****************************** Create ****************************************/

// Adds new entry when Add New Book form is submitted
$(document).ready(function(){
  $("#addForm").submit(function(event){

    // Prevent form from being submitted normally
    event.preventDefault();

    var forClassVal, rereadVal;

    // Change For Class + Reread values to bool
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
      url: 'php/Books/create.php',
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
          // Display success msg + refresh data from DB
          $("#addPanel").hide();
          $("#addResponse").text("Added " + $("input[name=title]").val() + "!");
          $("#addResponse").delay(2000).fadeOut('5000');
          $("#addForm")[0].reset();
          getData(sortState, orderState);
        }else{
          $("#addResponse").text("Add failed! " + printErr(response));
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

/***************************** Update *****************************************/

// Show update window and data of selected entry when edit button is clicked
$(document).on("click",".fa-edit", function(){

  //Get ID of selected entry to query DB
  entryId = $(this).attr("value");

  // Reset update panel
  $("#updateFailed").text(""); // clears any previous error messages
  $("#updatePanel").css({"max-height": "600px"}); // reset height of update form
  //due to error message
  $("#updateSuccessPanel").css("display", "none"); // hide sucesss panel
  $('#updateForm')[0].reset(); //reset form
  $('#updateOverlay').scrollTop(0); // reset scroll bar to top

  $.ajax({
    type: 'GET',
    url: 'php/Books/readOne.php',
    data: {id: entryId},
    success: function(response){
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
          $('#forClassNo').removeAttr("checked");
          $('#forClassYes').attr('checked', "true");
        }else if (entryData[0].forClass == 0){
          $('#forClassYes').removeAttr("checked");
          $('#forClassNo').attr('checked', "true");
        }else{
          $('#forClassYes').removeAttr("checked");
          $('#forClassNo').removeAttr("checked");
        }

        if(entryData[0].reread == 1){
          $('#rereadNo').removeAttr("checked");
          $('#rereadYes').attr('checked', "true");
        }else if (entryData[0].reread == 0){
          $('#rereadYes').removeAttr("checked");
          $('#rereadNo').attr('checked', "true");
        }else{
          $('#rereadYes').removeAttr("checked");
          $('#rereadNo').removeAttr("checked");
        }
      } //end if
    }// end success func
  }) //end ajax

  $('#updateOverlay').slideDown(300);
  $("#updatePanel").show();

});

// Update selected entry when update form is submitted
$(document).ready(function(){
  $("#updateForm").submit(function(event){

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
      url: 'php/Books/update.php',
      type: 'post',
      data : { id: entryId, title: $('#titleUpdate').val() ,
        authorFirst: $('#authorFirstUpdate').val(),
        authorLast: $('#authorLastUpdate').val(),
        yearRead: $('#yearReadUpdate').val(), yearPub: $('#yearPubUpdate').val(),
        numPgs: $('#numPgsUpdate').val(),
        forClass: forClassVal, reread: rereadVal},
      success: function(response){
        if(response == "200"){
          $("#updatePanel").css("display", "none"); // hide update form
          $("#updateSuccessPanel").fadeIn(300); // show update success panel
          $('#updateOverlay').delay(1000).fadeOut(500); // fade out update panel

          // update entries displayed
          setTimeout(function(){getData(sortState, orderState);}, 1500);
        }else if (response == "404"){
          //change size of update panel to add error message
          $("#updatePanel").css({"max-height": "620px", "margin": "50px auto"});
          $("#updateFailed").text("Unable to update entry.");
        }else{
          //change size of update panel to add "no changes" message
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

/***************************** Delete *****************************************/

// Show delete window when delete button is clicked
$(document).on("click", ".fa-trash-alt", function(){
  entryId = $(this).attr("value"); //get id of selected entry

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
      url: 'php/Books/delete.php',
      data: {id: entryId},
      type: 'post',
      success: function(response){
        if(response == "200"){
          $("#deletePanel").hide();
          $("#deleteResponsePanel").fadeIn(300);
          $("#deleteOverlay").delay(1000).fadeOut(500);
          $("#deleteResponsePanel").text("Sucessfully deleted!");

          // update entries displayed
          setTimeout(function(){getData(sortState, orderState);}, 1500);
        }else{
          $("#deletePanel").hide();
          $("#deleteResponsePanel").fadeIn(300);
          $("#deleteResponsePanel").text("Delete failed.");

          $("#deleteOverlay").delay(1000).fadeOut(500);
        }
      }// end success func
    }); //end ajax
  });
});

//Close delete window when cancel button is clicked
$(document).ready(function(){
  $("#cancelDeleteBtn").click(function(){
    $('#deleteOverlay').slideUp(300);
  });
});

/***************************** Upload *****************************************/

// Prints error(s) when uploading CSV files
function printErrUpload(dbErr){
  console.log(dbErr);
  var errors = JSON.parse(dbErr);
  var errFormat = "";
  var replaceField; //changes the name of the field to be more user friendly (vs DB fields)

  // Converts json object to array if necessary
  if(!$.isArray(errors)){
    errors = [errors];
  }

  errFormat += "Error: <ul>";
  $.each(errors, function(i, item){
    errFormat += "<li> Line " + item.lineNum + ": ";

    switch(item.field){
      case "title":
        replaceField = "Title";
        break;
      case "author_first":
        replaceField = "Author First Name";
        break;
      case "author_last":
        replaceField = "Author Last Name";
        break;
      case "year_read":
        replaceField = "Year Read";
        break;
      case "year_pub":
        replaceField = "Year Published";
        break;
      case "num_pgs":
        replaceField = "Number of Pages";
        break;
      case "for_class":
        replaceField = "Read for Class";
        break;
      case "reread":
        replaceField = "Reread";
        break;
    }

    if(item.errType == "required"){
      errFormat += "Missing required input for " + replaceField;
    }else if (item.errType == "number"){
      errFormat += "Input must be a number for " + replaceField;
    }else if (item.errType == "bool"){
      errFormat += "Input must be either y, n, or NULL for " + replaceField;
    }else if (item.errType == "year"){
      errFormat += "Invalid year input for " + replaceField;
    }

    errFormat += ". Input: " + item.value + "</li>";
  });
  errFormat += "</ul>";

  return errFormat;
}

// Show upload panel when upload csv file button is clicked
$(document).ready(function(){
  $("#uploadBtn").click(function(){
    //reset upload panel
    $("#uploadResponsePanel").css({"margin": "250px auto",
      "font-size": "30px" });
    $("#uploadPanel").show();
    $("#uploadResponsePanel").hide();
    $("#uploadOverlay").slideDown(300);
  });
});

// Shows name of file selected to be uploaded
$(document).ready(function(){
		var input	 = $("#fileUpload"),
			label	 = input.next( 'label' ),
			labelVal = label.html();

		input.on('change', function(e){
			var fileName = '';

			if( this.files && this.files.length > 1 )
				fileName = ( this.getAttribute( 'data-multiple-caption' ) ||
          '' ).replace( '{count}', this.files.length );
      else if( e.target.value )
				fileName = e.target.value.split( '\\' ).pop();

      if( fileName )
        label.html("<i class='far fa-file-alt'></i> " + fileName);
			else
				label.html( labelVal );

		});

});

// Handles file upload when upload form is submitted
$(document).ready(function(e){
  $("#uploadForm").on('submit', (function(e){
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: 'php/Books/upload.php',
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(response){
        if(response == "200"){
          $("#uploadPanel").hide();
          $("#uploadResponsePanel").fadeIn(300);
          $("#uploadOverlay").delay(1000).fadeOut(500);
          $("#uploadResponsePanel").text("All books were added!");

          // update entries displayed
          setTimeout(function(){getData(sortState, orderState);}, 1500);
        }else{
          var okBtn = "The rest of the lines without errors were added. " +
          "<br> Please fix the errors and try again." +
          "<br><br><button class = \"btn\" id = \"errorOkBtn\">Ok</button>";

          if(response == "uploadErr"){
          $("#uploadResponsePanel").html("File could not be uploaded. Try again.<br>"
          + okBtn);
          }else if (response == "invalidFile"){
            $("#uploadResponsePanel").html("Invalid file type. Try again. <br>"
            + okBtn);
          }else if (response == "noFile"){
            $("#uploadResponsePanel").html("File not detected. Try again.<br>"
            + okBtn);
          }else{

            $("#uploadResponsePanel").html(printErrUpload(response) + okBtn);
          }
          $("#uploadPanel").hide();
          $("#uploadResponsePanel").css({"height": "300px", "margin": "50px auto",
            "font-size": "20px" });
          $("#uploadResponsePanel").fadeIn(300);

        } // end else, error
      }// end success
    }); // end ajax
  }));
});

// Hide upload panel when ok button is clicked when there are errors
$(document).on("click", "#errorOkBtn", function(){
  $("#uploadOverlay").slideUp(300);
});


// Hide upload panel when cancel button is clicked
$(document).ready(function(){
  $("#cancelUploadBtn").click(function(){
    $("#uploadOverlay").slideUp(300);
  });
});

/***************************** Search *****************************************/

// Set any active sorting button to inactive when search is used
function deactivateSort(){
  // Change all sorting buttons to inactive style
  $(".sortBtnClick").addClass('sortBtn');
  $(".sortBtnClick").removeClass('sortBtnClick');

  // Removes icon from sorting btn
  var iconList = ["#sortOrderIcon", "#sortTitleIcon", "#sortAuthorIcon",
    "#sortYearReadIcon", "#sortYearPubIcon", "#sortNumPgsIcon",
    "#sortForClassIcon","#sortRereadIcon"];
  for(i = 0; i < iconList.length; i++){
    $(iconList[i]).html("");
  }
}

// Search when user hits enter on search bar
$(document).ready(function(){
  $("#searchForm").submit(function(event){
    // Prevent form from being submitted normally
    event.preventDefault();

    $.ajax({
      url: 'php/Books/search.php',
      data: {query: $('#searchInput').val()},
      type: 'get',
      success: function(response){

        if(response == "404" || response == "none"){
          $("#dataTable").html("<div class = 'searchResult'>Search results for \"" +
            $('#searchInput').val() + "\"</div> No results <i class=\"far fa-frown\"></i>");
        }else{
          var printResults = "";
          $("#dataTable").fadeOut(10);
          $("#dataTable").fadeIn(500);

          printResults += "<div class = 'searchResult'>Search results for \"" +
            $('#searchInput').val() + "\"</div>";

          printResults += printData(response);

          $("#dataTable").html(printResults);
          setTimeout(deactivateSort, 100); //set sorting options to inactive
        }
      }//end success func

    }); //end ajax
  });
});

// Show default sorting when user clears search bar
$("#searchInput").on('input', function(e){
  if(this.value == ""){
    getData();
  }
});

/****************************** Sorting ***************************************/

// Toggle sort button for Order Added (change button style + add icons)
// btnLabel is the id of the selected sort button
function toggleSortBtn(btnLabel){

  var btnIcon = btnLabel + "Icon"; // stores id of the button's icon

  var upArrow = '<i class="fas fa-sort-up"></i>';
  var downArrow = '<i class="fas fa-sort-down"></i>';

  var yes = '<i class="fas fa-check"></i>';
  var no = '<i class="fas fa-times"></i>';

  var sortOrder; // return value: either ascending or descending

  // Determine ascending + descending icons depending on sort type
  var ascendIcon, descendIcon;
  if (btnLabel == "#sortForClass" || btnLabel == "#sortReread" ){
    ascendIcon = yes;
    descendIcon = no;
  }else{
    ascendIcon = upArrow;
    descendIcon = downArrow;
  }

  // Change the other buttons to inactive style
  $(".sortBtnClick").addClass('sortBtn');
  $(".sortBtnClick").removeClass('sortBtnClick');

  // Change current button to active style
  if (!$(btnLabel).hasClass("sortBtnClick")){
    $(btnLabel).addClass("sortBtnClick");
    $(btnLabel).removeClass("sortBtn");
  }

  // Remove sort icons from the other buttons (default text)
  var iconList = ["#sortOrderIcon", "#sortTitleIcon", "#sortAuthorIcon",
    "#sortYearReadIcon", "#sortYearPubIcon", "#sortNumPgsIcon",
    "#sortForClassIcon","#sortRereadIcon"];
  for(i = 0; i < iconList.length; i++){
    if(iconList[i] != btnIcon){
      $(iconList[i]).html("");
    }
  }

  // Set button active color + ascending/ descending icon
  // Inactive to Ascending
  if ($(btnIcon).html() == "" ){
    $(btnIcon).html(ascendIcon);

    if(ascendIcon == upArrow){
      sortOrder = "ascend";
    }else{
      sortOrder = "yes";
    }
  // Ascending to descending
  }else if ($(btnIcon).html() == ascendIcon){
    $(btnIcon).html(descendIcon);

    if(descendIcon == downArrow){
      sortOrder = "descend";
    }else{
      sortOrder = "no";
    }
  // Descending back to inactive
  }else {
    $(btnIcon).html("");
    $(btnLabel).removeClass("sortBtnClick");
    $(btnLabel).addClass("sortBtn");
    sortOrder = "none";
  }

  return sortOrder;

}

/************************* Sorting  Buttons ***********************************/

// sort Order Added
$(document).ready(function(){
  $("#sortOrder").click(function(){
    sortState = "orderAdded";
    orderState = toggleSortBtn("#sortOrder");

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
    orderState = toggleSortBtn("#sortTitle");

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
    orderState = toggleSortBtn("#sortAuthor");

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
    orderState = toggleSortBtn("#sortYearRead");

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
    orderState = toggleSortBtn("#sortYearPub");

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
    orderState = toggleSortBtn("#sortNumPgs");

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
    orderState = toggleSortBtn("#sortForClass");

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
    orderState = toggleSortBtn("#sortReread");

    if(orderState == "none"){
      getData();
    }else{
      getData(sortState, orderState);
    }
  });
});


/************************* Sign Up Form  ***********************************/

$(document).ready(function(){
  $("#signUpForm").submit(function(event){
    event.preventDefault();

    $.ajax({
      url: 'php/Users/signup.php',
      type: 'POST',
      data: $("#signUpForm").serialize(),
      success: function(response){
        console.log(response);
        if(response != "200"){
          var jsonData = JSON.parse(response);
          var errors = "";

          // Converts json object to array if necessary
          if(!$.isArray(jsonData)){
            jsonData = [jsonData];
          }

          $.each(jsonData, function(i){
            errors += jsonData[i] + "<br>";
          });

          $('#signUpResponse').html(errors);

        }else{
          $('#signUpResponse').text('Sign up completed! You can now log into your account.');
        }
      }// end success
    });
  });
});


// Check if passwords match
$(function(){
  $("#signUpPwd, #confirmPwd").on("change", function(){
    if($("#signUpPwd").val() == $("#confirmPwd").val()){
      $("#signUpPwd").get(0).setCustomValidity('');
      $("#confirmPwd").get(0).setCustomValidity('');
    }else{
      $("#confirmPwd").get(0).setCustomValidity('Passwords do not match.');
    }
  });

    $("#signUpPwd, #confirmPwd").on("input", function(){
      $("#signUpPwd").get(0).setCustomValidity('');
      $("#confirmPwd").get(0).setCustomValidity('');
    });

});

/************************* Login Form  ***********************************/
$(document).ready(function(){
  $("#loginForm").submit(function(event){
    event.preventDefault();
    $.ajax({
      url: 'php/Users/login.php',
      type: 'GET',
      data: $("#loginForm").serialize(),
      success: function(response){
        console.log(response);
        if(response != "200"){
          $('#loginResponse').text('Email or password is invalid. Please try again.');
        }else{
          $('#loginResponse').text('You are now logged in.');
          // Redirect user to welcome page
          setTimeout(function(){window.location.replace("welcome.php");},1000);
        }
      }// end success
    });
  });
});

$(function(){
  $("#logoutBtn").click(function(event){

      $.ajax({
        url: 'php/Users/logout.php',
        type: 'POST',
        success: function(response){
          window.location.replace("index.php");
        }
      });
  });
});
