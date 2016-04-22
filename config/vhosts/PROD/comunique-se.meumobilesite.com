<VirtualHost *:80>
	ServerAdmin admin@meumobilesite.com
	ServerName comunique-se.meumobilesite.com
	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/comunique-se/public
	<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/comunique-se/public/>
		Options -Indexes FollowSymLinks MultiViews
		AllowOverride All
	</Directory>
	ErrorLog "|/usr/sbin/rotatelogs /var/log/apache2/meumobi.com/error.%Y-%m-%d 86400"
	LogLevel warn
	CustomLog "|/usr/sbin/rotatelogs /var/log/apache2/meumobi.com/access.%Y-%m-%d 86400" combined
	ServerSignature Off
</VirtualHost>
