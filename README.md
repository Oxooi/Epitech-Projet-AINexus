# Epitech Projet: AINexus
 
## Description
This is [AI-Nexus] project. 
This was a web development project for my school, Epitech, during my second year.

It was an open-ended project, allowing us to choose the technology (PHP or JS) and the theme (E-Commerce, Social Network, Blog, etc.). I decided to create a social network inspired by Twitter and Reddit, focusing on a single topic: Artificial Intelligence. The project was built using the Symfony 6 framework (PHP).

Unfortunately, it was eventually set aside and left incomplete.


## Installation
To use this project, you need to have [Composer](https://getcomposer.org/) installed.

1. Clone the repository:

    ```bash
    git clone https://github.com/yourusername/yourproject.git
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

## Requirements
- PHP 8.1 or later
- Other dependencies listed in the `composer.json` file

## Configuration
You can configure the project by modifying the `config` section in the `composer.json` file.

```json
{
    "config": {
        "allow-plugins": {
            "composer/package-versions-deprecated": true,
            "symfony/flex": true,
            "symfony/runtime": true
        },
        "optimize-autoloader": true,
        "preferred-install": {
            "*": "dist"
        },
        "sort-packages": true
    }
}
