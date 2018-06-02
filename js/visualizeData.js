var myData = [];
var numBooks= [24,20,26,24,21,25,9,8,1,6,3];
var year = [2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018];

for(var i = 0; i < numBooks.length; i++ ){
  myData.push({
    numBooks: numBooks[i],
    year: year[i]
  });
}

var xLabel = "year";
var yLabel = "numBooks";
var chartTitle = "Total Books Read Each Year";

var chart = c3.generate({
    data: {
        json: myData,
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
        // or
        //width: 100 // this makes bar width 100px
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
