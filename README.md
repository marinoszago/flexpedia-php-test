# Flexpedia Invoice System Project

Created by Marinos Zagkotsis (https://marinoszago.github.io/)

If anything occurs email me at: 

```
marinoszagkotsis@gmail.com
```
##### An Invoice system built with Quasar Framework, VueJs and PHP as a Single Page Application (SPA)

### Tech

* [VueJS] 
* [PHP]
* [Quasar Framework] 
* [MySQL]
* [CSS]
* [HTML]


### Running the project

##### Disclaimer: The installation refers to a [xampp] server. The project is already built and you do not need to install it just follow the following instructions to run. If you want to rebuild it see the last section.

The project is already build via Quasar CLI and Quasar Framework (using npm), however,  it needs [xampp] so please download it if you do not have a server to run [php]. You also need a [php] of version >= 5.6 .

Clone the project or download it as a zip and extract inside the htdocs folder of the xampp installation. When you do that you should have the following path: 

```
xampp/htdocs/flexpedia-php-test
```
The database of the application is stored in:
```
xampp/htdocs/flexpedia-php-test/backend/src/resources
```
so be sure to import it first if it is not installed.

To run the application open xampp start apache and phpmyadmin and then open your favorite browser (mine is chrome) and paste the following link and hit enter: 
```
http://localhost/flexpedia-php-test/frontend/dist/spa/
```
The application should open and see Home screen.

### Information about the project
Inside the project you can see two folders:
* Frontend - The front end interface of the web application using Vue and Quasar
* Backend - The back end of the web application using PHP 

The build application folder exists in: 
```
xampp/htdocs/flexpedia-php-test/frontend/dist/spa
```

while inside: 
```
xampp/htdocs/flexpedia-php-test/frontend/src
```
lies the code used to create the interface. 

Additionally inside: 
```
xampp/htdocs/flexpedia-php-test/backend/src
```
lies the code for the backend of the application and the API that is used.

### Installation
In case you want to rebuild the application you need to have the following installed: 
* [node.js] - this will install node.js and npm to download packages 
* [PHP]
* [Quasar Framework cli] - quasar cli and quasar

After you installed node.js open Node.js Command line and type: 
```
 npm install -g @quasar/cli
```

after that cd into the project's folder inside frontend and type:
```
 quasar dev
```

### Troubleshooting

If any trouble occurs try first to update the npm packages inside the frontend folder via deleting node_modules folder and typing: 
```
 npm install
```


## Thank you!!!

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)


   [marinoszago]: <https://github.com/marinoszago/>
   [git-repo-url]: <https://github.com/marinoszago/flexpedia-php-test>
   [HTML]: <https://en.wikipedia.org/wiki/HTML>
   [CSS]: <https://www.w3.org/Style/CSS/Overview.en.html>
   [MySQL]: <https://www.mysql.com/>
   [Quasar Framework]: <https://quasar.dev/>
   [PHP]: <https://www.php.net/>
   [VueJS]: <https://vuejs.org/>
   [xampp]: <https://www.apachefriends.org/index.html>
   [Quasar Framework cli]: <https://quasar.dev/quasar-cli/installation>
   [node.js]: <https://nodejs.org/en/>
   

