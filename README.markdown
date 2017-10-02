Getting Started
===============

- https://github.com/meumobi/sitebuilder/wiki/MeuMobi-Concepts
- https://github.com/meumobi/sitebuilder/wiki/Best-Practices

Using Vagrant
=============

```
vagrant box add sitebuilder http://37.187.106.27/vagrant/sitebuilder.box
vagrant up
```

Set the ENVIRONMENT
===================

```
echo "development" > config/ENVIRONMENT
```

Set the database config
=======================

```
cp config/connections{.sample,}.php
```

Execute migrations
==================

```
$ vagrant ssh
$ cd vagrant
$ php sitebuilder/script/migrate.php
```

Contributing
==================

1. Fork it
2. Create your feature branch (git checkout -b my-new-feature)
3. Commit your changes (git commit -am 'Add some feature')
4. Push to the branch (git push origin my-new-feature)
5. Create new Pull Request
