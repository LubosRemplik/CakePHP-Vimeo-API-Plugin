# Installation

### Step 1: Download / clone the following plugins: 

 * **Vimeo** to _Plugin/Vimeo_
 * [HttpSocketOauth plugin](https://github.com/ProLoser/http_socket_oauth) (ProLoser fork) to _Plugin/HttpSocketOauth_
 * [Apis plugin](https://github.com/ProLoser/CakePHP-Api-Datasources) to _Plugin/Apis_

### Step 2: Setup your `database.php`

```
var $vimeo = array(
	'datasource' => 'Vimeo.Vimeo',
	'login' => '<vimeo api key>',
	'password' => '<vimeo api secret>',
);
```
### Setp 3: Use Vimeo controllers and models

For Oauth dance in your view
```
echo $this->Html->link('Connect with Vimeo', array(  
	'plugin' => 'vimeo', 'controller' => 'vimeo',  
	'action' => 'connect', bin2hex(serialize(your_cake_url))  
));
```

To fetch data, in use one of Vimeo model in your controler
```
$uses = array('Vimeo.VimeoVideos');  
...  
$videos = $this->VimeoVideos->getList();
```
