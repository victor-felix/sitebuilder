<VirtualHost *:80>
        ServerAdmin admin@meumobi.com
        ServerName meu-site-manager.meumobi.com
        DocumentRoot /home/meumobi/PROJECTS/meu-site-manager.meumobi.com/current/public
        <Directory /home/meumobi/PROJECTS/meu-site-manager.meumobi.com/current/public/>
                Options -Indexes FollowSymLinks MultiViews
                AllowOverride All
        </Directory>
        ErrorLog /var/log/apache2/meu-site-manager.meumobi.com/error.log 
        LogLevel warn
        CustomLog /var/log/apache2/meu-site-manager.meumobi.com/access.log combined 
        ServerSignature Off
</VirtualHost>
