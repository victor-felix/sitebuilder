## Release Notes for meumobi ##

Update these notes using:

```
git log --pretty=format:'* %s' --no-merges rel-2.8.10..HEAD
```

### rel-2.8.10 (20161116) ###

* ENHANCEMENT: Closes #422, nominate a category on latest feed
* FIX: Closes #421, each time I save an event it wrongly convert dates
* BUGFIX: Closes #431, reintroduces logs for push notifications
* Info of how to execute migrations for sitebuilder setup
* HOTFIX: fixes undefined variable

### rel-2.8.9 (20161027) ###

* ENHANCEMENT: Closes #425, includes file extension when processing remote media
* FIX: Closes #420, updates app icon from custom theme page
* FIX: set OneSignal app key
* ENHANCEMENT: Closes #378, enables push notifications to be sent via OneSignal
* ENHANCEMENT: #378, adds OneSignal logic to its service
* ENHANCEMENT: #378, encapsulates Pushwoosh-specific logic into its own service
* Installs onesignal-php-api
* HOTFIX: mail title is different when sent by cli (Import visitors) or dashboard
* ENHANCEMENT: Closes #420, removes themes/application page

### rel-2.8.8 (20160930) ###

* ENHANCEMENT: Closes #415, site.description now allows up to 3000 characters
* ENHANCEMENT: add infomobi theme
* Allow subcategories on investor
* FEATURE: Closes #407, handle remote youtube media
* ENHANCEMENT: better layout for status page
* FEATURE: Closes #356. Monitor response codes of important sites
* FEATURE: monitor age of jobs in the queue
* BUG: fixes undef var warning

### rel-2.8.7 (20160801) ###

* Updates composer packages
* BUG: don't warn on new fields when updating items
* ENHANCEMENT: Closes #406, get mime types for remote media with Mimey
* BUG: Closes #388, use unique name for temp downloaded images
* Whitespace changes
* DOC: add new vhost for employee
* ENHANCEMENT: allow subcategories creation on segment's config
* ENHANCEMENT: reduce size of segment's logos

### rel-2.8.6 (20160726) ###

* ENHANCEMENT: Fetches oldest job for status page
* ENHANCEMENT: add custom bg color on mail layout
* BUG: consider missing medium on enclosure as images instead of generic media
* ENHANCEMENT: Closes #396, save items again just before downloading thumbnails
* FIX: Closes #404, script/check_database raises NOTICE when invalid Urls are empty
* ENHANCEMENT: Closes #399, By default enable extension and set exclusive and NOT html_purifier properties
* BUG: Closes #380, consistent error messages for visitors
* BUG: Closes #387, log when images fail to download
* BUG: Closes #389, don't set alpha channel if it doesn't exist
* ENHANCEMENT: better logging for media thumbnailer
* BUG: don't save job events for workers with no priority
* ENHANCEMENT: Closes #394, update dashboard links. Introduces App/Site Segment Property
* ENHANCEMENT: Closes #393, create segment Employee
* BUG: Closes #381, don't clobber new media with old media
* ENHANCEMENT: update App download link of condmobi
* ENHANCEMENT: Closes #382, Update App download url
* ENHANCEMENT: Closes #383, Update App download url
* BUG: fixes notices from last deploy

### rel-2.8.5 (20160614) ###

* BUG: Closes #377, fetch an empty content range instead of using a HEAD request
* ENHANCEMENT: Closes #276, shows feed import status at /status
* BUG: job event creation was not working completely
* ENHANCEMENT: #276, save events from feed processing
* BUGFIX: don't allow unauthenticated request to clobber user device
* BUGFIX: allows device to be created on login without throwing exception
* BUGFIX: throw Unauthorized if using invalid visitor token
* ENHANCEMENT: Closes #343, shows emails of invited users

### rel-2.8.4 (20160520) ###

* FIX: wrong urls to download Apps
* BUG: Closes 372, fixes "undefined index" notice in polls
* BUG: Closes #370, re-publish items when publish date is set to the past
* BUG: Closes #374, allows the same device to be used by multiple users
* BUG: Closes #373, refresh etags when poll is voted

### rel-2.8.3 (20160503) ###

* Update App links of Google Play Download
* BUG: Closes #364, catch RecordNotFound when trying to delete skin
* ENHANCEMENT: coding standards
* BUG: Closes #365, don't try to parse xml if request is not 200
* BUG: Closes #366, silences warning when URL cannot be fetched
* ENHANCEMENT: Closes #367, allow article's link to be edited
* BUG: get microtimes as floats instead of strings
* Replace wording 'pdf files' by 'External media' to be more generic
* Update config of residence Segment
* Remove unexpected tabs yaml
* Add polls as items.type allowed on comunique-se Segment
* DOC: Closes #361, save updated vhosts of PROD
* BUG: Closes #299, save thumbnails when creating an item with images
* ENHANCEMENT: Closes #257, update item.thumbnails with media.thumbnails
* ENHANCEMENT: Closes #341, log when no devices are found when sending push notif
* ENHANCEMENT: move PushNotificationWorker logic to its own service
* BUG: Closes #360, don't show unpublished items in the search
* BUG: Closes #363, allows 0 as option in polls

### rel-2.8.2 ###

* ENHANCEMENT: Closes #327, display name of visitor on visitors/mail/add
* add .DS_Store as untracked files
* create segment residence with its images
* BUG: Closes #348, don't allow votes on closed polls
* BUG: Closes #351, forces votes to serialize as a hash by using dummy option
* BUG: Closes #352, updates ETags when polls are saved
* BUG: Closes #353, fixes undef var warning in item creation
* ENHANCEMENT: Closes #355, default end date for poll is in 1 week
* BUG: updates logrotate config files
* BUG: fixes poll vote example from API documentation
* BUG: fixes push notifications not being sent for private sites
* ENHANCEMENT: don't show HTML5 errors in feed import
* BUG: Closes #337, use Sites.domain as a fallback for MeuMobi::domain
* ENHANCEMENT: disables cronjobs logging STDOUT to whenever.log
* BUG: fixes push notif not being sent for some private sites

### rel-2.8.1 ###

* ENHANCEMENT: Closes #287, don't try to process unsupported types for thumbnails
* ENHANCEMENT: Closes #277, includes processing time for remote media in logs
* BUG: Closes #252, fixes item pagination on sitebuilder
* BUG: Closes #329, use Sites.domain config for site domain attribute
* Fixes syntax error. Closes #326
* Update dictionary of c-se for new visitor mail
* Don't rely on Sites.domain for generating links
* Fixes undefined vars in invite confirmation mail. Closes #324
* Removes email confirmation when an user accepts an email. Closes #323
* Fixes invite email. Closes #289
* Updates items when media in news feed is updated
* Don't clobber exceptions if they are handled. Closes #321

### rel-2.8 (20160302) ###

* Updates docs for voting on polls. Closes #304
* Includes item_id when catching exceptions in ProcessRemoteMedia. #315
* Removes $_SERVER debug info from logs. Closes #318
* Allows user to update vote. #304
* Bypasses lithium when adding votes to a poll to avoid mongodb issues
* Implements multiple-choice polls
* Removes abstract methods. A repo can implement whatever it wants
* Returns an error response on internal errors
* Shows current site id on header
* Makes poll votes behave like in documentation
* Allows users to vote on polls. #304
* Fixes css mistake related to #308
* Scaffolding for voting in polls. #304
* Fixes new polls having no slots for options
* Minor code cleanup
* Adds Polls item type. #304
* Add demo file to show and test RSS import
* UPDATE: Closes #308, display current site_id on dashboard
* Adds 'manufacturer' to devices. Closes #305
* Allow to send push notif on public sites. Closes #114

### rel-2.7.2 (20160203) ###

* Forces user to renew password when he gets it reset. Closes #301
* Deletes associated devices when deleting a visitor. Closes #302
* Raises extension priority when delete all items from category. Closes #282
* Don't overwrite media types with nulls. Closes #300

### rel-2.7.1 (20160127) ###

* Fixes devices being created with the same UUID over and over
* Also adds android_custom_icon in pushwoosh's payload
* Uses different method for getting pushwoosh android banner
* Fixes missing repository for authenticated API calls

### rel-2.7 (20160126) ###

* Makes site_id an INT all over. Closes #296
* Migrate devices to their own collection. Closes #286
* Move footer informations to Segment's config file and remove unused segments, Closes #297
* Adds badge to android too! Closes #295
* Removes device (de)?hydrating from Visitors. Closes #294
* Sets correct thumbnail size for android banner for pushwoosh
* Adds banner to pushwoosh payload. Closes #293
* Adds custom data to pushwoosh api. Closes #291
* Adds header and custom icon in pushwoosh payload. Closes #290
* Update docs for API related to new Devices endpoint
* Don't update devices if it doesn't belong to the current user
* Finishes up DevicesRepository. Closes #285
* Creates DevicesRepository. WIP #285
* Code cleanup
* Clear imagemagick resources once we're done with them. Closes #288
* Renames VisitorDevice to Device
* Makes visitor-independent devices backwards-compatible
* Creates new API endpoint for "devices". Closes #284
* Creates docs for new API endpoint for "devices". Closes #283
* Adds the README with install instructions. Closes #136
* Removes unused files
* Allow LOGLEVEL env to overwrite Log.Level. Closes #281

### rel-2.6.7 (20151201) ###

* Lower extension priority if events feed failed to download. Closes #267
* Migrates UpdateFeedsWorker to use script/run_worker.php
* Creates script/run_worker.php for running workers manually
* Removes MediaThumbnailerWorker. Superseded by ProcessRemoteMediaWorker.
* Improves logging in general for workers, UpdateNewsFeed and ProcessRemoteMedia
* Logs all PHP errors to log/sitebuilder.log. Closes #272
* Updates composer.json dependencies
* Log events for command line scripts. Closes #273
* Updates environment for comunique-se's DB connections

### rel-2.6.6 (20151125) ###

* Use references when passing counters to a closure
* Only lower extension priority when it's HIGH. Closes #220
* Remove alpha channel from PDF->PNG preview. Closes #258
* Major refactoring of the media infrastructure
* General code cleaning
* Remove media thumbnail properties from UI form. Closes #248
* FIX, use fullpath on site logo of visitor/mail invite
* Add weblinks as business settings on segment investor
* Finishes php script successfully

### rel-2.6.5 (20151116) ###
* Fixes logs for MediaThumbnailerWorker
* Don't urldecode() links to enclosures. Closes #263
* Actually save the media thumbnail
* Use thumbnail object instead of bare URL string. Closes #262
* Don't try to create thumbnails when media type is known to be unsupported. Closes #260
* Don't show successful log for failed thumbnail creation. Closes #256
* Don't overwrite medias for items. Closes #253 and #254
* Configure integration to use int.meumobi.com
* Saves full absolute path for pdf previews. Closes #255
* New item type: 'Contacts'. Closes #246
* New item type: 'Files'. Closes #247
* Don't raise an exception when deleting already removed site. Fixes #251
* Formatting changes
* Uses full path for destination images in the PdfThumbnailer
* Update media also on item updates. Closes #250
* Shows actual id instead of "[object] ..." on worker logs
* Downgrade Capistrano. New version requires Ruby 2.0+
* enable API access from all domains on enterprise segment
* Drops capistrano-git-submodule. Fixes #221
* Update subject of the user emails
* Replace Mapper with MeuMobi to generate urls
* Make the MeuMobi mapper default on Html.link helper
* rename rimobi env to 'comunique-se'
* Add dynamic segments to mail templates
* Update mails templates to use the html link helper
* Translate datatable based on user settings, closes #223
* Allow set the url Mapper of html link helper, closes #141
* fixup! Disable the extension is site not exists, closes #227
* Load locale based on site settings on command line scripts
* Use the ResetVisitorPassword service in the reset_visitor_password script
* Extract segment loading from the bootstrap dispatcher
* Add Updatable role to extract common behaviors from workers
* Allow API Access from all domains for investors segment
* Remove duplicated RecordNotFoundException
* Disable the extension is site not exists, closes #227
* Check if site exists before persist an item
* Add site getter in category model to remove code duplication
* Change extension import mode input type on Events Feed
* Allows to deploy a specific tag
* Refac media/pdf thumbnailers Now MediaThumbnailerWorker delegates all the work to MediaThumbnailer service
* Create media thumbnails it works only for pdf files
* Make Images->getPath() public
* Add pdf worker to extract media thumbs
* Add PdfThumbnailer simple service with a method that gets a remote pdf, extract its thumbnail and returns the local file path

### rel-2.6.4 (20150921) ###
* Don't delete untouched items when bulk importing. Closes #228
* Try to update item only if it has an id
* Fix invalid site photos on performance response
* Change extension import mode imput type to select
* Remove log statement
* add mail tokens on enterprise segments, Close #210
* Extract the delete observers to the RemoveSite service
* Fix missing site in CreateItem service logs, closes #203
* Update addMediaFileSize conditional that checks if an item has any media
* Fix missing extension id in the Update Feeds and Events log, closes #200
* Fixes feed update. Option was not getting passed
* Updates feed items. Closes #68.
* add RELEASNOTES
* Allow make deploy by tags, closes #221
* Adds the UpdateItem service
* Renames ItemCreation to CreateItem
* Extract toJSONPresenter from Sites model to SitePresenter
* Code cleaning of Sites model
* Remove related photos, slash and icon when site is removed, closes #219
* Remove related visitors when site is removed, closes #191
* Refactor VisitorsRepository hydrate and dehydrate methods, closes #211
* Fix typo in the import_mode migration, closes #214
* Don't sort feed items by date. Closes #179.
* Add migration to set import mode of events feeds, closes #205
* Remove unnecessary logrotate config
* Create a logrorate config for each environment
* Add logrataion config file sample
* update mails forgot password and invite visitors, CLoses #185 and Closes #188
* Fix subject string format
* Add migration to set correct item published values, closes #192
* Make Visitors authentication case insensitive, closes #130
* Change Mailer log level
* Extract reset password email from User model, closes #173
* Set correct status for errors in API /mail request, closes #168
* Add migration to convert visitors.last_login from string to Date, closes #193
* Add created and modified fields to Visitors, closes #7
* Code cleaning
* Add excludeItems method to BulkImport service
* Add migration to set import mode on Extensions
* Remove addMediaFileSize option from ItemCreation service
* Use BulkImport on UpdateNewsFeed
* Remove Restaurants and Users. Related to #125
* Remove ExtendedArticles item type. Related to #125
* Improme item creation logs, closes #184
* Add import mode to Rss extension
* Rename BulkImport constants
* Fix the extension import mode form field
* Fix the exclusive removal of items
* Enable event update in the update service
* Code cleaning
* Fix extract items and created log
* Refactor events feed import
* Add import_mode to Rss extension
* Add title to helper generated radio imputs
* Add BulkImportItems service
* udpate layouts to use tokens of segments #131
* Uses dummy HTML for feed import when we have nothing. Closes #177
* Saves enclosures as images again. Closes #180
* Code cleaning
* Set the correct timezone for visitor last login date, closes #147
* Clean ImportVisitorsCsvService
* Update ImportVisitorsCsvService log
* Only send reinvite email if visitor not logged in
* Make import_visitors options consistent, closes #146
* Validate visitor before import, closes #133
* Add help documentation to import visitors script, closes #140
* Fix missing fields when log not found
* Fix missing date and status of some imported visitors
* Replace Logger for an optional message print
* Trim email in finder to prevend visitors duplication
* Not throw exception if visitor not exist
* Fix regex filter of visitors check import script
* Remove the unused abstract methos from ImportCsvService
* Allow resend the visitor email, closes #144
* Create check_import_visitors script, closes #142

### rel-2.6.3 (201507XX) ###

* Update Segments layout and dictionary: tibox, infobox, comunique-se
* Prevent send push notification if no device available, closes #151
* Overwrite default icon colors on the custom segment style, closes #164
* Updates Vagrant configurations
* Adds vagrant configuration
* Extract feed parsing and item creation from UpdateFeedsWorker. Closes #120
* Log payload of push notification request, closes #155
* Allow delete extensions in the webinterface, closes #85
* Set the correct guid in the Rss export, closes #21
* update assets of infobox segment
* fix inconsistencies on users/forms, forgot and reset passwords
* update config of segments to add fields on dashboard
* add 2 new segments: tibox, comunique-se
* Refactor push notification
* Use consistent log key names for push notification logs, closes #156
* Fix is_published filtering of items/latest, closes #152
* Prevent remove items when the extension is disabled, closes #82
* FIX mispell InfoBox
* Revert "hide deprecated warnings"
* Re-add token replacing for segments
* Code cleading of WorkerManager,dispatcher and ItemsController.
* Remove unused ItemsController::add action
* Validates item types and site and clean item creation code, closes #117
* Create items using the ItemCreation service, closes #103, closes #101 and closes #97
* Create the service ItemCreation
* Prevent fatal error on events feed update, Closes #118
* Fix #122, correct visitors login response
* hide deprecated warnings
* Add version, platform and app_build fields to visitors devices and refactor device creation using the CreateOrUpdateDevice service closing #110
* fix misspelled message on visitiors and closes #119
* Reverts and reopens #68. Don't update feed articles anymore
* Do not cache the item's parent category. Closes #104
* update push logs
* fix #95, invalid media file size job
* improve push logs, closes #94 and prevent send notification for disabled categories
* fix remove category script and closes #89
* fix remove sites script and closes #88
* Enables the visitor to reset its own password. Closes #75
* Removing legacy config
* remove pubdate and closes #87
* update workers log and closes #86
* order by publish date on GET /latest request and closes #72
* fix #77
* prevent item.created bug and fix #72
* Reverses order in which feeds are inserted. Closes #80
* Working on #80: only process articles we need to
* Fixes warnings in mongodb
* Includes group information when presenting items. Closes #76
* Adds /visitors/forgot_password. Closes #75
* Allows PUT /visitors/devices/{uuid} to also create devices. Closes #74
* Don't show items from hidden categories on /items/latest. Fixes #51
* update feeds logs
* update feeds logs
* set old extensions priority and also fixes #12
* allow deploy to a specifc branch, eg. cap integration deploy branch=super_feature
* update update feeds logs
* setup recurring feed update worker
* update update feeds log
* update IOS manual
* update logs
* update site controller
* clean Articles model
* refactor feed import and move UpdateFeedsService to workers

### rel-2.6.1 (201505XX) ###

* update docs and visitors email
* Don't try to generate an etag if we don't have a site
* Returns the site's domain instead of the id
* Allows a visitor to login without knowing the site. Closes #2
* fix #66
* fix import duplicate key exception and closes #69
* update buttons helper and closes #31
* Don't try to delete skin if it doesn't exist.
* Backup databases on deploy and closes #61
* clean scripts
* clean dashboard styles and closes #64
* fix visitors report when aren't available visitors and closes #63
* fix javascript error on site creation and fix #62
* remove old move row buttons
* add move buttons and closes #29
* finish add media file size and closes #59
* fix add media file size recursive bug
* add Media File size worker
