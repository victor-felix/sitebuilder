<VirtualHost *:80>
        ServerAdmin admin@meumobi.com
        ServerName meumobi.com
	Alias /static/ "/var/www/static/"
	<Directory /var/www/static/>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride None
		Order allow,deny
		allow from all
	</Directory>

	DocumentRoot /home/meumobi/PROJECTS/meumobi.com/current/segments/meumobi/public
			<Directory /home/meumobi/PROJECTS/meumobi.com/current/segments/meumobi/public>
							Options -Indexes FollowSymLinks MultiViews
							AllowOverride All
			</Directory>
			ErrorLog /var/log/apache2/meumobi.com/error.log 
			LogLevel warn
			CustomLog /var/log/apache2/meumobi.com/access.log combined 
			ServerSignature Off
</VirtualHost>
