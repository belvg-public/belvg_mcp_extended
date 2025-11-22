# **BelVG MCP Extensions for PrestaShop 🚀**

**BelVG MCP Extensions** transforms your PrestaShop store from a passive data source into an intelligent, agentic system.

By extending the official [PrestaShop MCP Server](https://addons.prestashop.com/en/administrative-tools/96617-prestashop-mcp-server.html), this module adds high-level business logic tools that allow AI agents (like ChatGPT, Claude, or Gemini) to perform complex management tasks—such as generating daily briefings or scanning for low stock—with a single command.

## **🌟 Features**

This module injects the following **AI Tools** into your MCP Server:

### **1\. 📊 get\_morning\_briefing**

* **What it does:** Generates a comprehensive executive summary of your store's performance for a specific period.  
* **Why use it:** Perfect for automated daily reports via email or chat.  
* **Data Points:**  
  * Total Revenue (Tax Included)  
  * Total Order Count  
  * Average Cart Value  
  * Top Selling Product (with quantity sold)  
* **Parameters:**  
  * startDate (optional, YYYY-MM-DD, defaults to *yesterday*)  
  * endDate (optional, YYYY-MM-DD, defaults to *today*)

### **2\. 📉 get\_low\_stock\_alerts**

* **What it does:** Proactively scans your inventory to find products that have fallen below a specific threshold.  
* **Why use it:** Enables an AI agent to draft reorder lists for suppliers automatically.  
* **Parameters:**  
  * threshold (optional, default: 10\)  
  * limit (optional, default: 20 items)

## **📦 Installation**

### **Prerequisites**

* **PrestaShop**: 8.0.0 or higher  
* **PHP**: 8.1 or higher  
* **Required Module**: ps\_mcp\_server (The official PrestaShop MCP Server must be installed and active).

### **Manual Installation**

1. Download the latest release or clone this repository.  
2. Rename the folder to belvg\_mcp\_extended (if it isn't already).  
3. Upload the folder to your PrestaShop /modules/ directory.  
4. Go to your **Back Office \> Modules \> Module Manager**.  
5. Search for **"BelVG MCP Extensions"** and click **Install**.

## **⚙️ Configuration & Usage**

There is no configuration page for this module. It works silently in the background, exposing its tools to the main MCP Server.

### **⚠️ Important: Refreshing the Cache**

After installing this module, you **must** clear the MCP Server's tool cache for the AI to "see" the new tools.

1. Connect to your server via FTP or SSH.  
2. Navigate to: /modules/ps\_mcp\_server/.mcp/  
3. **Delete** the file .cache.json.  
4. The next time you connect your AI agent, it will re-scan the system and discover get\_morning\_briefing and get\_low\_stock\_alerts.

### **Example Prompts**

Once installed, you can give your AI agent instructions like:

*"Give me a morning briefing for yesterday's sales."*

*"Check if any products have less than 5 items in stock. If so, list them."*

## **🧑‍💻 For Developers**

This module serves as a clean example of how to extend the PrestaShop MCP ecosystem using **PHP Attributes**.

* **Namespace:** BelVG\\Module\\McpExtended\\  
* **Tool Definitions:** Located in src/Tools/StoreManagementTools.php.  
* **Mechanism:** The module implements the isMcpCompliant() method, returning true, which signals ps\_mcp\_server to scan its classes for \#\[McpTool\] attributes.

### **Adding More Tools**

You can easily add more tools by editing src/Tools/StoreManagementTools.php:

\#\[McpTool(name: 'my\_new\_tool', description: 'Description for the AI')\]  
public function myNewTool($arg1) {  
    // ... logic ...  
}

## **🤝 Contributing**

We welcome contributions\! If you have ideas for more "Agentic" tools (e.g., checking abandoned carts, generating voucher codes), please open a Pull Request.

## **📄 License**

This module is released under the [Academic Free License 3.0][AFL-3.0] 
