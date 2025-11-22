<?php

namespace BelVG\Module\McpExtended\Tools;

use PhpMcp\Server\Attributes\McpTool;
use PhpMcp\Server\Attributes\Schema;
use Db;
use Configuration;
use Context;

if (!defined('_PS_VERSION_')) {
    exit;
}

class StoreManagementTools
{
	#[McpTool(
		name: 'get_morning_briefing',
		description: 'Get a daily summary of store performance. Returns total revenue, order count, and top selling product for a specific date range (default: yesterday).'
	)]
	#[Schema(
		properties: [
			'startDate' => [
				'type' => 'string', 
				'description' => 'Start date YYYY-MM-DD (default: yesterday)'
			],
			'endDate' => [
				'type' => 'string', 
				'description' => 'End date YYYY-MM-DD (default: today)'
			]
		]
	)]
	public function getMorningBriefing(?string $startDate = null, ?string $endDate = null): array
	{
		// Default to "Yesterday" if no date provided
		if (!$startDate) {
			$startDate = date('Y-m-d', strtotime('-1 day'));
		}
		if (!$endDate) {
			$endDate = date('Y-m-d');
		}

		$db = Db::getInstance();
		$shopId = (int) Context::getContext()->shop->id;

		// Get revenue and number of orders
		$sqlStats = 'SELECT 
				COUNT(id_order) as order_count, 
				SUM(total_paid_tax_incl) as total_revenue
			FROM ' . _DB_PREFIX_ . 'orders
			WHERE date_add BETWEEN "'.pSQL($startDate).' 00:00:00" AND "'.pSQL($endDate).' 23:59:59"
			AND current_state IN (2, 3, 4, 5) 
			AND id_shop = ' . $shopId;
		
		$stats = $db->getRow($sqlStats);

		// Get top selling
		$sqlTopProduct = 'SELECT od.product_name, SUM(od.product_quantity) as total_sold ' .
			'FROM ' . _DB_PREFIX_ . 'order_detail od ' .
			'LEFT JOIN ' . _DB_PREFIX_ . 'orders o ON od.id_order = o.id_order ' .
			'WHERE o.date_add BETWEEN "' . pSQL($startDate) . ' 00:00:00" AND "' . pSQL($endDate) . ' 23:59:59" ' .
			'AND o.id_shop = ' . $shopId . ' ' .
			'GROUP BY od.product_id, od.product_name ' .
			'ORDER BY total_sold DESC'; 
			
		$topProduct = $db->getRow($sqlTopProduct);

		return [
			'period' => "$startDate to $endDate",
			'revenue' => round((float)($stats['total_revenue'] ?? 0), 2),
			'orders' => (int)($stats['order_count'] ?? 0),
			'average_cart' => ($stats['order_count'] ?? 0) > 0 ? round($stats['total_revenue'] / $stats['order_count'], 2) : 0,
			'top_product' => $topProduct ? $topProduct['product_name'] . " ({$topProduct['total_sold']} sold)" : 'None'
		];
	}

	#[McpTool(
		name: 'get_low_stock_alerts',
		description: 'Scan inventory for products that are below the critical threshold. Use this to generate reorder lists for suppliers.'
	)]
	#[Schema(
		properties: [
			'threshold' => [
				'type' => 'integer', 
				'description' => 'Quantity threshold to trigger alert (default: 10)'
			],
			'limit' => [
				'type' => 'integer', 
				'description' => 'Max number of products to return (default: 20)'
			]
		]
	)]
	public function getLowStockAlerts(int $threshold = 10, int $limit = 20): array
	{
		$shopId = (int) Context::getContext()->shop->id;
		$langId = (int) Context::getContext()->language->id;

		// Get product name, reference, and real quantity
		$sql = 'SELECT 
				pl.name, 
				p.reference, 
				sa.quantity
			FROM ' . _DB_PREFIX_ . 'stock_available sa
			LEFT JOIN ' . _DB_PREFIX_ . 'product p ON p.id_product = sa.id_product
			LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.$langId.')
			WHERE sa.quantity <= ' . (int)$threshold . '
			AND sa.id_shop = ' . $shopId . '
			AND p.active = 1
			AND sa.id_product_attribute = 0 
			ORDER BY sa.quantity ASC
			LIMIT ' . (int)$limit;

		$results = Db::getInstance()->executeS($sql);

		if (empty($results)) {
			return ['status' => 'healthy', 'message' => "No products found below threshold ($threshold)."];
		}

		return [
			'status' => 'alert',
			'count' => count($results),
			'threshold_used' => $threshold,
			'items' => $results
		];
	}
}
