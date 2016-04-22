<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerAlias meumobi.com
	ServerAlias www.meumobi.com        

	Alias /static/ "/var/www/static/"
	<Directory /var/www/static/>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride None
		Order allow,deny
		allow from all
	</Directory>
	
	Alias /doc/ "/usr/share/doc/"
	<Directory "/usr/share/doc/">
		Options Indexes MultiViews FollowSymLinks
		AllowOverride None
		Order deny,allow
		Deny from all
		Allow from 127.0.0.0/255.0.0.0 ::1/128
	</Directory>

	<Location "/server-status">
		SetHandler server-status
		Order allow,deny
		Allow from all
	</Location>

	RewriteEngine on 
	RewriteCond %{HTTP_HOST} ^www.meumobi.com [NC] 
	RewriteRule ^(.*)$ http://meumobi.com$1 [L,R=301]

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/meumobi/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/meumobi/public>
		Options -Indexes FollowSymLinks MultiViews
		AllowOverride All
	</Directory>

	RewriteLog "|/usr/sbin/rotatelogs /var/log/apache2/meumobi.com/rewrite.%Y-%m-%d 86400"
	RewriteLogLevel 0
	ErrorLog "|/usr/sbin/rotatelogs /var/log/apache2/meumobi.com/error.%Y-%m-%d 86400"
	LogLevel error
	CustomLog "|/usr/sbin/rotatelogs /var/log/apache2/meumobi.com/access.%Y-%m-%d 86400" combined
	ServerSignature Off
</VirtualHost>
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName agencia3.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/agencia3/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/agencia3/public>
		Options -Indexes FollowSymLinks MultiViews
		AllowOverride All
	</Directory>
        
	RewriteLog "|/usr/sbin/rotatelogs /var/log/apache2/partners.meumobi/rewrite.%Y-%m-%d 86400"
	RewriteLogLevel 0
	ErrorLog "|/usr/sbin/rotatelogs /var/log/apache2/partners.meumobi/error.%Y-%m-%d 86400"
	LogLevel error
	CustomLog "|/usr/sbin/rotatelogs /var/log/apache2/partners.meumobi/access.%Y-%m-%d 86400" combined
	ServerSignature Off
</VirtualHost>
