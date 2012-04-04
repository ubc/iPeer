iPeer 2.3

Changelog from 2.2.2.1
1) Removed PHP closing brackets that may causing issue (the blank page when submitting evaluation)
2) Fixed it didn't select current course by default when clicking on edit survey in teammaker survey menu
3) Fixed the duplicated numeric result in export when there are more than one event for the same group in a course
4) Added updating user when import students
5) Fixed missing descriptors in mix eval
6) Fixed displaying descriptor for mixeval
7) Added displaying descriptor on view_mixeval_evaluation_results-details.ctp
8) Fixed find list function to 1.1 syntax
9) Fixed some format on the report
10) Fixed empty results in export issue for rubric
11) Fixed the wrong order of first name and last name in rd_auth fullname field
12) Fixed the wrong calculation on instructor detailed result page
13) Fixed total marks not calculated
14) Fixed a bug when submitting quote in a input text box in student interface

Changelo from 2.2.2
1) Fixed password reset double hash bug

Changelog from 2.2.1
1) Fixed a bug that missing group 1 when release groups from teammaker(survey)
2) Fixed a bug that ajax list is not working when magic_quote_gpc is set to ON
3) Fixed a bug that password is updated when student is added to another course
4) Fixed a bug that users receive empty email when resetting password by themselves

Changelog from 2.2
1) Fixed a bug pagination on evaluation result list failed on IE
2) Fixed a bug that removing an evaluation event failed
3) Fixed a bug when editing student will lose the course info
4) Fixed a bug when changing student number will result an error
5) Fixed a bug that unordered group import file will create duplicated groups
6) Fixed a bug when editing Mixed evaluation resets the total_marks database entry to NULL
7) Changed the instructor column on course page from username to instructor name

Changelog from 2.1
1) Students are now able to view released evaluation results (grades and comments)
     These results are anonymous, and randomly ordered.
     Fixed for Rubrics and Mixed Evaluations.
2) Import from CSV changes for Students/Groups:
     Email and password are optional. Password will be randomly generated if it is omitted from the import file.
     Column orders are changed. Please see the sample file on import page.
     Mac/Windows Excel CSV format is supported.
     Default course is set to blank when importing student.
3) Export Changes
     Corrected Export Evaluation result calculations.
     Evaluation result export with details.
4) Copy mixed evaluation function is now working.
5) Small GUI Changes:
     Changed phrasing and language to make things more clear.
     More clear notice if student did not finish all questions on student portal.
6) General bug fixes, including some code clean-up.
7) New Listing component
     Replaces most lists in iPeer with a unified component
     (with notable exception of Advanced Search).
     Easy to use for end-users, and to implement and augment by developers.


Changelog from 2.0

1) Over 180 bugs are fixed
2) Major browsers are supported
3) PHP 5.3 is supported
4) Improved security and permission checking
5) Improved installation script and new upgrade script
6) Now exports comments in the CSV file
7) Import Students/Groups from CSV file now functional
8) Instructors can only add students to the courses the instructor teaches
9) Instructors can no longer remove students from courses the instructor does not teach
10) Instructors can no longer remove students from the database
11) An instructor can no longer remove himself/herself from the course
12) Group email links are removed temporarily

Changelog from 2.0.8

1) Bug fix: #1725231 , #1725229 (see bugs list in SF for details)
2) Modify default login page to remove unnecessary JavaScript codes


upgrade from 1.6:

1. copy the ipeer_export folder OUTSIDE of your iPeer 2 folder
2. run it from your browser http://yourserverpath/ipeer_export/ipeer_export.php
3. type the user/pass and whatnot to get the xml file of your database
4. save it for now and continue on to installation of iPeer 2.

iPeer 2 install:

requirements:
mod_rewrite for apache
PHP 4.3.10+ with GD extension
MYSQL 4+
PEAR with XML_RPC module (optional, only needed when using CWL)

1.  run http://yourserverpath/youripeerpath/install   (trailing slash may be required)
1b. create a database in MySql
2.  follow the instructions for the install
2a. if you are upgrading, select UPGRADE and load the xml file there.
--     in install4, make sure you set the absolute path to your path!
3.  once the install is complete, delete controllers/install_controller.php and /ipeer_export directory.

please note:

1. The database config file /app/config/database.php must be writable during installation. 
2. After installation, please make database.php read only. It is VERY important.
3. To change file permission, you may either use a FTP client or do it directly through command line (if you have
shell access to your server. In unix, you may use chmod command to do it)

please report any bugs you find on sourceforge. we can't fix it if we don't know about it.

any suggestion or question? please let us know

troubleshooting:

-if you type http://yourserverpath/youripeerpath/ and you get http://yourserverpath/loginout/login, your mod_rewrite
is not set up properly. make sure the line in http.conf has 'AllowOverride All'.
