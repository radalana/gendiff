# Difference Calculator
[![Actions Status](https://github.com/radalana/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/radalana/php-project-48/actions)
[![Test Coverage](https://api.codeclimate.com/v1/badges/00755fc20ade9882f670/test_coverage)](https://codeclimate.com/github/radalana/php-project-48/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/00755fc20ade9882f670/maintainability)](https://codeclimate.com/github/radalana/php-project-48/maintainability)
![Github Actions Status](https://github.com/radalana/php-project-48/actions/workflows/main.yml/badge.svg)

## Description
The 'Difference Calculator' is a utility designed to identify differences between structured data. It supports JSON and YAML formats, employing a recursive approach to build an internal difference tree. The output can be presented in one of three formats: stylish, plain, or json. It's crucial to highlight that the comparison is conducted at the data level, rather than comparing raw text strings. This ensures accurate identification of changes within structured data, regardless of their textual representation.

## Requirements
- PHP >= 8.1.2
## Technology Stack:
- PHP 8.1
- Php-cli-tools
- Code Climate
- Make
- Composer
- PHP_CodeSniffer
## Demonstration
##### Comparing flat json files
[![asciicast](https://asciinema.org/a/QJOpdPd9cUB6vv9ljtOziQNVJ.svg)](https://asciinema.org/a/QJOpdPd9cUB6vv9ljtOziQNVJ)

##### Comparing flat yaml files
[![asciicast](https://asciinema.org/a/z3VYMe0nKQniEIrkcI2abvdxf.svg)](https://asciinema.org/a/z3VYMe0nKQniEIrkcI2abvdxf)

##### Comparing nested structures
[![asciicast](https://asciinema.org/a/oWFqTyXxJQ6ZKhVat5OEFIB16.svg)](https://asciinema.org/a/oWFqTyXxJQ6ZKhVat5OEFIB16)

##### Generating report in plain text format
[![asciicast](https://asciinema.org/a/uoFX6jFs6H1mmE9dNEiSHyVp8.svg)](https://asciinema.org/a/uoFX6jFs6H1mmE9dNEiSHyVp8)

##### Generating report in json format
[![asciicast](https://asciinema.org/a/r74bRH04lsP9vaTeUNBW4OOSo.svg)](https://asciinema.org/a/r74bRH04lsP9vaTeUNBW4OOSo)
