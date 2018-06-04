# Book Tracker
Book Tracker is a web app that keeps track of all the books you have read. This app is meant to be a replacement to the spreadsheet I have been using to record all the fiction books I have read since 2008. I wanted to create something with a clean and easy-to-use user interface that would allow me to not only record the books I have read, but also analyze my reading habits across the past decade. 

The app uses a variation of the LAMP stack, consisting of Mac OS, Apache, MySQL, and PHP. On the front end, jQuery is used along with C3.js/ D3.js for data visualization.  

## Features
- Each book entry can include the title of the book, the author, year read, year published, number of pages, whether the book was read for class, or whether the book was a reread.
- Multiple ways to sort the book entries, such as in ascending order by title.
- Search through all the book entries.
- Add multiple books at once by uploading a CSV file.
- Reading analytics that provides statistics and visualizations of the user's reading habits.

## Dependencies
- jQuery (v3.3.1)
- Data Visualization
  - [C3.js](http://c3js.org) (v4.23)
  - [D3.js](https://d3js.org/) (v3) (This version needed for compability with C3.js)
