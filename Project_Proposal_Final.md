Project Proposal Final
=======
####Team Member:
Lilin Wang : lw555@cornell.edu     Yanjing Zhang: yz786@cornell.edu
####App Name: 
DesiredDish

A food journaling, food sharing, and food ordering app.
####Motivation:
Everyday people need to think about what to eat today, and it’s sometimes hard to make decision. Here comes DesiredDish, it can help people to memorize what they’ve eat and how was the meal. It can also let people share the meal information with their friends and see what their friends eat as well. More importantly, with DesiredDish user can simply order food from the restaurants, which their friends have recommended.
####Related apps: 
   * MealLogger: the fast photo food journal, is the easy way to keep track of your personal wellness. 
   * Foodmento: it’s a new mobile platform where people can discover, organize and share the best dishes in the world and connect with the people who love them. 
   * FoodSnap: a food photo diary dedicated to making it easy to track your meals and be accountable for the food you eat. Meticulous food tracking, just like meticulous diets, is tough to sustain. 
   * Seamless: an online food ordering service that allows users to order food for delivery and takeout from restaurants. Compared to Seamless, we’re not limited restaurants to those have delivery services, and Seamless is NOT dishes oriented.

####Related technologies:
   * Log-in/Register Service on Android Device
   * Quickly capture your meal via user camera
   * Load thumbnailing image and use local cache to speed up process.
   * Integrate user accounts with social network
   * Data export API
  
####Fulfill the minimum entry requirement
   1.	User accounts/management

    Users must sign in/sign up with user name and password to use DesiredDish app. Users can then link their social network account with their social network account at anytime. We will store the users account info into remote server database.
   2.	Native app on Android or iOS

    We plan to develop a Native Android app. 
   3.	Meal description/metadata logging with date/time stamp
   
    We will have a database to store image path/comment/rating/eating date for a meal, those information should be provided by user via our app. Also, backend server will record the uploaded date and store it to the database as well. All images will be stored in an exclusive folder, which can be accessed via the image path.
   4.	Photo capture/storage
   
       Users can either take pictures via phone camera or pick a picture from the Photo Library to upload to our server. Our backend server will handle their request to store their photo and meal metadata. Users can also save pictures on DesiredDish to their phone.
   5.	Thumbnailing/image manipulations
   
      When users open our app, it will download metadata via JSON and then parse it locally, also, it will only load the thumbnailing image into memory to speed up the whole process. Plus, we will check local cache to provide better user experience.
   6.	At least one service (in the sense of SOA)
   
     When users request register/log in/upload image/download meal info/share with friends/read friend’s meal information on their mobile phone, our backend server will have corresponding scripts to handle their request properly.

   7.	Rudimentary web presence
   
     We will create a simple website like https://www.uber.com/cities/new-york , to provide some basic functions such as user account creation and forgotten password reset.

   8.	Basic API for data export
   
     We will expose some basic APIs for user to export data of resaurant info and reviews from our APIs without using our Interface.
   9.	Integration with 3rd party API

     Integrate with OpenTable API to get restaurants info.
     Integrate user accounts with social network so that users can share information on our app to social platform.

####Deep Dive
Our aim is to make DesiredDish not only a food journaling app, but also a food sharing, food discovering app. It is a food social network and food ordering service. 

In DesiredDish, user can 

   1. share their meals, either homemade dishes or restaurant dishes to our platform
   2. check what other people shared in our platform
   3. use filters (homemade/restaurant food) and sort them by rating/price/distance/delivery time
   4. place an order or put in a reservation to the restaurants which others have shared to the platform

Firstly, we use OpenTable Public API to get restaurants data to build a local database.

On food social network service, users can sign up with a DesiredDish user id and then bind their Facebook accounts. We enable users to add friend from their Facebook friend list or send friend request by searching friend’s DesiredDish user id. We plan to use Facebook API to bind DesiredDish account with Facebook account and request Facebook friend list.

On food ordering service, we enable users to order food by clicking a phone call button, or reserve a table of restaurant. We plan to use built-in package android.telephony to realize the ordering feature, while call OpenTable Public API to realize the reservation feature.

####Milestones
*	Four to six milestones and deadlines

   10/1/14 
     * Configure all the development environment 
     * Figure out all necessary resource & knowledge we need in this project.

   10/8/14 
     * Finish second version proposal
     * Do research online for related public API
     * Start building the app

   10/15/14
     * Design and implement Log in/register service on Android Device
     * Use Camera to take photo, store it and upload to web server 

   10/22/14 
     * Download metadata list and images asynchronously, present them in List View
     * Implement Thumbnailing/image manipulations
     * Expose basic API for data export
     
   10/29/14
     * Implement Rudimentary web presence
     * Integrate with 3rd party API
     * Finish final version proposal
     
   11/5/14
     * Discuss and detail the deep dive part
     * Look for other teams who’s interested in similar deep dive

   11/12/14
     * Fulfill the food sharing part of deep dive
     
   11/19/14
     * Fulfill the food ordering part of deep dive
     * Start testing, fixing bug and improving function
   
   11/26/14
     * Code freeze
   
   12/16/14
     * Open Studio

 
*	Division of labor between team members

    Equally divide initially. Everyone will have individual tasks later.

