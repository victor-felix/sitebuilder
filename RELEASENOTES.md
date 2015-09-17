## Release Notes for meumobi ##

Update these notes using: git log --pretty=format:'* %s' --no-merges rel-2.6.3..HEAD
 
### rel-2.6.4 (201509XX) ###
* Adds the UpdateItem service
* Renames ItemCreation to CreateItem
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
* Adds "pubdate" to JSON responses
* Adds the singular "error" to the visitor login response
* Fixes 'Undefined variable: downloadStats'. Closes #176
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
