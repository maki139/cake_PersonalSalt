# PersonalSaltComponent

CakePHP で Security.salt + 個人 salt + パスワードの認証方法を提供します。

- Require: CakePHP 2.4+
- ユーザーテーブルに個人 salt を保存するフィールドを追加

## How to use
AppController.php
```php
App::uses('Controller', 'Controller');

class AppController extends Controller {

	public $components = array(
		'Auth' => array(
			'authenticate' => array(
				'PersonalSalt' => array(
					'fields' => array(
						'username' => 'username', // [optional] default: username
						'password' => 'password', // [optional] default: password
						'salt' => 'password_salt', // 個人 salt を保存するテーブル
					),
					'hashType' => 'blowfish', // [optional] ハッシュ方式 (md5, sha1, sha256, blowfish)
				)
			),
		)
	);
}
```

User Model
```php
App::uses('AppModel', 'Model');
App::uses('SaltPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

	public function beforeSave($options = array()) {
		if (! empty($this->data[$this->name]['password']) {
			$hasher = new SaltPasswordHasher(array('hashType' => 'blowfish')); 
			$salt = $hasher->generateSalt(); // 引数に salt の文字長を指定可能
			$this->data[$this->name]['password_salt'] = $salt;
			$this->data[$this->name]['password'] = $hasher->hash($this->data[$this->name]['password'], $salt);
		}

		return parent::beforeSave($options);
	}
}
```
