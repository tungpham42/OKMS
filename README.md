CONTENTS OF THIS FILE
---------------------

 * About OKMS
 * Features
 * Installation

ABOUT OKMS
----------

OKMS stands for Online Knowledge Management System. It is a Q & A platform 
for lecturers and students to share knowledge and query. The Online Knowledge 
Management System is a web-based application to aim for the desire of grasping 
the fundamental and advanced knowledge firmly of students.

FEATURES
--------

The system proposed allows lecturers posting questions collected from students 
and their answers. These questions and answers here called "post". These posts 
will be categorized into courses (e.g. ISYS2110 Internet for Business, INTE2431 
Business IT or ACCT2105 Introductory Accounting).
Students are allowed to interact with posts in multiple ways, include viewing, 
commenting, like or dislike and rating for the difficulty level. These interactions 
would accumulate enabling students to sort for the most interacted posts to view. 
Furthermore, these statistics help lecturers understand which knowledge area they 
should explain in more detailed.
Another functions aiding lecturers in improve teaching quality is by viewing 
statistical reports. OKMS generate dynamic report based on current statistics 
that ensure the information is realistic, accuracy, timely and effective. Lecturers 
can choose different criterion in the report, such as the most interacted posts or 
the most difficult post, and sorting in multiple ways, such as by all courses, by 
specific course or by lecturers.
Searching functions help students and lecturers navigate to the post they are looking 
for easier. By searching by keywords in post title or by lecturers, students have a 
wide yet concise result on relevant topics which allow them to enhance their knowledge 
further.


INSTALLATION
------------

To use this package, first create a database. Then import the database.sql or 
database-sample-data.sql file into that database. After that, change the 
includes/database.inc file with your database details.
To send email by via SMTP, adjust the function send_mail in the includes/functions.inc 
file with your SMTP host, username and password.

Thank you.