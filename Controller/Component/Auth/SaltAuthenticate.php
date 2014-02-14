<?php
App::uses('FormAuthenticate', 'Controller/Component/Auth');
App::uses('SaltPasswordHasher', 'Controller/Component/Auth');

/**
 * PersonalSalt Authenticate
 *
 * @copyright     Copyright (c) maki674
 * @link          https://github.com/maki674/cake_PersonalSalt
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
class PersonalSaltAuthenticate extends FormAuthenticate {

/**
 * Authenticates the identity contained in a request. Will use the `settings.userModel`, and `settings.fields`
 * to find POST data that is used to find a matching record in the `settings.userModel`. Will return false if
 * there is no post data, either username or password is missing, or if the scope conditions have not been met.
 *
 * @param CakeRequest $request The request that contains login information.
 * @param CakeResponse $response Unused response object.
 * @return mixed False on login failure. An array of User data on success.
 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$userModel = $this->settings['userModel'];
		list(, $model) = pluginSplit($userModel);

		$fields = $this->settings['fields'];
		if (!$this->_checkFields($request, $model, $fields)) {
			return false;
		}

		$username = $request->data[$model][$fields['username']];
		$password = $request->data[$model][$fields['password']];

		// Get personal salt
		$user = ClassRegistry::init($userModel)->find('first', array(
			'conditions' => array(
				$model . '.' . $fields['username'] => $username
			)
		));

		if (! $user) { // not found user data
			return false;
		}

		$hasher = new SaltPasswordHasher($this->settings);
		$result = $hasher->check($password, $user[$model][$fields['password']], $user[$model][$fields['salt']]);

		if ($result) {
			return $user[$model];
		} else {
			return false;
		}
	}
}