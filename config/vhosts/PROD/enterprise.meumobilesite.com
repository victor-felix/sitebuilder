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

