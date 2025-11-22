# **BelVG MCP Extensions for PrestaShop**

This module extends the official **PrestaShop MCP Server** with advanced, business-logic focused tools. It transforms your AI agent from a passive data reader into a proactive store manager.

## **🚀 Features**

This module adds the following "Agentic" tools to your MCP Server:

### **1\. get\_morning\_briefing**

A daily executive summary of your store's performance.

* **Inputs:** startDate (optional), endDate (optional).  
* **Returns:**  
  * Total Revenue  
  * Total Order Count  
  * Average Cart Value  
  * Top Selling Product of the period

### **2\. get\_low\_stock\_alerts**

A proactive inventory scanner used to generate reorder lists.

* **Inputs:**  
  * threshold: The quantity below which a product is considered low stock (default: 10).  
  * limit: Max number of items to retrieve.  
* **Returns:** A list of products with their current quantity and reference.

## **📦 Installation**

1. **Download** the belvg\_mcp\_extended folder.  
2. **Upload** it to your PrestaShop modules/ directory.  
3. **Install** the module via the PrestaShop Module Manager.  
   * *Note:* This module does not have a configuration page. It works silently in the background.

## **⚙️ Configuration & Usage**

### **Requirements**

* **PrestaShop** 8.0 or higher.  
* **Module:** ps\_mcp\_server (The official PrestaShop MCP Server) must be installed and active.

### **How to Activate the Tools**

Once installed, you must clear the MCP Server cache so the AI can discover the new tools.

1. Go to your server files via FTP/SSH.  
2. Navigate to /modules/ps\_mcp\_server/.mcp/.  
3. Delete the file .cache.json.  
4. On your next request (via ChatGPT or any other LLM), the tools get\_morning\_briefing and get\_low\_stock\_alerts will appear in the tool list.

## **🤝 Contributing**

Feel free to extend StoreManagementTools.php with more methods using the \#\[McpTool\] attribute.

## **License**

This module is released under the [Academic Free License 3.0][AFL-3.0] 