<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerAlias meumobi.com
	ServerAlias www.meumobi.com        
	
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

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/meumobi/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/meumobi/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName kinghost.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/kinghost/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/kinghost/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName dailyfresh.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/dailyfresh/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/dailyfresh/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName oi.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/oi/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/oi/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName enterprise.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/enterprise/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/enterprise/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName ageisobar.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/ageisobar/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/ageisobar/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName fbiz.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/fbiz/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/fbiz/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName pontomobi.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/pontomobi/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/pontomobi/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName fbiz.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/fbiz/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/fbiz/public>
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
<VirtualHost *:80>
	ServerAdmin admin@meumobi.com
	ServerName 1440group.meumobilesite.com

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/1440group/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/1440group/public>
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
