// Count number of times user has clicked button in order to toggle between states
var numClicked = 0;

//  Toggle sort button for Order Added (change button style + add icons)
function toggleSortBtn(btnLabel, innerTxt, isBool){

  var upArrow = ' <i class="fas fa-sort-up"></i>';
  var downArrow = " <i class='fas fa-sort-down'></i>";

  var yes = ' <i class="fas fa-check"></i>';
  var no = ' <i class="fas fa-times"></i>';

  // Determine ascending + descending icons depending on sort type
  var ascendIcon;
  var descendIcon;
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

  console.log("start");
  console.log("html: " + $(btnLabel).html());
  console.log("has active class: " + $(btnLabel).hasClass("sortBtnClick"));

  // Set button active color + ascending/ descending icon
  // Default to Ascending
  if ($(btnLabel).html() == innerTxt  ){ //|| $(btnLabel).html() == (innerTxt + " ")
    console.log("set to ascending");
    $(btnLabel).html(innerTxt + ascendIcon);

  // Ascending to descending
  }else if ($(btnLabel).html() == innerTxt + ascendIcon){
    console.log("set to descending");
    $(btnLabel).html(innerTxt + descendIcon);

  // Descending back to default (inactive state)
  }else {
    console.log("back to default");
    $(btnLabel).html(innerTxt);
    $(btnLabel).removeClass("sortBtnClick");
    $(btnLabel).addClass("sortBtn");
  }

  console.log("html: " + $(btnLabel).html());
  console.log("has active class: " + $(btnLabel).hasClass("sortBtnClick"));

  console.log("end");

}


$(document).ready(function(){
  // sort Order Added
  $("#sortOrder").click(function(){
    toggleSortBtn("#sortOrder", "Order Added", 0);
  });
});

// sort Title
$(document).ready(function(){
  $("#sortTitle").click(function(){
    toggleSortBtn("#sortTitle", "Title", 0);
  });
});

// sort Author
$(document).ready(function(){
  $("#sortAuthor").click(function(){
    toggleSortBtn("#sortAuthor", "Author", 0);
  });
});

// sort Year Read
$(document).ready(function(){
  $("#sortYearRead").click(function(){
    toggleSortBtn("#sortYearRead", "Year Read", 0);
  });
});

// sort Year Read
$(document).ready(function(){
  $("#sortYearPub").click(function(){
    toggleSortBtn("#sortYearPub", "Year Published", 0);
  });
});

// sort Num Pgs
$(document).ready(function(){
  $("#sortNumPgs").click(function(){
    toggleSortBtn("#sortNumPgs", "Number of Pages", 0);
  });
});

// sort For Class
$(document).ready(function(){
  $("#sortForClass").click(function(){
    toggleSortBtn("#sortForClass", "For Class", 1);
  });
});

// sort Reread
$(document).ready(function(){
  $("#sortReread").click(function(){
    toggleSortBtn("#sortReread", "Reread", 1);
  });
});

/*
$(document).ready(function(){
  $("#sortOrder").click(function(){

    //Button active color + ascending/ descending icon
    if ($('#sortOrder').html() == "Order Added" || $('#sortOrder').html() == 'Order Added <i class="fas fa-sort-down"></i>'){
      $('#sortOrder').html("Order Added <i class='fas fa-sort-up'></i>");
      $("#sortOrder").toggleClass("sortBtn");
      $("#sortOrder").toggleClass("sortBtnClick");
    }else {
      $('#sortOrder').html("Order Added <i class='fas fa-sort-down'></i>");
    }

    // Button not active color
    if ($("#sortOrder").hasClass("sortBtn") && $('#sortOrder').html() != "Order Added"){
      $('#sortOrder').html("Order Added");
    }

  });
});
*/
