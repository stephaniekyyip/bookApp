# Book Tracker
Book Tracker is a web app that keeps track of all the books you have read. This app is meant to be a replacement to the spreadsheet I have been using to record all the books I have read since 2008. I wanted to create something with a clean and easy-to-use user interface that would allow me to not only record the books I have read, but also analyze my reading habits across the past decade. 

This app uses a tech stack consisting of Apache, MySQL, and PHP. On the front end, jQuery is used along with C3.js/ D3.js for data visualization.  

## Try It Out
See the live app [here](https://still-scrubland-90743.herokuapp.com/).
<br>
Download some sample CSV files [here](https://github.com/stephaniekyyip/bookTracker/tree/master/csv_files) to test the upload feature.
<br>
<br>
Create a new account, log in, and add a few books to get started. You can also upload one of the CSV files linked above to see the app working with a more complete set of book entries.
<br>
<br>
#### Home Page
<img src = "https://github.com/stephaniekyyip/bookTracker/blob/master/bookTrackerScreenshot.png" width = "600px"></img>
<br>

#### Reading Analytics Page
<img src = "https://github.com/stephaniekyyip/bookTracker/blob/master/bookTrackerAnalytics.png" width = "600px"></img>
<br>

## Features
- Create a new account or log back into an existing account.
- Add, edit, and delete entries. Each book entry contains:
  - Book title
  - Author
  - Year Read
  - Number of Pages
  - Year Published
  - Was the book read for class?
  - Was the book a reread?
- Different ways to sort the book entries, such as alphabetically by author or in order from newest to oldest.
- Search functionality.
- Ability to add multiple books at once by uploading a CSV file.
- Reading analytics that provide statistics and data visualizations of the user's reading habits.

## Libraries Used
- jQuery (v3.3.1)
- Data visualization
  - [C3.js](http://c3js.org) (v0.6.1)
  - [D3.js](https://d3js.org/) (v4) (This version is needed for compability with C3.js)
