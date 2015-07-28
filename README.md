# usershell
You can use this plugin for create groups and users from configure settings file.


### Load plugin

```
:: bootstrap.php ::
```
```php
CakePlugin::load('UserShell');

### Configure

```
:: core-default.php ::
```
```php
Configure::write('Groups', array(
		'modelName' => 'Group',
		'data' => array(
			'admin' => array(
				'id' => 1,
				'name' => 'Administrators',
				'status' => 0,
			),
			'member' => array(
				'id' => 2,
				'name' => 'Members',
				'status' => 0,
			)
		),
		'permissions' => array(
			'admin' => array(
				'id' => 1,
				'deny' => [],
				'allow' => [
					'controllers',
				],
			),
			'others' => array(
				'id !=' => [1],
				'deny' => array(
					'controllers/users/index',
					'controllers/users/edit',
					'controllers/users/delete',
					'controllers/users/create',
					'controllers/groups',
				),
				'allow' => array(
					'controllers',
				)
			),
		),
	));
```

```php
	Configure::write('Users', array(
		'modelName' => 'User',
		'data' => array(
			'admin' => array(
				'id' => 1,
				'email' => 'example@example.com',
				'password' => 'example',
				'repead-password' => 'example',
				'status' => 1,
				'group_id' => 1,
			),
		),
		'conditions' => array(
			'status' => array(1, 2),
		),
	));
```


### Usage
    
Group will be created if they don`t exists    
```
bin/cake UserShall.group init
```

Add missing groups
```
bin/cake UserShall.group complete
```

Just set permissions for groups
```
bin/cake UserShall.group set_permissions
```

Users will be created if they don`t exists
```
bin/cake UserShall.user create
```

Add missing users
```
bin/cake UserShall.user complete
```