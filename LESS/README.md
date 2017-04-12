//#!/compile = true

# How To Compile .less files manually

## 1. Requirements

* C++ Compilater
* node.js
* npm
* less
* less-plugin-clean-css

## 2. How to install Node.js

### 2.1. Get Node.js

* First, make directory named `node.js` in `etc` folder with the following command :

```bash
# Create directory and then go into it
mkdir /etc/node.js && cd /etc/node.js
```

* Download the latest package from `nodejs.org` here : http://nodejs.org/dist/latest/
* Download the package named : `node-vX.Y.0.tar.gz` with the following command :

```bash
wget http://nodejs.org/dist/latest/node-vX.Y.0.tar.gz
```

### 2.2. Extract and install Node.js

* First, you need to extract the package :

```bash
tar xzvf node-vX.Y.0.tar.gz
```

* Then go into the newly folder created by the extraction


```bash
cd node-vX.Y.0
```

* Check if you had a C++ compiler


```bash
./configure
```

* If there is no C++ compilator, you will get this message : <span style="color: red"> No c compiler found </span>
* In that case, you have to install one. I advise to install `build-essential`


```bash
# To do only if you get 'no c compiler found'
apt-get install build-essential
```

* Once `build-essential` is installed, launch the installation :


```bash
./configure

make && make install
```

* Note : The node.js v7.9.0 on Raspberry Pi B+ took more than 5 hours to be completed...

Source : http://www.symfony-grenoble.fr/fr/75/installer-less-via-nodejs-et-npm-sur-debian/

## 3. Get Node.js modules

### 3.1. Download LESS compilator

With that Node.js install, the module manager `npm` is include. Adding module is really easy :

#### 3.1.1 Get lessc

Type the following command to get `lessc` compiler :

```bash
npm install -g less
```

#### 3.1.1. Get CSS minifier plugin

```bash
npm install -g less-plugin-clean-css
```

## 4. How to compile less file

### 4.1 Raw compilation

```bash
lessc you_less_file.less you_output_css_file.css
```

### 4.1. Minified compilation

```bash
lessc --clean-css you_less_file.less you_output_css_file.css
```

Source : http://stackoverflow.com/questions/20906286/remove-less-comments-on-compile



