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
    url: 'php/analyticCharts.php',
    type: 'GET',
    data: {chartSelect: yLabelSelect},
    success: function(response){
      var jsonData = JSON.parse(response);

      // Converts json object to array if necessary
      if(!$.isArray(jsonData)){
        jsonData = [jsonData];
      }
      console.log(jsonData);

      console.log(yLabelSelect);

      if(yLabelSelect == "totalForClass" || yLabelSelect == "totalReread"){
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
  console.log("generate chart");
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
         height: 400
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
         height: 400
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
  var yLabel2 = "Book Title";
  var yLabel3 = "Author";

  chart = c3.generate({
      data: {
          json: chartData,
          keys:{
            x: xLabel,
            y: yLabel1,
            y2: yLabel2,
            value: [xLabel, yLabel1, yLabel2, yLabel3]
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
            // outer: false
              centered: true,
              format: '%Y'
           }
         },
         y: {
           //show: false
           type: 'category',
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

              // const meta = config.data_classes;

              for(i = 0; i < d.length; i++){
                if (!(d[i] && (d[i].value || d[i].value === 0))) {continue;}
                if(!text){
                  title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                  text = "<table class ='" + $$.CLASS.tooltip + "'>" +
                  (title || title === 0 ? "<tr class = 'top'><td class = 'name' id = 'topLeft'>"
                  + "Year Read" + "</td><td id = 'topRight'>" + title
                  + "</td></tr>" : "");
                }

                 // const line = d[0].id;
                 // const properties = meta.classes[line];
                 // const property = properties ? properties[i] : null;
                 //
                 //

                 // if (property){
                 //   text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[i].id + "'>";
                 //   text += "<td class='name' id = 'bottomLeft'>Year Read</td>";
                 //   text += "<td class='value' id = 'bottomRight'>" + property.yearRead + "</td>";
                 //   text += "</tr>";
                 //
                 //   text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[i].id + "'>";
                 //   text += "<td class='name' id = 'bottomLeft'>Year Published</td>";
                 //   text += "<td class='value' id = 'bottomRight'>" + "" + "</td>";
                 //   text += "</tr>";
                 //
                 //   text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[i].id + "'>";
                 //   text += "<td class='name' id = 'bottomLeft'>Book Title</td>";
                 //   text += "<td class='value' id = 'bottomRight'>" + "" + "</td>";
                 //   text += "</tr>";
                 //
                 // }

                 name = nameFormat(d[i].name);
                 value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);


                  text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[i].id + " top'>";
                  text += "<td class='name' >" + name + "</td>";
                  text += "<td class='value' >" + value + "</td>";
                  text += "</tr>";

                  text += "<tr class='" + $$.CLASS.tooltipName + "-" + "bookTitle"+ " top'>";
                  text += "<td class='name' >" + "Book Title" + "</td>";
                  text += "<td class='value'>" + chartData[d[i].index]['Book Title'] + "</td>";
                  text += "</tr>";

                  text += "<tr class='" + $$.CLASS.tooltipName + "-" + "author"+ " top'>";
                  text += "<td class='name' id = 'bottomLeft'>" + "Author" + "</td>";
                  text += "<td class='value' id = 'bottomRight'>" + chartData[d[i].index]['Author'] + "</td>";
                  text += "</tr>";

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
         height: 400
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
  // createChart(xLabelSelect, yLabelSelect, chartTitleSelect);
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
    url: 'php/analyticValues.php',
    type: 'GET',
    success: function(response){
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
