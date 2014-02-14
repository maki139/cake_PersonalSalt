<?php
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
App::uses('Security', 'Utility');

/**
 * Generate personal salt class
 *
 * Input: Security.salt + Input string + Personal salt
 *
 * Since CakePHP 2.4+
 *
 * @copyright     Copyright (c) maki674
 * @link          https://github.com/maki674/cake_PersonalSalt
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
class SaltPasswordHasher extends SimplePasswordHasher {

	/**
	 * Config for this object.
	 *
	 * @var array
	 */
	protected $_config = array('hashType' => null);

	/**
	 * Generate random salt
	 *
	 * @param integer $length Generate salt length
	 * @return string
	 */
	public static function generateSalt($length = 30) {
		$strinit = "abcdefghkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ012345679";
		$strarray = preg_split("//", $strinit, 0, PREG_SPLIT_NO_EMPTY);
		for ($i = 0, $str = null; $i < $length; $i++) {
			$str .= $strarray[array_rand($strarray, 1)];
		}

		return $str;
	}

	/**
	 * Hash password with salt
	 *
	 * @param string $password Plain text password to hash
	 * @param string $salt Personal salt
	 * @see AbstractPasswordHasher::hash()
	 */
	public function hash($password, $salt = '') {
		$password .= $salt;
		return parent::hash($password);
	}

	/**
	 * Check password with salt
	 *
	 * @param string $password Plain text password to hash.
	 * @param string $hashedPassword Existing hashed password.
	 * @param string $salt Personal salt
	 * @see SimplePasswordHasher::check()
	 */
	public function check($password, $hashedPassword, $salt = '') {
		return $hashedPassword === $this->hash($password, $salt);
	}

}