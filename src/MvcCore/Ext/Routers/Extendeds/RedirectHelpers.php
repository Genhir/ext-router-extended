<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Routers\Extendeds;

/**
 * Responsibility: configurable protected properties with getters and setters,
 *				   internal protected properties and internal methods used 
 *				   in most extended router implementations bellow.
 * Trait for classes:
 * - `\MvcCore\Ext\Routers\Media`
 * - `\MvcCore\Ext\Routers\Localization`
 * - `\MvcCore\Ext\Routers\MediaAndLocalization`
 */
trait RedirectHelpers
{
	/**
	 * If local request object global collection `$_GET` contains any items
	 * and if controller and action in collection have the same values as 
	 * default controller and action values, unset them from request global 
	 * `$_GET` collection.
	 * @return void
	 */
	protected function removeDefaultCtrlActionFromGlobalGet () {
		if ($this->requestGlobalGet) {
			$toolClass = $this->application->GetToolClass();
			list($dfltCtrlPc, $dftlActionPc) = $this->application->GetDefaultControllerAndActionNames();
			$dfltCtrlDc = $toolClass::GetDashedFromPascalCase($dfltCtrlPc);
			$dftlActionDc = $toolClass::GetDashedFromPascalCase($dftlActionPc);
			if (isset($this->requestGlobalGet['controller']) && isset($this->requestGlobalGet['action']))
				if (
					$this->requestGlobalGet['controller'] == $dfltCtrlDc && 
					$this->requestGlobalGet['action'] == $dftlActionDc
				)
					unset($this->requestGlobalGet['controller'], $this->requestGlobalGet['action']);
		}
	}

	/**
	 * Add all remaining params in `$this->requestGlobalGet` into given reference URL string `$targetUrl`.
	 * @param string $targetUrl 
	 */
	protected function redirectAddAllRemainingInGlobalGet (& $targetUrl) {
		if ($this->requestGlobalGet) {
			$amp = $this->getQueryStringParamsSepatator();
			$questionMarkDelimiter = mb_strpos($targetUrl, '?') === FALSE ? '?' : $amp;
			$targetUrl .= $questionMarkDelimiter . str_replace(
				'%2F', '/', 
				http_build_query($this->requestGlobalGet, '', $amp, PHP_QUERY_RFC3986)
			);
		}
	}
}