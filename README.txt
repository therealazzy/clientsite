To deploy this system drag this file structure into the folder on your server were the HTML files are usually stored.
-----------------------------------------------------------------------------------------------------------------------

You must also edit Database.php and change the login credentials to your own database with your own tables.
There must be two tables in this database: project and user.

project must contain: id, projectName, customerName, description, budget, deadline, projectManager, userID
user must contain: id, email, password, company, fullName, phoneNumber

Where userID is a foreign key to the id in the user table.
And description is set to a large enough variable such as mediumtext.

-----------------------------------------------------------------------------------------------------------------------


You must also edit the printSend function in ProjectDataSet.php to use your own server's configuration for sending mail.
And create a printSendUpdate function in ProjectDataSet.php to use your own server's configuration for sending an update email.


Either the printSend() function or the sendEmail.php can be used to send emails, you should choose one in accordance to your server specification
and comment out the other


-----------------------------------------------------------------------------------------------------------------------


Hyperlinks also need to be added as required to the footer in footer.phtml such as Terms, Privacy Policy and any social media links.


The contact form on the homepage must also be configured to use your servers mail in index.php.

-----------------------------------------------------------------------------------------------------------------------