var chartData = [];
var numBooks= [24,20,26,24,21,25,9,8,1,6,3];
var year = [2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018];

for(var i = 0; i < numBooks.length; i++ ){
  chartData.push({
    numBooks: numBooks[i],
    year: year[i]
  });
}

var xLabelSelect; // Current data in x-axis of chart
var yLabelSelect; // Current data in y-axis of chart
var chartTitleSelect; // Current title of chart
var chart; // Holds the chart

// Creates the chart
function createChart(xLabel, yLabel, chartTitle){
  chart = c3.generate({
      data: {
          json: chartData,
          keys:{
            x: xLabel,
            y: yLabel,
            value: [xLabel, yLabel]
          },
          type: 'bar',
          labels: true
      },
      bar: {
          width: {
              ratio: 0.7 // this makes bar width 50% of length between ticks
          }
      },
       legend: {
         show: false
       },
       axis:{
         x: {
           type: 'category'
         },
         y: {
           show: false
         }
       },
       tooltip:{
         show: false
       },
       title: {
         text: chartTitle
       },
       padding: {
         top: 30,
       }
  });
}

// Load total books yearly chart by default
$(function(){
  xLabelSelect = "year";
  yLabelSelect = "numBooks";
  chartTitleSelect = "Total Books Read Each Year";

  createChart(xLabelSelect, yLabelSelect, chartTitleSelect);
});

/************************** Overall Stats *************************************/

// Fills in stats for Overall Stats sections
$(document).ready(function(){
  $.ajax({
    url: 'php/analyticValues.php',
    type: 'GET',
    success: function(response){
      var jsonData = JSON.parse(response);
      // var table = ""; // Holds the html to display all data entries

      // Converts json object to array if necessary
      if(!$.isArray(jsonData)){
        jsonData = [jsonData];
      }

      var mostAuthorList = "";
      var mostAuthorBooks;
      var needComma = 0;

      // Add stats to page
      $.each(jsonData, function (i, item){
        switch(i){
          case 0:
            $("#totalBooks").text(item.totalBooks);
            $("#totalPgs").text(item.totalPgs);
            $("#earliestYear").text(item.earliestYear);
            break;
          case 1:
            $("#maxPgsTitle").text(item.maxPgsTitle);
            $("#maxPgs").text(item.maxPgs);
            $("#authorMaxPgs").text(item.authorMaxPgs);
            break;
          case 2:
            $("#minPgsTitle").text(item.minPgsTitle);
            $("#minPgs").text(item.minPgs);
            $("#authorMinPgs").text(item.authorMinPgs);
            break;
          case 3:
            $("#numDistinctAuthors").text(item.numDistinctAuthors);
            break;
          case 4:
            $("#mostAuthorBooks").text(item.mostAuthorBooks);
          default:
            if(needComma == 1){
              mostAuthorList += ", ";
            }
            mostAuthorList += item.mostAuthor;
            needComma = 1;
            break;
        }
      }); // end each

      $("#mostAuthor").text(mostAuthorList);

    } // end success
  }); // end ajax

});

/************************** Chart Buttons *************************************/

// Change chart buttons to active state
function toggleChartBtn(btnLabel){
  // Change the other buttons to inactive style
  $(".sortBtnClick").addClass('sortBtn');
  $(".sortBtnClick").removeClass('sortBtnClick');

  // Change current button to active style
  if (!$(btnLabel).hasClass("sortBtnClick")){
    $(btnLabel).addClass("sortBtnClick");
    $(btnLabel).removeClass("sortBtn");
  }
}

// Handles the transition between different chart selections
function chartTransition(){
  chart.unload({
    ids: [xLabelSelect, yLabelSelect]
  });
}

// Generates the chart when clicking the corresponding button
$(function(){
  // Total books by year
  $("#totalBooksBtn").click(function(){
    xLabelSelect = "year";
    yLabelSelect = "numBooks";
    chartTitleSelect = "Total Books Read Each Year";

    toggleChartBtn("#totalBooksBtn");
    chartTransition();
    createChart(xLabelSelect, yLabelSelect, chartTitleSelect);
  });

  // Total pages by year
  $("#totalPgsBtn").click(function(){
    toggleChartBtn("#totalPgsBtn");
  });

  // Total for class vs not by year
  $("#totalForClassBtn").click(function(){
    toggleChartBtn("#totalForClassBtn");
  });

  // Total reread vs not by year
  $("#totalRereadBtn").click(function(){
    toggleChartBtn("#totalRereadBtn");
  });
});
