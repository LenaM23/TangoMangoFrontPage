PERMISSION TO MODIFY

* You may not use this code to make a website for Argentine tango in the U.S.

* I don't have time to provide support, so you must figure out everything yourself.

* Somewhere on your About page, give credit to TangoMango by David Hundsness. (But don't use either as the major branding or copyright for your site.)


SYSTEM REQUIREMENTS

PHP 5.x
MySQL 5.x (maybe 4.x)


HOW IT WORKS

Anyone can update events. Originally there were a few instances of vandalism, so a few safeguards are in place:
* You must login to post or edit an event. This is for accountability. (It is not required to login just to look at listings.)
* Only event owners can delete their own events. (But anyone can login to edit anyone else's event, even if they're not the event owner. This is not considered high-security, as anyone can edit an event to remove the owner, then delete their listing, but there have not been any significant complaints of abuses.)
* In one case I had to black-list the IP addresses of someone who was acting as a vigilante.

Logins require only an email address, no password. This is not considered a high-security application.

Cookies are used to identify each user so their preferences can be saved in the database.

When you click an event on the Calendar it uses a frameset to retrieve the event description from the server (like AJAX).

The filters on the Calendar use Javascript to show and hide events, so the page rarely needs to be reloaded.

The whole site is designed to be self-maintaining. Event owners update their listings, and a cronjob cleans up the database every week.

To highlight special events, the listing owner must follow the directions on the edit screen. This sends an email to you, with a link for you to edit their listing. When you save the event, it approves the Special Event status. If you do not save it, the event is not highlighted as special, and the user cannot re-request it.


ADMIN ACCESS

yourdomain.com/code/logging.php : Sets the mode for administrative access. Only you should use this, never for the public. The three options are:
Public = Normal mode the way the public sees it.
Admin = Same as public, except you can set or approve which listings are highlighted as Special Events.
Logging = Show complete diagnostic log on every page for trouble-shooting.


CRON JOBS & LOGS

yourdomain.com/code/purge.php : Every Sunday morning delete events over one month old and purge aborted users and other unused data. This keeps the database from growing infinitely, so it runs efficiently with no maintenance.
yourdomain.com/code/purglog.html : Log of last purge

yourdomain.com/code/sendreminders.php : Twice a month send email to all event owners reminding them to review and update their listings. This has proven very effective in keeping information up to date, otherwise event owners forget to extend their listings before they expire and information becomes out of date.
yourdomain.com/code/emaillog.php : Log of last email batch

Whenever someone edits an event it emails you a log. This is for diagnostics and if anyone complains about vandalism.


UNFINISHED

The database is prepared for multiple countries, but the user interface allows only the United States.

This is not optimized for mobile browsers.

The calendar is not well suited for festivals or events that people would want to travel for. That might be a new page to add.
