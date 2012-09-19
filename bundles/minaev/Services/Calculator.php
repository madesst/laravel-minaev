<?php namespace Minaev\Services;
/**
 * Created by JetBrains PhpStorm.
 * User: madesst
 * Date: 18.09.12
 * Time: 11:43
 * To change this template use File | Settings | File Templates.
 */

class Calculator
{
	public static $arithmetic_operators = array(
			'minus' => '-',
			'plus' => '+',
			'multiplication' => '*',
			'division' => '/'
		);

	public static function parseAndCalculate($url)
	{
		//Нельзя заюзать validator_match из-за того что он не поддерживает регулярки с пайпами (|)
		$pattern = '#([0-9]+(\/('.implode('|', array_flip(self::$arithmetic_operators)).')+\/[0-9]+)+)#i';

		$matches = array();
		preg_match($pattern, $url, $matches);

		if($matches && $matches[0] == $url)
		{
			$search_names = array();
			$replace_symbols = array();

			foreach(self::$arithmetic_operators as $operator_name => $sybmol)
			{
				$search_names[] = '/'.$operator_name.'/';
				$replace_symbols[] = $sybmol;
			}

			$normalized = str_ireplace(
				$search_names,
				$replace_symbols,
				$url
			);
			return round(eval('return @('.$normalized.');'), 0, PHP_ROUND_HALF_UP);
		}
		else
		{
			throw new \Exception('BAD ARGS');
		}
	}
}