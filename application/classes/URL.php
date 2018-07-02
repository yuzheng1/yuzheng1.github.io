<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * 增加网站全局URL配置
 * @author akirametero
 *
 */

class URL extends Kohana_URL {
/**
   * 返回主站地址
   * @author 龚湧
   * @param string $path
   * @return string
   */
	public static function website($path){
		$base = Kohana::$config->load("site.website");
		$url = $base.ltrim($path,"/");
		return $url;
	}
	//end function
	
	
	/**
	 * 返回static地址
	 * @author 龚湧
	 * @param string $path
	 * @return string
	 */
	public static function staticsite($path){
		$base = Kohana::$config->load("site.static");
		$url = $base.ltrim($path,"/");
		return $url;
	}
	//end function
	
	
	/**
	 * 返回css地址
	 * @author 龚湧
	 * @param string $path
	 * @return string
	 */
	public static function csssite($path){
		$base = Kohana::$config->load("site.css");
		$url = $base.ltrim($path,"/");
		return $url ? $url."?v=".date('YmdHis',time()) : $url;
	}
	//end function
	
	/**
	 * 返回image地址
	 * @author 龚湧
	 * @param string $path
	 * @return string
	 */
	public static function imagesite($path){
		$base = Kohana::$config->load("site.image");
		$url = $base.ltrim($path,"/");
		return $url;
	}
	//end function
	
	/**
	 * 返回js地址
	 * @author 龚湧
	 * @param string $path
	 * @return string
	 */
	public static function jssite($path){
		$base = Kohana::$config->load("site.js");
		$url = $base.ltrim($path,"/");
		return $url ? $url."?v=".date('YmdHis',time()) : $url;
	}
	//end function

}