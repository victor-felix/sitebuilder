## Release Notes for meumobi ##

Update these notes using: git log --pretty=format:'* %s' --no-merges rel-2.7.1..HEAD

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
