var myData = []; // Holds chart data in JSON format
var numBooks= [24,20,26,24,21,25,9,8,1,6,3];
var year = [2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018];

for(var i = 0; i < numBooks.length; i++ ){
  myData.push({
    numBooks: numBooks[i],
    year: year[i]
  });
}

var xLabelSelect = "year"; // Current data in x-axis of chart
var yLabelSelect; // Current data in y-axis of chart
var chart; // Holds the chart itself generated using C3.js

function getChartData(chartTitle, yAxisText){
  $.ajax({
    url: 'php/analytics/analyticCharts.php',
    type: 'GET',
    data: {chartSelect: yLabelSelect},
    success: function(response){
      // console.log(response);
      var jsonData = JSON.parse(response);

      // Converts json object to array if necessary
      if(!$.isArray(jsonData)){
        jsonData = [jsonData];
      }
      // console.log(jsonData);

      var errMsg; // Store error msg to display instead of the chart

      if (response == "404"){ //No data yet, so chart can't be displayed
        switch(yLabelSelect){
          case "yearReadvsPublished":
            errMsg = "the year published to your book entries";
            break;
          case "totalPgs":
            errMsg = "the number of pages to your book entries";
            break;
          case "totalForClass":
            errMsg = "to your books entries whether the books were read for class or not"
            break;
          case "totalReread":
            errMsg = "to your books entries whether the books were a reread or not";
            break;
          case "totalBooks":
            errMsg = "some book entries";
            break;
        }
        // Error message
        $("#chart").html("<span class = 'highlightColor'>Please go back and add "
        + errMsg + " to see this chart.</span>");
      }else if (yLabelSelect == "totalForClass" || yLabelSelect == "totalReread"){
        createDoubleChart(xLabelSelect, yLabelSelect, yLabelSelect + "Not", chartTitle, yAxisText, jsonData);
      }else if (yLabelSelect == "yearReadvsPublished"){
        createScatterplot("Year Read", "Year Published", chartTitle, yAxisText, jsonData);
      }else{
        createChart(xLabelSelect, yLabelSelect, chartTitle, yAxisText, jsonData);
      }

    }// end success
  }); // end ajax

}

// Creates the chart
function createChart(xLabel, yLabel, chartTitle, yAxisText, chartData){
  // console.log("generate chart");
  chart = c3.generate({
      data: {
          json: chartData,
          keys:{
            x: xLabel,
            y: yLabel,
            value: [xLabel, yLabel]
          },
          type: 'bar',
          labels: {
            format: function(d){ return d3.format(",")(d); },
          }
      },
      bar: {
          width: {
              ratio: 0.4 // this makes bar width 50% of length between ticks
          }
      },
       legend: {
         show: false
       },
       axis:{
         x: {
           type: 'category',
           label:{
             text: 'Year',
             position: 'outer-center'
           },
           tick: {
            // outer: false
              centered: true
           }
         },
         y: {
            // type: 'category',
           label:{
             text: yAxisText,
             position: 'outer-middle'
           },
           tick: {
             centered: true
          },
          inner: true
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
         bottom: 10
       },
       size: {
         height: 500
       },
       color: {
         pattern: ['#F9E784', '#DBC02E']
       }
  });

  // Hide y-axis tick numbers
  $(".c3-axis-y .tick text").hide();
}

function createDoubleChart(xLabel, yLabel1, yLabel2, chartTitle, yAxisText, chartData){

  chart = c3.generate({
      data: {
          json: chartData,
          keys:{
            x: xLabel,
            y: yLabel1,
            y2: yLabel2,
            value: [xLabel, yLabel1, yLabel2]
          },
          type: 'bar',
          labels: true
      },
      bar: {
          width: {
              ratio: 0.8
          }
      },
       axis:{
         x: {
           type: 'category',
           label:{
             text: 'Year',
             position: 'outer-center'
           },
           tick: {
            // outer: false
              centered: true
           }
         },
         y: {
           //show: false
           label:{
             text: yAxisText,
             position: 'outer-middle'
           },
           tick: {
            // outer: false
          },
          inner: true
         }
       },
       tooltip:{
         grouped: true,
         contents: function (d, defaultTitleFormat, defaultValueFormat, color){
           const  $$ = this;
           const config = $$.config;
           var titleFormat = config.tooltip_format_title || defaultTitleFormat,
           nameFormat = config.tooltip_format_name || function(name){return name;},
           valueFormat = config.tooltip_format_value || defaultValueFormat,
           text, i, title, value, name, bgcolor;

           var yes, no, label;
           if(yLabelSelect == "totalForClass"){
             yes = "Read for Class";
             no = "Not Read for Class";
           }else{
             yes = "Reread";
             no = "Read for the First Time";
           }

           for(i = 0; i < d.length; i++){
             if (!(d[i] && (d[i].value || d[i].value === 0))) {continue;}
             if(!text){
               title = titleFormat ? titleFormat(d[i].x) : d[i].x;
               text = "<table class ='" + $$.CLASS.tooltip + "'>" +
               (title || title === 0 ? "<tr class = 'top'><td class = 'name' id = 'topLeft'>"
               + "Year" + "</td><td id = 'topRight'>" + title
               + "</td></tr>" : "");
             }

              name = nameFormat(d[i].name);
              value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);

              if (name == "totalForClass" || name == "totalReread"){
                label = yes;
              }else {
                label = no;
              }

             text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[0].id + " top'>";
             text += "<td class='name' >" + label + "</td>";
             text += "<td class='value'>" + chartData[d[i].index][name] + "</td>";
             text += "</tr>";

             // text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[0].id + "top'>";
             // text += "<td class='name' id = 'bottomLeft'>" + name + "</td>";
             // text += "<td class='value' id = 'bottomRight'>" + value + "</td>";
             // text += "</tr>";

               // text += "<tr class='" + $$.CLASS.tooltipName + "-" + "bookTitle"+ " top'>";
               // text += "<td class='name' >" + "Book Title" + "</td>";
               // text += "<td class='value'>" + chartData[d[i].index]['Book Title'] + "</td>";
               // text += "</tr>";

               // text += "<tr class='" + $$.CLASS.tooltipName + "-" + "author"+ " top'>";
               // text += "<td class='name' id = 'bottomLeft'>" + "Author" + "</td>";
               // text += "<td class='value' id = 'bottomRight'>" + chartData[d[i].index]['Author'] + "</td>";
               // text += "</tr>";

           }
           return text + "</table>";
         }
       },
       title: {
         text: chartTitle
       },
       padding: {
         top: 30,
         bottom: 10
       },
       size: {
         height: 500
       },
       color: {
         pattern: ['#F9E784', '#DBC02E']
       }
  });

  if(yLabelSelect == "totalReread"){
    $(".c3-legend-item-totalReread text").text("Reread");
    $(".c3-legend-item-totalRereadNot text").text("Read For the First Time");
  }else{
    $(".c3-legend-item-totalForClass text").text("Read For Class");
    $(".c3-legend-item-totalForClassNot text").text("Not Read For Class");
  }

  // Hide y-axis tick numbers
  $(".c3-axis-y .tick text").hide();
}

function createScatterplot(xLabel, yLabel1, chartTitle, yAxisText, chartData){
  // var yLabel2 = "Book Title";
  // var yLabel3 = "Author";

  chart = c3.generate({
      data: {
          json: chartData,
          keys:{
            x: xLabel,
            y: yLabel1,
            value: [xLabel, yLabel1]
          },
          x: xLabel,
          type: 'scatter',
          labels: true
      },
       legend: {
         show: false
       },
       axis:{
         x: {
         	type: 'category',
           label:{
             text: 'Year Read',
             position: 'outer-center'
           },
           tick: {
              centered: true
           }
         },
         y: {
           label:{
             text: yAxisText,
             position: 'outer-middle'
           },
           tick: {
             format: d3.format(".4r"),
             count: 6
          },
          inner: true
         }
       },
        tooltip:{
            grouped: true,
            contents: function (d, defaultTitleFormat, defaultValueFormat, color){
              const  $$ = this;
              const config = $$.config;
              var titleFormat = config.tooltip_format_title || defaultTitleFormat,
              nameFormat = config.tooltip_format_name || function(name){return name;},
              valueFormat = config.tooltip_format_value || defaultValueFormat,
              text, i, title, value, name, bgcolor;

              for(i = 0; i < d.length; i++){
                if (!(d[i] && (d[i].value || d[i].value === 0))) {continue;}
                if(!text){
                  title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                  text = "<table class ='" + $$.CLASS.tooltip + "'>" +
                  (title || title === 0 ? "<tr class = 'top'><td class = 'name' id = 'topLeft'>"
                  + "Year Read" + "</td><td id = 'topRight'>" + title
                  + "</td></tr>" : "");
                }

                 name = nameFormat(d[i].name);
                 value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);


                  text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[i].id + " top'>";
                  text += "<td class='name' id = 'bottomLeft'>" + name + "</td>";
                  text += "<td class='value' id = 'bottomRight'>" + value + "</td>";
                  text += "</tr>";

                  // text += "<tr class='" + $$.CLASS.tooltipName + "-" + "bookTitle"+ " top'>";
                  // text += "<td class='name' >" + "Book Title" + "</td>";
                  // text += "<td class='value'>" + chartData[d[i].index]['Book Title'] + "</td>";
                  // text += "</tr>";

                  // text += "<tr class='" + $$.CLASS.tooltipName + "-" + "author"+ " top'>";
                  // text += "<td class='name' id = 'bottomLeft'>" + "Author" + "</td>";
                  // text += "<td class='value' id = 'bottomRight'>" + chartData[d[i].index]['Author'] + "</td>";
                  // text += "</tr>";

              }
              return text + "</table>";
            }
       },
       title: {
         text: chartTitle
       },
       padding: {
         top: 30,
         bottom: 10
       },
       size: {
         height: 500
       },
       color: {
         pattern: ['#F9E784', '#FDF9E0']
       },
       point: {
       	r: 2.5
      }
  });

  // Display y-axis tick numbers
  $(".c3-axis-y .tick text").show();

  // Change label in Tooltip
  $(".c3-tooltip tbody tr th").prepend("Year Read: ");
}

// Load total books yearly chart by default
$(function(){
  yLabelSelect = "totalBooks";
  chartTitle = "Total Books Read Each Year";
  yAxisText = "Number of Books";

  getChartData(chartTitle, yAxisText);
});

// Fade in analytics section on load
$(function(){
  $("#dataVisuals").css('visibility', 'hidden');
  $("#dataVisuals").delay(10).css('visibility', 'visible').hide().fadeIn(500);

  $("#analytics").css('visibility', 'hidden');
  $("#analytics").delay(10).css('visibility', 'visible').hide().fadeIn(500);

});

/************************** Overall Stats *************************************/

// Fills in stats for Overall Stats sections
$(document).ready(function(){
  $.ajax({
    url: 'php/analytics/analyticValues.php',
    type: 'GET',
    success: function(response){

      if(response == "404"){
        $("#analytics").html("Please add some book entries to see statistics here.");
      }else{
        var jsonData = JSON.parse(response);

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
              $("#totalPgs").text(Number(item.totalPgs).toLocaleString());
              $("#earliestYear").text(item.earliestYear);
              break;
            case 1:
              if(item.maxPgs > 0){
                $("#max").show();
                $("#maxPgsTitle").text(item.maxPgsTitle);
                $("#maxPgs").text(item.maxPgs);
                $("#authorMaxPgs").text(item.authorMaxPgs);
              }else{
                $("#max").hide();
              }
              break;
            case 2:
              if(item.minPgs > 0){
                $("#min").show();
                $("#minPgsTitle").text(item.minPgsTitle);
                $("#minPgs").text(item.minPgs);
                $("#authorMinPgs").text(item.authorMinPgs);
              }else{
                $("#min").hide();
              }
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
      }

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

  // chart.flow({
  //   transition: {
  //     duration: 500
  //   }
  // });
}

// Generates the chart when clicking the corresponding button
$(function(){
  // Total books by year
  $("#totalBooksBtn").click(function(){
    yLabelSelect = "totalBooks";
    var chartTitle = "Total Books Read Each Year";
    var yAxisText = "Number of Books";

    toggleChartBtn("#totalBooksBtn");
    getChartData(chartTitle, yAxisText);
  });

  // Total pages by year
  $("#totalPgsBtn").click(function(){
    yLabelSelect = "totalPgs";
    var chartTitle = "Total Pages Read Each Year";
    var yAxisText = "Number of Pages";

    toggleChartBtn("#totalPgsBtn");
    getChartData(chartTitle, yAxisText);
  });

  // Total for class vs not by year
  $("#totalForClassBtn").click(function(){
    yLabelSelect = "totalForClass";
    var chartTitle = "Total Books Read For Class vs Not for Class Each Year";
    var yAxisText = "Number of Books";

    toggleChartBtn("#totalForClassBtn");
    getChartData(chartTitle, yAxisText);
  });

  // Total reread vs not by year
  $("#totalRereadBtn").click(function(){
    yLabelSelect = "totalReread";
    var chartTitle = "Total Books Reread vs Read for the First Time Each Year";
    var yAxisText = "Number of Books";

    toggleChartBtn("#totalRereadBtn");
    getChartData(chartTitle, yAxisText);
  });

  // Year read vs year published
  $("#yearReadvsPublishedBtn").click(function(){
    yLabelSelect = "yearReadvsPublished";
    var chartTitle = "Year Read vs Year Published For Each Book";
    var yAxisText = "Year Published";

    toggleChartBtn("#yearReadvsPublishedBtn");
    getChartData(chartTitle, yAxisText);
  });
});
