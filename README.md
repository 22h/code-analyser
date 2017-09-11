# code analyser
**This repository is experimental.**

With this repository I want to solve some problems of my own projects. For example, incorrect namespaces in uni tests.

PHP Unit doesn't need namespaces and therefore they are often wrong.

## install
you only need composer
```
composer require 22h/code-analyser --dev
```

## commands
search exceptions in all autoload folders
```
php bin/code-analyser code-analyser:exceptions
```
search exceptions in vendor folder recursive
```
php bin/code-analyser code-analyser:exceptions -f vendor
```
search incorrect namespaces in autoload folders
```
php bin/code-analyser code-analyser:namespaces
```
search incorrect namespaces in vendor folder recursive
```
php bin/code-analyser code-analyser:namespaces -f vendor
```

## example output namespace
```shell
$ php code-analyser code-analyser:namespaces
 
Lookup autoload paths
---------------------
 
 ------ ------------------------------- --------
  env    namespace                       folder
 ------ ------------------------------- --------
  prod   TwentyTwo\CodeAnalyser\         src
  dev    TwentyTwo\CodeAnalyser\Tests\   tests
 ------ ------------------------------- --------
 
Search matching files
---------------------
 
 Find 11 matching files in directories
 
Search incorrect namespaces
---------------------------
 
 11/11 [============================] 100%
 
List incorrect namespaces
-------------------------
 
 ------------------- ------------------------------------
  File                tests/\Test\Wrang.php
  Current Namespace   TwentyTwo\CodeAnalyserA\Tests\Test
  New Namespace       TwentyTwo\CodeAnalyser\Tests\Test
 ------------------- ------------------------------------
 
 ------------------- -------------------------------
  File                tests/\Wrong.php
  Current Namespace   TwentyTwo\CodeAnalyser2\Tests
  New Namespace       TwentyTwo\CodeAnalyser\Tests
 ------------------- -------------------------------
```

## example output exceptions
```shell
$ php code-analyser code-analyser:exceptions
 
Lookup autoload paths
---------------------
 
 ------ ------------------------------- --------
  env    namespace                       folder
 ------ ------------------------------- --------
  prod   TwentyTwo\CodeAnalyser\         src
  dev    TwentyTwo\CodeAnalyser\Tests\   tests
 ------ ------------------------------- --------
 
Search matching files
---------------------
 
 Find 12 matching files in directories
 
Search exceptions
-----------------
 
 12/12 [============================] 100%
 
List founded exceptions
-----------------------
 
 ------------------------------- ----------------------------------
  exception                       files
 ------------------------------- ----------------------------------
  ComposerFileNotFoundException   src\Composer.php
  ComposerFileNotFoundException   tests/\Test\Wrang.php
  FileNotFoundException           src\Autoload\CheckFile.php
  FileNotFoundException           src\FindExceptions\CheckFile.php
  Exception                       tests/\Test\Wrang.php
 ------------------------------- ----------------------------------
 
List grouped exceptions
-----------------------
 
 ------------------------------- -------
  exception                       count
 ------------------------------- -------
  ComposerFileNotFoundException   2
  FileNotFoundException           2
  Exception                       1
 ------------------------------- -------
 
 [OK] find 5 exceptions
 
```