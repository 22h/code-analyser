# code analyser
This repository is experimental.

With this repository I want to solve some problems of my own projects. For example, incorrect namespaces in uni tests.

PHP Unit doesn't need namespaces and therefore they are often wrong.

## example output
```bash
$ php code-analyser code-analyser:namespaces
 
Lookup autoload paths
---------------------
 
 ------ ------------------------------- --------
  env    namespace                       folder
 ------ ------------------------------- --------
  prod   TwentyTwo\CodeAnalyser\         src
  dev    TwentyTwo\CodeAnalyser\Tests\   tests/
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