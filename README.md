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

### Step 3: Install the Apis-OAuth Component for authentication

```
MyController extends AppController {
	var $components = array(
		'Apis.Oauth' => 'vimeo',
	);
	
	function connect() {
		$this->Oauth->connect();
	}
	
	function vimeo_callback() {
		$this->Oauth->callback();
	}
}
```

### Step 4: Use the datasource normally 
Check the [wiki](https://github.com/ProLoser/CakePHP-Vimeo/wiki) for available commands & usage

```
Class MyModel extends AppModel {

	function readProfile() {
		$this->setDataSource('vimeo');
		$data = $this->find('all', array(
			'path' => 'people/~',
			'fields' => array(
				'first-name', 'last-name', 'summary', 'specialties', 'associations', 'honors', 'interests', 'twitter-accounts', 
				'positions' => array('title', 'summary', 'start-date', 'end-date', 'is-current', 'company'), 
				'educations', 
				'certifications',
				'skills' => array('id', 'skill', 'proficiency', 'years'), 
				'recommendations-received',
			),
		));
		$this->setDataSource('default');
	}
}
```