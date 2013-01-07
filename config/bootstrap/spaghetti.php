<?php

define('SPAGHETTI_ROOT', LIB_ROOT);

require 'lib/core/common/Config.php';
require 'lib/core/common/Inflector.php';
require 'lib/core/common/Utils.php';
require 'lib/core/common/Exceptions.php';
require 'lib/core/common/String.php';
require 'lib/core/common/Filesystem.php';
require 'lib/core/common/Hookable.php';
require 'lib/log/KLogger.php';
require 'lib/core/debug/Debug.php';
require 'lib/core/dispatcher/Dispatcher.php';
require 'lib/core/dispatcher/Mapper.php';
require 'lib/core/model/Model.php';
require 'lib/core/controller/Controller.php';
require 'lib/core/view/View.php';
require 'lib/yaml_dictionary/YamlDictionary.php';
require 'lib/i18n/I18n.php';
