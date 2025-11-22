<?php
/**
 * BelVG MCP Extension Module
 *
 * @author    Pavel Novitsky <pavel@belvg.com>
 * @copyright 2025 BelVG
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

if (!defined('_PS_VERSION_')) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class Belvg_Mcp_Extended extends Module
{
	public function __construct()
	{
		$this->name = 'belvg_mcp_extended';
		$this->tab = 'administration';
		$this->version = '1.0.0';
		$this->author = 'BelVG';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('BelVG MCP Extensions');
		$this->description = $this->l('Adds advanced store management tools (Morning Briefing, Low Stock) to the MCP Server.');
		
		$this->ps_versions_compliancy = ['min' => '8.0', 'max' => _PS_VERSION_];
	}

	public function install()
	{
		return parent::install();
	}

	public function uninstall()
	{
		return parent::uninstall();
	}

	/**
	 * Tell ps_mcp_server that this module provides MCP tools.
	 */
	public function isMcpCompliant(): bool
	{
		return true;
	}
}
