<?php

class GravatarComponent extends Object {
		
	const ENDPOINT = 'http://gravatar.com/avatar/';
	
	/**
	 * Get either a Gravatar URL or complete image tag for a specified email address.
	 *
	 * @param string $email The email address
	 * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
	 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
	 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
	 * @return String containing either the URL
	 * @source http://gravatar.com/site/implement/images/php/
	 */
	public function getUrl($email, $s = 80, $d = 'mm', $r = 'g') {
		$url = self::ENDPOINT;
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		return $url;
	}
	
	public function getImage($email, $s = 80, $d = 'mm', $r = 'g') {
		
		$image = file_get_contents(self::getUrl($email, $s, $d, $r));
		
		return $image;
		
	}
	
}

class GravatarRating {
	
	const G = 'g';
	
	const PG = 'pg';
	
	const R = 'r';
	
	const X = 'x';
	
}

class GravatarImageSet {
	
	const FOUROFOUR = '404';
	
	const MM = 'mm';
	
	const IDENTICON = 'identicon';
	
	const MONSTERID = 'monsterid';
	
	const WAVATAR = 'wavatar';
	
}

?>