$(document).ready(function(){
  $("#addBtn").click(function(){
    $("#addPanel").slideToggle();
    $("#addResponse").hide();
  });
});

$(document).ready(function(){
  $("#cancelBtn").click(function(){
    $("#addPanel").slideUp();
  });
});
